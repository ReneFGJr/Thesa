<?php

namespace App\Models\Thesa;

use CodeIgniter\Model;

class Collaborators extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_users';
    protected $primaryKey       = 'id_th_us';
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

    function list($th)
        {
            $dt = $this
                ->join('users', 'th_us_user = users.id_us')
                ->join('thesa_users_perfil','th_us_perfil = id_pf')
                ->where('th_us_th',$th)
                ->findAll();

            $sx = '';
            for ($r=0;$r < count($dt);$r++)
                {
                    $line = $dt[$r];
                    $sx .= view('Admin/UsersPerfil',$line);
                }
            return $sx;
        }
}
