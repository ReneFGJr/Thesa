<?php

namespace App\Models\Thesa;

use CodeIgniter\Model;

class ThesaUser extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_th_us', 'th_us_user', 'th_us_th',
        'th_us_function',
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

    function add_user_th($th=0, $user=0,$tp=1)
        {
            $dt = $this
                ->where('th_us_user',$user)
                ->where('th_us_th', $th)
                ->findAll();
            if (count($dt) ==0)
                {
                    $dd['th_us_user'] = $user;
                    $dd['th_us_th'] = $th;
                    $dd['th_us_function'] = $tp;
                    $id = $this->insert($dd);
                } else {
                    $id = $dt[0]['id_th_us'];
                }
            return $id;
        }
}
