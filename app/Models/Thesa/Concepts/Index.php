<?php

namespace App\Models\Thesa\Concepts;

use CodeIgniter\Model;

class Index extends Model
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

    function recover_th($id)
        {
            $dt = $this->where('c_concept',$id)->first();
            return($dt['c_th']);
        }

    function le($id)
    {
        $dt = $this
            /*  */
            ->select('
                            id_c, c_concept, c_th,
                            t1.id_ct as id_ct,
                            term_name as label,
                            vc2.vc_label as resource_name,
                            lg_code, lg_language,
                            t1.ct_resource as ct_resource,
                            t1.ct_concept_2 as ct_concept_2,
                            vc1.vc_label as property, spaceName')
            /*  */
            ->join('thesa_concept_term as t1', 't1.ct_concept = id_c')
            ->join('thesa_terms', 'ct_literal = id_term', 'left')
            ->join('owl_vocabulary_vc as vc1', 'ct_propriety = vc1.id_vc', 'left')
            ->join('owl_vocabulary', 'vc1.vc_prefix = id_owl', 'left')
            ->join('owl_vocabulary_vc as vc2', 'ct_resource = vc2.id_vc', 'left')
            ->join('language', 'term_lang = id_lg', 'left')
            ->join('thesa_language', 'lgt_language = id_lg')
            ->where('id_c', $id)
            ->orderBy('vc1.vc_label desc, lgt_order')
            ->findAll();
        return $dt;
    }
}
