<?php

namespace App\Models\RDF;

use CodeIgniter\Model;

class Proprieties extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'th_proprieties';
    protected $primaryKey       = 'id_p';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'ip_p','p_prefix','p_propriey','p_custom','p_url','p_group','p_order'
    ];
    protected $typeFields    = [
        'hidden','sql:id_prefix:prefix_name:th_proprieties_prefix','string:100','string:100','string:100','string:10','[1-99]'
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

    function getPropriety($prop,$force=false)
        {
            $dt = $this->where('p_propriey',$prop)->findAll();
            if (count($dt) > 0)
                {
                    return $dt[0]['id_p'];
                }
            return 0;
        }
}
