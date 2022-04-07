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
    }

    function json_prefix($th=0)
        {
            $prefix = array();
            $prefix['@context'] = array();
            $prefix['@context']['skos'] = 'http://www.w3.org/2004/02/skos/core#';
            $prefux['@context']['isothes'] = 'http://purl.org/iso25964/skos-thes#';
            $prefix['@context']['@vocab'] = 'http://schema.org/';
            $js = json_encode($prefix);
            pre($js);
        }
    function json_conceptScheme($th=0)
        {

        }
}
