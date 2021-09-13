<?php

namespace App\Models;

use CodeIgniter\Model;

class ThLiteral extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'rdf_literal';
    protected $primaryKey           = 'id_rl';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = [];

    // Dates
    protected $useTimestamps        = false;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks       = true;
    protected $beforeInsert         = [];
    protected $afterInsert          = [];
    protected $beforeUpdate         = [];
    protected $afterUpdate          = [];
    protected $beforeFind           = [];
    protected $afterFind            = [];
    protected $beforeDelete         = [];
    protected $afterDelete          = [];

    function resume($id)
        {
            $this->select("count(*) as total, rl_lang");
            $this->join('th_concept_term','ct_term = id_rl');
            $this->groupBy('rl_lang');
            $dt = $this->where('ct_th',$id)->findAll();
            $rst = count($dt);
            return $rst;
        }
}
