<?php

namespace App\Models\Thesa\Content;

use CodeIgniter\Model;

class Resume extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'resumes';
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

    /********************** GLOBAL */

    function resume()
        {
            $data = array();
            $Thesa = new \App\Models\Thesa\Index();

            $dt = $Thesa->select('count(*) as total')->findAll();
            $data['nr_thesaurus'] = $dt[0]['total'];

            $ThConcept = new \App\Models\Thesa\Concepts\Index();
            $dt = $ThConcept->select('count(*) as total')->findAll();
            $data['nr_concepts'] = $dt[0]['total'];

            $ThTerm = new \App\Models\Thesa\Terms\Index();
            $dt = $ThTerm->select('count(*) as total')->findAll();
            $data['nr_terms'] = $dt[0]['total'];


            return view('Thesa/Resume', $data);
        }
}
