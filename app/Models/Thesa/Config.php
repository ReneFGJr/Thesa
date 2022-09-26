<?php

namespace App\Models\Thesa;

use CodeIgniter\Model;

class Config extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'configs';
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

    function control($class)
        {
            $sx = '';
            $sx .= '<a name="'.$class.'"></a>';
            $sx .= '<h1>' . lang('thesa.' . $class) . '</h1>';
            switch($class)
                {
                    default:
                        $sx .= '<p>Information not found</p>';
                        break;
                }
            return $sx;
        }
}
