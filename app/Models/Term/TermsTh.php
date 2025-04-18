<?php

namespace App\Models\Term;

use CodeIgniter\Model;

class TermsTh extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_terms_th';
    protected $primaryKey       = 'id_thid';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'term_th_thesa', 'term_th_term', 'term_th_concept', 'term_th_id'
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

    function getTerms($th = 0)
    {
        $dt = $this
            ->select('id_term as id, term_name as term, lg_code as lang')
            ->join('thesa_terms', 'term_th_term = id_term')
            ->join('language', 'term_lang = id_lg', 'LEFT')
            ->where('term_th_thesa', $th)
            ->where('term_th_concept', 0)
            ->groupby('term,id,lang')
            ->orderBy('term_name')
            ->findAll();
        return $dt;
    }

    function register($term, $th, $cp = 0)
    {
        $dt = $this
            ->where('term_th_term', $term)
            ->where('term_th_thesa', $th)
            ->first();

        $dd = [];
        $dd['term_th_term'] = $term;
        $dd['term_th_thesa'] = $th;
        $dd['term_th_concept'] = $cp;

        if ($dt == []) {
            $this->set($dd)->insert();
        } else {
            $this->set($dd)->where('term_th_id', $dt['term_th_id'])->update();
        }
        return true;
    }
}
