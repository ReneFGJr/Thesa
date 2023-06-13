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

    function management($th)
        {
            $sx = '';
            $access = $this->own($th);
            if ($access)
                {
                    $sx .= '<span class="handle" onclick="newwin(\''.PATH.'/admin/collaborators/add/'.$th.'\',1024,600);">';
                    $sx .= bsicone('plus');
                    $sx .= '</span>';
                }
            return $sx;
        }

    function index($d1,$th,$d3,$d4)
        {
            $sx = '';
            switch($d1)
                {
                    case 'add':
                        $sx .= $this->form($th);
                    break;

                    case 'del':
                        $sql = "delete from thesa_users where id_th_us = $th";
                        $this->query($sql);
                        return wclose();
                        break;
                    default:
                        echo '==>'.$d1.'='.$th;
                        break;
                }
                return $sx;
        }

    function form($th)
        {
            $value = get("name");
            $sx = '';
            $sx .= h(lang('thesa.collaborators.add'),3);
            $sx .= form_open();
            $sx .= form_label(lang('thesa.user_name'));
            $sx .= form_input(array('name'=>'name','class'=>'form-control full', 'value'=>$value));
            $sx .= form_submit('action',lang('thesa.search'));
            $sx .= form_close();

            if ($value != '')
                {
                    $Socials = new \App\Models\Socials();
                    $dt = $Socials
                            ->where("us_nome like '%$value%'")
                            ->ORwhere("us_email like '%$value%'")
                            ->orderby('us_nome')
                            ->findAll();
                    $sfa = form_open();
                    $sa = '';
                    $sb = '';

                    foreach($dt as $id=>$line)
                        {
                            $sa .= form_radio('id_us',$line['id_us']);
                            $sa .= $line['us_nome'];
                            $sa .= ' ('.$line['us_email'].')';
                            $sa .= '<br>';
                        }
                    $sql = "select * from thesa_users_perfil";
                    $dba = $Socials->query($sql);
                    $dba = $dba->getresult();

                    foreach ($dba as $id => $line) {
                        $line = (array)$line;
                        $sb .= form_radio('id_pf',$line['id_pf']);
                        $sb .= lang($line['pf_name']);
                        $sb .= '<br>';
                    }
                    $sa .= form_submit('action', lang('thesa.save'));
                    $sfb = form_close();

                    $us = get("id_us");
                    $pf = get("id_pf");

                    if (($us != '') and ($pf != ''))
                        {
                            $this->add($us,$th,$pf);
                            return wclose();
                        }

                    $sx .= '<br/>'.bsc($sa,6).bsc($sb,6);
                    $sx = $sfa . bs($sx). $sfb;
                }
            return $sx;
        }

    function add($user,$th,$perfil=1)
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
        $Socials = new \App\Models\Socials();
        $user = $Socials->getUser();
        if ($user > 0) {

            $dt =
                $this
                ->where('th_us_th', $th)
                ->where('th_us_user', $user)
                ->FindAll();

            if (count($dt) > 0)
                {
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
                ->join('users','id_us = th_us_user')
                ->join('thesa_users_perfil', 'th_us_perfil = id_pf')
                ->where('th_us_th',$th)
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
        $sx = bs($sx);
        return $sx;
    }
}
