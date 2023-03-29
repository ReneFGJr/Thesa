<?php

namespace App\Models\Thesa\Tools;

use CodeIgniter\Model;

class ImportThesa extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'importthesas';
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

    function import($url)
    {

        $Thesa = new \App\Models\Thesa\Index();
        $ThTerm = new \App\Models\RDF\ThTerm();
        $Broader = new \App\Models\Thesa\Relations\Broader();
        $ThConcepts = new \App\Models\Thesa\Concepts\Index();
        $ThTermTh = new \App\Models\RDF\ThTermTh();
        $Language = new \App\Models\Thesa\Language();
        $ThNotes = new \App\Models\RDF\ThNotes();
        $ClassPropriety = new \App\Models\RDF\Ontology\ClassPropryties();
        $ThConceptPropriety = new \App\Models\RDF\ThConceptPropriety();
        $ThProprity = new \App\Models\RDF\ThProprity();

        $th = $Thesa->getThesa();

        $sx = h($url);
        $txt = read_link($url);
        $txt = troca($txt, 'xml:', '');
        $txt = troca($txt, 'rdf:', '');
        $txt = troca($txt, 'xmlns:', '');

        $xml = simplexml_load_string($txt);

        $locate = [];

        foreach ($xml->Concept as $type => $xmlc) {
            $idc = 0;

            foreach ($xmlc as $class => $xmld) {
                $term = '';
                $lang = '';
                $xmld = (array)$xmld;
                $xmla = (array)$xmld['@attributes'];

                switch ($class) {
                    case 'prefLabel':
                        $lang = $Language->convert($xmla['lang']);
                        $term = $xmld[0];
                        $idt = $ThTerm->register($term, $lang, $th);

                        if ($idc > 0) {
                            $class = 'skos:prefLabel';
                            $prop = $ClassPropriety->Class($class);
                            $idr = $ThConceptPropriety->register($th, $idc, $prop, 0, 0, $idt);
                            $sx .= $term . '@' . $lang . ' foi registrado como prefLabel (alternativo)' . ' [' . $idc . ']<br>';
                        } else {
                            $attr = (array)($xmlc);
                            $attr = (array)($attr['@attributes']);
                            if (isset($attr['about'])) {
                                $source = $attr['about'];
                                $source = troca($source, '#', '');
                            } else {
                                $source = '';
                            }
                            $idc = $ThConcepts->register($idt, $th, $source, 'id');
                            $sx .= $term . '@' . $lang . ' foi registrado como prefLabel (principal)[' . $idc . ']' . '<br>';
                        }
                        $locate[$term.'@'.$lang] = $idc;
                        break;
                    case 'altLabel':
                        $lang = $Language->convert($xmla['lang']);
                        $term = $xmld[0];
                        $idt = $ThTerm->register($term, $lang, $th);

                        $class = 'skos:altLabel';
                        $prop = $ClassPropriety->Class($class);
                        $idr = $ThConceptPropriety->register($th, $idc, $prop, 0, 0, $idt);
                        $sx .= $term . '@' . $lang . ' registrado como ' . $class . '<br>';
                        /********************************************** Trava o Termos do Vocabulario */
                        $Term = new \App\Models\RDF\ThTerm();
                        $Term->term_block($idt, $idc, $th);
                        break;

                    case 'hiddenLabel':
                        $lang = $Language->convert($xmla['lang']);
                        $term = $xmld[0];
                        $idt = $ThTerm->register($term, $lang, $th);

                        $class = 'skos:hiddenLabel';
                        $prop = $ClassPropriety->Class($class);
                        $idr = $ThConceptPropriety->register($th, $idc, $prop, 0, 0, $idt);
                        $sx .= $term . '@' . $lang . ' registrado como ' . $class . '<br>';
                        /********************************************** Trava o Termos do Vocabulario */
                        $Term = new \App\Models\RDF\ThTerm();
                        $Term->term_block($idt, $idc, $th);
                        break;

                    case 'scopeNote':
                        $class = 'skos:' . $class;
                        $class = 'skos:definition';
                        $prop = $ThProprity->Class($class);
                        $lang = $Language->convert($xmla['lang']);
                        if (isset($xmld[0])) {
                            $term = $xmld[0];
                            $ThNotes->register($idc, $prop, $term, $lang);
                        }
                        break;
                    case 'broader':
                        break;
                    case 'narrower':
                        break;
                    default:
                        $sx .= h($class . '===>' . $term . '@' . $lang, 5);
                        break;
                }
            }
            $xmlc = (array)$xmlc;
        }

        /************************ RELACOES */
        $xml = simplexml_load_string($txt);

        foreach ($xml->Concept as $type => $xmlc) {
            $idc = 0;
            foreach ($xmlc as $class => $xmld) {
                $term = '';
                $lang = '';
                $xmld = (array)$xmld;
                $xmla = (array)$xmld['@attributes'];

                switch ($class) {
                    case 'prefLabel':
                        $lang = $Language->convert($xmla['lang']);
                        $term = $xmld[0];
                        if (isset($locate[$term.'@'.$lang]))
                            {
                                $idc = $locate[$term . '@' . $lang];
                            } else {
                                echo "ERRO DE LOCATE";
                                exit;
                            }
                        break;

                    case 'broader':
                        $broad = (array)$xmlc;
                        $broad = (array)$broad['broader'];
                        $broad = (array)$broad['@attributes'];
                        $source = $broad['resource'];
                        $source = troca($source, 'https://www.ufrgs.br/tesauros/index.php/thesa/c/','thesa:');

                        $idb = $ThConcepts
                                ->where('c_agency',$source)
                                ->where('c_th', $th)
                                ->first();
                        $idb = $idb['c_concept'];
                        $master = 0;
                        $prop = $ThProprity->Class($class);
                        $Broader->register($th, $idb, $idc, $master);
                        break;
                    default:
                        $sx .= h($class . '=RELATION==>' . $term . '@' . $lang, 5);
                        break;
                }
            }
        }
        return $sx;
    }
}
