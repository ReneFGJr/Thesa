<?php

namespace App\Models\Thesa\Tools;

use CodeIgniter\Model;

class ImportThemaTres extends Model
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

    function reload()
        {
            $sx = '';
            $ThConcepts = new \App\Models\Thesa\Concepts\Index();
            $dt = $ThConcepts
                ->join('owl_vocabulary', 'owl_prefix = "thematres"','LEFT')
                ->where('c_ativo',-1)
                ->where('c_agency like "thematres%"')
                ->first();
            if ($dt != '')
                {
                    $url = $dt['endpoint']. 'xml.php?skosTema='.sonumero($dt['c_agency']);
                    $sx = metarefresh(PATH. 'admin/import/thematres?url='.$url,2);
                }
            return $sx;
        }

    function import($url)
    {
        $sx = h('import');
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

        $sx = h('Import ThemaTres');
        $txt = read_link($url);

        $txt = troca($txt, 'xml:', '');
        $txt = troca($txt, 'rdf:', '');
        $txt = troca($txt, 'xmlns:', '');
        $txt = troca($txt, 'skos:','');
        $txt = troca($txt, 'dc:', '');
        $txt = troca($txt, 'dct:', '');


        $xml = simplexml_load_string($txt);

        $url = (array)$xml->ConceptScheme;
        $url = (array)$url['@attributes'];
        $url = (array)$url['about'];
        $url = $url[0];
        $prefix = 'thematres';
        $Prefix = new \App\Models\RDF\Ontology\Prefix();
        $dt = $Prefix
            ->where('owl_prefix', $prefix)
            ->first();
        if ($dt == '')
            {
                $Prefix->identify($prefix, $url);
            }

        //pre($xml);
        $preLabel = $xml->Concept->prefLabel;
        $lang = $Language->convert('pt');
        $term = $preLabel;
        $idt = $ThTerm->register($term, $lang, $th);
        $sx .= h($term,2);


        /*********************************************** */


        /*********************************************** */
        $na = (array)$xml->Concept;
            foreach($na as $att=>$rs)
                {
                    $sx .= '<li>'.$att.'</li>';
                    switch($att)
                        {
                            case '@attributes':
                                $rs = $rs['about'];
                                $rs = troca($rs, $url, '');
                                $source = troca($rs, '?tema=', 'thematres:');
                                $idc = $ThConcepts->register($idt, $th, $source, 'id');
                                break;
                            case 'scopeNote':
                                $ThNotes->register($idc, $att, $rs, 'pt');
                                break;
                            case 'broader':
                                $line = (array)$rs;
                                if (isset($line['@attributes'])) {
                                    $line = $line['@attributes']['resource'];
                                } else {
                                    $line = ['resource'];
                                }
                                $rs = troca($rs, $url, '');
                                $source = troca($rs, '?tema=', 'thematres:');
                                pre($rs);
                                break;
                            case 'narrower':
                                $nw = (array)$rs;
                                foreach($nw as $id=>$line)
                                    {
                                        $line = (array)$line;
                                        if (isset($line['@attributes']))
                                            {
                                            $line = $line['@attributes']['resource'];
                                            } else {
                                                $line = ['resource'];
                                            }

                                        $line = troca($line,$url,'');
                                        $line = troca($line, '?tema=','thematres:');
                                        $ThConcepts->reserve($th,$line);
                                    }

                            break;
                        }
                }
        return $sx;
        }
    }