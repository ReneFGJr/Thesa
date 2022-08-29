<?php

namespace App\Models\Thesa;

use CodeIgniter\Model;

class TermsThesa extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_terms_th';
    protected $primaryKey       = 'term_th_id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'term_th_id', 'term_th_thesa', 'term_th_term'
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

    function add_term_th($dt)
        {
           $da = $this
                    ->where("term_th_thesa",$dt['term_th_thesa'])
                    ->where("term_th_term",$dt['term_th_term'])
                    ->findAll();
            if (count($da) == 0)
                {
                    $this->insert($dt);
                }
        }
}
