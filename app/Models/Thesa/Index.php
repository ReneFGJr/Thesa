<?php

namespace App\Models\Thesa;

use CodeIgniter\Model;

class Index extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'indices';
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

    function summary($id = '')
    {
        $data = array();
        $Thesa = new \App\Models\Thesa\Thesa();
        $data['nr_thesaurus'] = '';

        if ($id == '') {
            $dt = $Thesa->select('count(*) as total')->findAll();
            $data['nr_thesaurus'] = $dt[0]['total'];

            $ThConcept = new \App\Models\RDF\ThConcept();
            $dt = $ThConcept->select('count(*) as total')->findAll();
            $data['nr_concepts'] = $dt[0]['total'];

            $ThTerm = new \App\Models\RDF\ThTerm();
            $dt = $ThTerm->select('count(*) as total')->findAll();
            $data['nr_terms'] = $dt[0]['total'];
        } else {
            $ThConcept = new \App\Models\RDF\ThConcept();
            $dt = $ThConcept
                ->select('count(*) as total')
                ->where('c_th', $id)
                ->findAll();
            $data['nr_concepts'] = $dt[0]['total'];

            $ThTerm = new \App\Models\RDF\ThTermTh();
            $dt = $ThTerm
                ->select('count(*) as total')
                ->where('term_th_thesa', $id)
                ->findAll();
            $data['nr_terms'] = $dt[0]['total'];
        }

        return $data;
    }
}
