<?php

namespace App\Models\Api;

use CodeIgniter\Model;

class Json extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'jsons';
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

    function index($d1='',$d2='',$d3='',$d4='',$d5='')
    {
        switch($d1)
            {
                case 'search':
                    $this->json_search($d1,$d2,$d3,$d4);
                    break;
                break;
            }
    }

    function json_search($d1='',$result='',$thID='',$thName='')
        {
            $p = $this->json_prefix();
            header('Content-Type: application/json; charset=utf-8');

            $p['@context']['uri'] = '@id';
            $p['@context']['type'] = '@type';
            $p['@context']['results'] = array('@id'=>'onki:results','@container'=>'@list');
            $p['@context']['prefLabel'] = 'skos:prefLabel';
            $p['@context']['altLabel'] = 'skos:altLabel';
            $p['@context']['hiddenLabel'] = 'skos:hiddenLabel';

            $p['results'] = array();

            for($r=0;$r < count($result);$r++)
                {
                    $line = $result[$r];
                    $uri = URL.'v/'.$line['ct_concept'];
                    $prefLabel = $line['n_name'];
                    $lang = $line['n_lang'];
                    $vocab =$thName;
                    $type = array('skos:Concept');
                    //,'https://www.w3.org/2009/08/skos-reference/skos.html#Concept');
                    
                    array_push($p['results'],array('uri'=>$uri,'type'=>$type,'localname'=>$line['ct_concept'],'prefLabel'=>$prefLabel,'lang'=>$lang,'vocab'=>$vocab));
                    
                }
            $js = json_encode($p);

            echo $js;
            exit;
        }

    function json_prefix($th=0)
        {
            $prefix = array();
            $prefix['@context'] = array();
            $prefix['@context']['skos'] = 'http://www.w3.org/2004/02/skos/core#';
            $prefix['@context']['isothes'] = 'http://purl.org/iso25964/skos-thes#';
            $prefix['@context']['@vocab'] = 'http://schema.org/'; 
            return $prefix;
        }
    function json_conceptScheme($th=0)
        {
            $prefix = $this->json_prefix($th);
            $prefix['#context']['uri'] = '@id';
            $prefix['#context']['type'] = '@type';

        }
}
