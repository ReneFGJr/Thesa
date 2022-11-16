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
            $aurl = 'https://www.ufrgs.br/tesauros/';
            $xurl = $aurl.'index.php/thesa/';

            $sx = '';
            $ThConceptPropriety = new \App\Models\RDF\ThConceptPropriety();
            $ClassPropriety = new \App\Models\RDF\Ontology\ClassPropryties();
            $VocabularyVC = new \App\Models\RDF\Ontology\VocabularyVC();
            $ThProprity = new \App\Models\RDF\ThProprity();
            $Language = new \App\Models\Language\Index();
            $ThConcept = new \App\Models\RDF\ThConcept();
            $ThTermTh = new \App\Models\RDF\ThTermTh();
            $Midias = new \App\Models\Thesa\Midias();
            $ThTerm = new \App\Models\RDF\ThTerm();
            $Thesa = new \App\Models\Thesa\Thesa();


            $th = $Thesa->getThesa();

            $url = $xurl.'export_to_thesa2/374';

            $txt = $this->load_link($url);
            $txt = json_decode($txt);

            /********************************** TERMS */
            $terms = $txt->terms;
            $dt = array();
            $loop = 0;

            /***************************** Concept */

            //$agency = 'thesa:'.$txt->c_concept;
            //$idc = $ThConcept->register($id_term, $th, $agency);
            $concepts = array();

            foreach($terms as $id=>$content)
                {
                    /***************************** Register Concept */
                    $agency = 'thesa:c'.$id;

                    $idc = $ThConcept->register_concept($th, $agency);
                    $concepts[$id] = $idc;

                    $sx .= 'Create '.$agency.' ['.$idc.']';
                    $sx .= '<ul class="small">';

                    foreach($content as $nterm=>$term)
                        {
                            /**************** Recupera propriedade */
                            $prop = (string)$term->proprety;

                            /**************** Verifica código da propriedade */
                            $id_prop = $VocabularyVC->find_prop($prop);

                            $qualy = $VocabularyVC->quali;
                            $resource = 2;

                            $lang = $Language->getLanguage($term->lang);
                            $idt = $ThTerm->register($nterm, $lang, $th);
                            $ThTermTh->update_term_th($idt,$th,$idc);

                            /* Registra propriedade */
                            $ThConceptPropriety->register($th, $idc, $id_prop, $qualy, $resource, $idt);
                            $sx .= '<li class="ms-4">' . $nterm . ' - ' . $idt . ' <sup>('.$prop. ')</sup> <sup>P'. $id_prop.'-Q'.$qualy.'</sup> </li>';
                        }

                    /******************************** Recupera Arquivo Json individual */
                    $url = $xurl.'c/' . $idc . '/json';
                    $txt = json_decode($this->load_link($url));

                    /******************************** Recupera propriedades - IMAGES*/
                    /*************************************** M I D I A S ************/
                    $images = $txt->images;
                    if (count($images) > 0)
                        {
                            foreach($images as $idj=>$urli)
                                {
                                    $images_url = $aurl.$urli;
                                    if (!strpos($images_url,'0000000_287px'))
                                        {
                                            $Midias->image_save_url($idc, $th, $images_url);
                                        }
                                }
                        }

                    $notes = $txt->notes;
                    if (count($notes) > 0)
                        {
                            pre($notes);
                        }

                    $sx .= '</ul>';
                    //return $sx;
                }

            foreach($concepts as $url=>$id)
                {
                    echo "<br>$url->$id";
                }
            return $sx;
        }
}
