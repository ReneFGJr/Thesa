<?php

namespace App\Models\RDF;

use CodeIgniter\Model;

class ThConceptPropriety extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_concept_term';
    protected $primaryKey       = 'id_ct';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_ct', 'ct_concept', 'ct_th',
        'ct_literal', 'ct_use', 'ct_propriety',
        'ct_resource'

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

    function register($th, $concept, $prop, $resource, $literal)
        {
            $data['ct_concept'] = $concept;
            $data['ct_propriety'] = $prop;
            $data['ct_th'] = $th;
            $data['ct_literal'] = $literal;
            $data['ct_resource'] = $resource;
            $data['ct_use'] = 0;

            $da = $this
                ->where('ct_th', $th)
                ->where('ct_concept', $concept)
                ->where('ct_propriety', $prop)
                ->where('ct_propriety', $prop)
                ->where('ct_resource', $literal)
                ->findAll();

            if (count($da) == 0)
                {
                    $id = $this->set($data)->insert();
                } else {
                    $id = $da[0]['id_ct'];
                }
            return $id;
        }
}
