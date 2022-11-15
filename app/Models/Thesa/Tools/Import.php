<?php

namespace App\Models\Thesa\Tools;

use CodeIgniter\Model;

class Import extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'imports';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    function index($id)
        {
            return $this->import_thesa($id);
        }

    function load_link($url)
        {
        $dir = '.tmp/';
        dircheck($dir);
        $dir = '.tmp/.cache';
        dircheck($dir);

        $file = $dir.'/'.md5($url);
        $load = true;
        if (file_exists($file))
            {
                $data = filemtime($file);
                if (date("Y-m-d") == date("Y-m-d",$data))
                    {
                        $load = false;
                        echo 'CACHED';
                    }
            }

        if ($load == true)
            {
                $txt = read_link($url);
                file_put_contents($file, $txt);
            } else {
                $txt = file_get_contents($file);
            }
        return $txt;
        }

    function import_thesa($id)
        {
            $sx = '';
            $ThConceptPropriety = new \App\Models\RDF\ThConceptPropriety();
            $ClassPropriety = new \App\Models\RDF\Ontology\ClassPropryties();
            $ThProprity = new \App\Models\RDF\ThProprity();
            $Language = new \App\Models\Language\Index();
            $ThConcept = new \App\Models\RDF\ThConcept();
            $ThTerm = new \App\Models\RDF\ThTerm();
            $Thesa = new \App\Models\Thesa\Thesa();

            $th = $Thesa->getThesa();

            $url = 'https://www.ufrgs.br/tesauros/index.php/thesa/export_to_thesa2/374';

            $txt = $this->load_link($url);
            $txt = json_decode($txt);

            /********************************** TERMS */
            $terms = $txt->terms;
            $dt = array();
            $loop = 0;

            /***************************** Concept */

            //$agency = 'thesa:'.$txt->c_concept;
            //$idc = $ThConcept->register($id_term, $th, $agency);

            foreach($terms as $id=>$content)
                {
                    $agency = 'thesa:c'.$id;
                    $idc = $ThConcept->register_concept($th, $agency);

                    foreach($content as $nterm=>$term)
                        {
                            $prop = (string)$term->proprety;
                            echo h($prop);
                            $dt_prop = $ThProprity->find_prop($prop);
                            $id_prop = $dt_prop['id_p'];

                            $qualy = 0;
                            $resource = 2;

                            $lang = $Language->getLanguage($term->lang);
                            $idt = $ThTerm->register($nterm, $lang, $th);
                            $sx .= '<li>'.$nterm.'-'.$idt.'</li>';

                            /* Registra propriedade */
                            $ThConceptPropriety->register($th, $idc, $id_prop, $qualy, $resource, $idt);

                        }

                    /************************** Relations */
                    $url = 'https://www.ufrgs.br/tesauros/index.php/thesa/c/'.$id.'/json';
                    if (!isset($dt[$id]))
                        {
                            $dt[$id] = json_decode(read_link($url));
                        }
                }
            pre($dt);

        }
}
