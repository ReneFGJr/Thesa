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
        'th_us_th',
        'th_us_user',
        'th_us_perfil'
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

    function isMember($apikey, $th)
    {
        $Users = new \App\Models\Socials();
        $user = $Users->where('us_apikey', $apikey)->first();
        if (isset($user['id_us'])) {
                $idUser = $user['id_us'];
            } else {
                $idUser = 0;
            }

        $Thesa = new \App\Models\Thesa\Thesa();
        $thD = $Thesa->where('id_th', $th)->first();
        if ($thD == []) {
            return 0;
        } else {
            if ($thD['th_own'] == $idUser) {
                return 1;
            }
        }

        $dt = $this
            ->where('th_us_th', $th)
            ->where('th_us_user', $idUser)
            ->first();

        if ($dt == []) {
            return 0;
        } else {
            return 1;
        }
    }

    function authorizedSave($th, $user)
    {
        $dt = $this
            ->join('thesa_users_perfil', 'id_pf = th_us_perfil')
            ->where('th_us_user', $user)
            ->where('th_us_th', $th)
            ->first();
        if ($dt == []) {
            return 0;
        }
        return 1;
    }

    function management($th)
    {
        $sx = '';
        $access = $this->own($th);
        if ($access) {
            $sx .= '<span class="handle" onclick="newwin(\'' . PATH . '/admin/collaborators/add/' . $th . '\',1024,600);">';
            $sx .= bsicone('plus');
            $sx .= '</span>';
        }
        return $sx;
    }

    function index($d1, $th, $d3, $d4)
    {
        $sx = '';
        switch ($d1) {
            case 'add':
                $sx .= $this->form($th);
                break;

            case 'del':
                $sql = "delete from thesa_users where id_th_us = $th";
                $this->query($sql);
                return wclose();
                break;
            default:
                echo '==>' . $d1 . '=' . $th;
                break;
        }
        return $sx;
    }

    function members_remove()
        {
            $RSP = [];
            $this->where('th_us_th', get('thesaID'))
                ->where('id_th_us', get('id'))
                ->delete();
            $RSP['status'] = '200';
            $RSP['message'] = 'Usuário removido com sucesso';
            return $RSP;
        }

    function members_register()
    {
        $RSP = [];
        $Socials = new \App\Models\Socials();
        $query = get("query");
        $thesaID = get("thesaID");
        $perfil = get("type");
        $cp = 'us_nome,id_us,us_affiliation';

        if ($thesaID == '') {
            $RSP['status'] = '500';
            $RSP['message'] = 'Thesa não encontrado';
            return $RSP;
        }

        $dt = $Socials
            ->select($cp)
            ->where('us_email', $query)
            ->first();

         if ($dt == []) {
            $RSP['status'] = '500';
            $RSP['message'] = 'Usuário não encontrado';
        } else {
            $dt['th_us_th'] = $thesaID;
            $dt['th_us_user'] = $dt['id_us'];
            $dt['th_us_perfil'] = $perfil;
            $dd = $this->where('th_us_th', $thesaID)
                ->where('th_us_user', $dt['id_us'])
                ->first();

            if ($dd == []) {
                $this->set($dt)->insert();
                $RSP['status'] = '200';
                $RSP['message'] = 'Usuário adicionado com sucesso';
            } else {
                if ($dd['th_us_perfil'] != $perfil) {
                    $this
                        ->set($dt)
                        ->where('id_th_us', $dd['id_th_us'])
                        ->where('th_us_user', $dt['id_us'])
                        ->update();
                    $RSP['status'] = '200';
                    $RSP['message'] = 'Usuário já atualizado com sucesso';
                } else {
                    $RSP['status'] = '500';
                    $RSP['message'] = 'Usuário já cadastrado';
                }
            }
        }
        return $RSP;
    }

    function add($user, $th, $perfil = 1)
    {
        $dt = $this
            ->where('th_us_th', $th)
            ->where('th_us_user', $user)
            ->findAll();

        if (count($dt) == 0) {
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
        $Socials = new \App\Models\Socials();
        $user = $Socials->getUser();
        if ($user > 0) {

            $dt =
                $this
                ->where('th_us_th', $th)
                ->where('th_us_user', $user)
                ->FindAll();

            if (count($dt) > 0) {
                return 1;
            } else {
                return 0;
            }
        }
        return false;
    }



    function authors($th)
    {
        $dt = $this
            ->join('users', 'id_us = th_us_user')
            ->join('thesa_users_perfil', 'th_us_perfil = id_pf')
            ->where('th_us_th', $th)
            ->orderBy('id_pf, id_th_us')
            ->findAll();


        $dd = [];
        foreach ($dt as $id => $line) {
            $line = (array)$line;
            $da = [];
            $dad['fullname'] = $line['us_nome'];
            $dad['affiliation'] = $line['us_affiliation'];
            $dad['function'] = $line['pf_name'];
            $dad['idv'] = $line['id_th_us'];
            $dad['id'] = $line['id_us'];
            array_push($dd, $dad);
        }
        $RSP['members'] = $dd;
        $RSP['total'] = count($dd);
        return ($RSP);
    }
}
