<?php

namespace App\Models\Schema;

use CodeIgniter\Model;

class SchemaExternal extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'schema_external';
    protected $primaryKey       = 'id_se';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_se','se_name','se_url',
        'se_update','se_format','se_ative',
    ];

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

    function import_external_schema_upload($dt,$id)
        {
            if (isset($dt['tmp_name']))
                {
                    $file = $dt['tmp_name'];
                    $txt = file_get_contents($file);
                    $sx = $this->import_content($txt,$id);
                }
        } 

    function import_content($txt,$id)
        {
        $txt = troca($txt,'rdf:','');
        $txt = troca($txt,'dc:','dc_');
        $txt = troca($txt,'dct:','dct_');
        $txt = troca($txt,'skos:','skos_');
        $txt = troca($txt,'xml:','');
        $txt = troca($txt,'skosxl:','skosxl_');

         $xml = simplexml_load_string($txt);
         $xml = (array)$xml;
         $this->xml_skosmos($xml);
         pre($xml);
        }

    function xml_skosmos($xml)
        {
            $SchemaExternalTerms = new \App\Models\Schema\SchemaExternalTerms();
            $data = (array)$xml['skos_ConceptScheme'];
            $att = (array)$data['@attributes'];
            $tit = (array)$data['dct_title'][0];            
            echo h($tit[0],1);
            $url = $att['about'];
            echo h($url,4);
            $dd['se_name'] = $tit[0];
            $dd['se_url'] = $url;
            $dd['se_format'] = 'skos_mos';
            $dd['se_ative'] = 1;
            $dd['se_update'] = date("Y-m-d");

            $dt = $this->where('se_url',$url)->findAll();
            if (count($dt) == 0)
                {
                    $this->insert($dd);
                    $dt = $this->where('se_url',$url)->findAll();
                }

            $id_skos = $dt[0]['id_se'];

            $cpt = $xml['skos_Concept'];

            //pre($xml->skos_Concept);

            foreach($cpt as $id=>$cpt)
                {
                    $line = (array)$cpt;                    

                    $att = (array)$line['@attributes'];
                    $urli = $att['about'];
                    $urli = troca($urli,$url,':');
                    $urli = troca($urli,'/','');

                    $term = $cpt->skos_prefLabel;
                    $terms = array();
                    foreach($term as $id=>$pref)
                        {
                            $te = (array)$pref;                            
                            $lang = $te['@attributes']['lang'];
                            $ter = $te[0];
                            $terms[$lang] = $ter;

                            $da['see_term'] = $ter;
                            $da['see_lang'] = $lang;
                            $da['see_resource'] = $urli;
                            $da['see_se'] = $id_skos;

                            if (($lang == 'pt') or ($lang == 'en') or ($lang == 'fr') or ($lang == 'es'))
                            {
                                $dx = $SchemaExternalTerms
                                    ->where('see_resource',$urli)
                                    ->where('see_term',$ter)
                                    ->findAll();
                                if (count($dx) == 0)
                                    {
                                        $SchemaExternalTerms->insert($da);
                                    }
                            } else {
                                //echo '+==>'.$lang.'<br>';
                            }
                        }
                    
                        $term = $cpt->skos_altLabel;
                        $terms_alt = array();
                        foreach($term as $id=>$pref)
                            {
                                $te = (array)$pref;                            
                                $lang = $te['@attributes']['lang'];
                                $ter = $te[0];
                                $terms_alt[$lang] = $ter;
                            }   
                }
                
        }
    

    function import_external_schema($dt,$id)
        {
            pre($dt);
        }
}
