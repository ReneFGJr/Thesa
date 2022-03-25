<?php

namespace App\Models;

use CodeIgniter\Model;

class ThConfigRelations extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thconfigrelations';
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

    function edit($id=0,$ac='')
        {
            $Schema = new \App\Models\Schema\Index();
            $sx = h('thesa.relations',3);
            $sx .= $Schema->list($id);           
            $sx .= $Schema->btn_inport_external($id);
            return $sx;
        }
}
