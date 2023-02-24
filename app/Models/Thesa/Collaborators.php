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
    protected $allowedFields    = [
        'th_us_th', 'th_us_user', 'th_us_perfil'
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

    function add($user,$th,$perfil)
        {
            $dt = $this
                ->where('th_us_th',$th)
                ->where('th_us_user',$user)
                ->findAll();

            if (count($dt) == 0)
                {
                    $data = array();
                    $data['th_us_th'] = $th;
                    $data['th_us_user'] = $user;
                    $data['th_us_perfil'] = $perfil;
                    $this->set($data)->insert();
                    return true;
                }
            return false;
        }

    function own($th)
    {
        if (isset($_SESSION['id'])) {
            $user = $_SESSION['id'];

            $dt =
                $this
                ->where('th_us_th', $th)
                ->where('th_us_user', $user)
                ->first();

            if ($dt != "")
                {
                    return true;
                } else {
                    return false;
                }

        }
        return false;
    }

    function authors($th)
        {
            $dt = $this
                ->join('users','id_us = th_us_user')
                ->join('thesa_users_perfil', 'th_us_perfil = id_pf')
                ->orderBy('id_pf, id_th_us')
                ->findAll();
            $sx = '';
            foreach($dt as $id=>$line)
                {
                    if ($sx != '') { $sx .= '; '; }
                    $class = $line['pf_name'];
                    $txt = trim(lang($line['pf_name'] . '_abrev'));
                    if ($txt != '')
                        {
                            $txt = ' <sup>('.$txt.')</sup>';
                        }
                    $sx .= $line['us_nome'];
                    $sx .= ' ';
                    $sx .= $txt;
                }
            return $sx;
        }

    function list($th)
    {
        $dt = $this
            ->join('users', 'th_us_user = users.id_us')
            ->join('thesa_users_perfil', 'th_us_perfil = id_pf')
            ->where('th_us_th', $th)
            ->findAll();

        $sx = '';
        for ($r = 0; $r < count($dt); $r++) {
            $line = $dt[$r];
            $sx .= view('Admin/UsersPerfil', $line);
        }
        return $sx;
    }
}
