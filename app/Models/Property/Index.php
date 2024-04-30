<?php

namespace App\Models\Property;

use CodeIgniter\Model;

class Index extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_property';
    protected $primaryKey       = 'id_p';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_p','p_name', 'p_reverse',
        'p_equivalente', 'p_range', 'p_group',
        'p_description','p_th','p_global'
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

    function findPropriety($prop,$th=0)
        {
            $dt = $this
                ->where('p_name',$prop)
                ->where('(p_th = '.$th.' or p_global = 1)')
                ->first();
            if ($dt == [])
                {
                    echo "Propriety OPS $prop";
                }
            return $dt['id_p'];
        }
}
