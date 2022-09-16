<?php

namespace App\Models\RDF;

use CodeIgniter\Model;

class ThConcept extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_concept';
    protected $primaryKey       = '	id_c';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_c', 'c_concept', 'c_th',
        'c_ativo', 'c_agency'
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

    function register($th, $term, $agency='')
        {
            $dt = $this->where('c_concept', -1)->where('c_th', $th)->findAll();
            if (count($dt) == 0)
            {
                $data['c_concept'] = 0;
                $data['c_th'] = $th;
                $data['c_concept'] = -1;
                $data['c_ativo'] = 1;
                $data['c_agency'] = $agency;

                $id = $this->insert($data);
            } else {
                $id = $dt[0]['id_c'];
            }

            echo h($id);
            exit;

        }
}
