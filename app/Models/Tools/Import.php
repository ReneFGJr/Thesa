<?php

namespace App\Models\Tools;

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

    function import()
    {
        $th = 3;
        $Term = new \App\Models\Term\Index();
        $Notes = new \App\Models\Propriety\Notes();
        $Concept = new \App\Models\Concept\Index();
        $Language = new \App\Models\Language\Index();
        $Propriety = new \App\Models\Propriety\Index();

        $file = '.tmp/thesa_0374_20240429.xml';
        $xml = simplexml_load_file($file);

        foreach ($xml->children() as $child) {
            $field = $child->getName();
            if ($field == 'Concept') {
                $attr = (array)$child->attributes();
                $thesa = $attr['@attributes']['about'];
                $IDC = $Concept->registerInport($thesa,$th);

                $field = (array)$child->prefLabel;
                echo '<h1>'.$thesa.'</h1>';

                foreach ($child as $element => $value) {
                    $value = (array)$value;
                    switch ($element) {
                        case 'prefLabel':
                            $lang = $Language->search($value['@attributes']['lang']);
                            $term = $value[0];
                            $Term->register($term,$lang);
                            break;
                        /************************************** altLabel */
                        case 'altLabel':
                            $lang = $Language->search($value['@attributes']['lang']);
                            $term = $value[0];
                            $Term->register($term, $lang);
                            break;
                        /************************************** altLabel */
                        case 'hiddenLabel':
                            $lang = $Language->search($value['@attributes']['lang']);
                            $term = $value[0];
                            $Term->register($term, $lang);
                            break;
                        /************************************** altLabel */
                        case 'scopeNote':
                            $type = $value['@attributes']['type'];
                            $prop = $Propriety->findPropriety($type);
                            $lang = $Language->search($value['@attributes']['lang']);
                            if (isset($value[0]))
                                {
                                    $term = $value[0];
                                    $Notes->register($IDC, $prop, $term, $lang, $th);
                                }
                            break;

                        default:
                            echo '=======>'. $element.'<hr>';
                    }
                }
            }
            // Encontrar todos os elementos 'prefLabel'
            foreach ($xml->xpath('//prefLabel[@xml:lang="pt"]') as $item) {
                echo "Conteúdo de <prefLabel xml:lang='pt'>: " . $item . "<br>";
            }
        }
        echo "Atributo de um elemento específico: " . $xml->seuElemento['atributo'] . "<br>";
        exit;
    }
}
