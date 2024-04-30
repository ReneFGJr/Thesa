<?php

namespace App\Models\Term;

use CodeIgniter\Model;

class TermConcept extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_concept_term';
    protected $primaryKey       = 'id_ct';
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

    function le($id, $prop='')
        {
            $this->select('term_name as Term, p_name as Prop, ct_th as th, lg_code as Lang, lg_language as Language, lg_cod_short as LangCode');
            $this->join('thesa_property', 'id_p = ct_propriety');
            $this->join('thesa_terms', 'ct_literal = id_term');
            $this->join('language', 'term_lang = id_lg');
            $this->where('ct_concept',$id);
            if ($prop != '')
                {
                    $this->where('p_name', $prop);
                }
            $dt = $this->findAll();
            return $dt;
        }
}
