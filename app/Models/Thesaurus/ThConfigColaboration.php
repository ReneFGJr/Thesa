<?php

namespace App\Models\Thesaurus;

use CodeIgniter\Model;

class ThConfigColaboration extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'th_users';
    protected $primaryKey       = 'id_ust';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_ust','ust_user_id','ust_user_role',
        'ust_th','ust_status','ust_tipo'
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

    var $status = 0;

    function edit($id)
        {
            $sx = $this->show($id);
            $sx .= $this->btn_ass($id);
            return $sx;
        }

    
    function btn_ass($id)
        {
            $url = PATH.MODULE.'popup/colaboration/'.$id;
            $sx = '            
            <button type="button" class="btn btn-outline-primary" onclick="'.newwin($url,800,400).'">
            '.lang('thesa.colaboration_add').'
            </button>
            ';
            return $sx;
        }

    function add($th,$user,$type)
        {
            $this->where('ust_user_id',$user);            
            $this->where('ust_th',$th);
            $dt = $this->findAll();

            $active = 0;
            for($r=0;$r < count($dt);$r++)
                {
                    $line = $dt[$r];
                    if ($line['ust_status'] == 1)
                        {
                            $active = 1;
                        }
                }

            if ($active == 1)
                {
                    $this->status = 1;
                    $sx  = bsmessage(lang('thesa.colaboration_already'),3);
                    return($sx);
                }
            

            if (count($dt) == 0)
                {
                    $data = array(
                    'ust_th' => $th,
                    'ust_user_id' => $user,
                    'ust_user_role' => $type,
                    'ust_status' => 1,
                    'ust_tipo' => $type
                );
                $this->insert($data);
                $id = $this->insertID();
                } else {
                    $data = array(
                    'ust_status' => 1,
                    );
                    $this->set($data)->where('id_ust',$dt[0]['id_ust'])->update();
                    $id = $dt[0]['id_ust'];    
                }
            return($id);
        }

    function add_colaboration($id)
        {
            $user = get("user");
            $email = get("email");
            $type = get("type");

            $ThUsersPerfil = new \App\Models\Thesaurus\ThUsersPerfil();
            $ThUsers = new \App\Models\Thesaurus\ThUsers();
            $ThUser = new \App\Models\Thesaurus\ThUser();

            $sx = h(lang('thesa.colaboration_add'));
            $sx .= form_open();

            /********************************************************* Users */
            if ($email != '')
                {
                    $dt = $ThUser->findEmail($email);
                    if (count($dt) == 1)
                        {
                            if ($type != '')
                            {
                                $sx .= $this->add($id,$dt[0]['id_us'],$type);
                                if ($this->status == 0)
                                    {
                                        $sx = wclose();
                                        return $sx;
                                    }                             
                            } else {
                                $sx .= bsmessage(lang('thesa.select_author_type'),3);
                            }
                            
                        } else {
                            $sx .= $ThUser->radiobox($dt,'users');
                            $sx .= bsmessage(lang('thesa.email_not_found'),3);
                        }                    
                }
            if ($user == '')
                {
                    $sx .= '<span class="small">'.lang('thesa.colaboration_add_email').'</span>';
                    $sx .= form_input(['name' => 'email', 'id' => 'email', 'class' => 'form-control mb-4', 'value' => set_value('email'), 'placeholder' => 'Email']);
                }

            /*********************************** PARTH II */
            $sx .= '<div class="small">'.lang('thesa.colaboration_add_role').'</div>';
            $sx .= $ThUsersPerfil->perfil_radiobox('type');

            $sx .= form_submit(['name' => 'action', 'class' => 'mt-4 btn btn-outline-primary', 'value' => lang('thesa.colaboration_add')]);

            $sx .= form_close();



            $sx = bs(bsc($sx,12));
            return $sx;
        }

    function excluding($id,$th,$act='',$conf='')
        {
            $ThAccess = new \App\Models\Thesaurus\ThAccess();
            $ac = $ThAccess->access();
            $sx = wclose();
            if ($conf == 'yes')
                {
                    $dd = array();
                    $dd['ust_status'] = 0;
                    $this
                        ->set($dd)
                        ->where('id_ust', $id)
                        ->update();
                }
            return $sx;
        }

    function show($th)
        {
            $Socials = new \App\Models\Socials();
            $sx = '';
            $this->join('th_users_perfil','ust_user_role = id_up','left');
            $this->join($Socials->table,'ust_user_id = id_us','left');
            $this->join('th_thesaurus','ust_th = id_pa','left');
            $this->where('ust_th',$th);
            $this->orderBy('up_order,id_ust','asc');
            $dt = $this->findAll();
            $id = 0;
            $sx .= '<table class="table">';
            $xperf = 'x';
            for ($r=0;$r < count($dt);$r++)
                {
                    $id++;
                    $line = $dt[$r];

                    $class = '';
                    if ($line['ust_status'] == 0)
                        {
                            $class = 'text-decoration-line-through';
                        }

                    $perf = $line['up_tipo'];
                    if ($xperf != $perf)
                        {
                            $sx .= '<tr><th colspan="4" class="h5">'.$perf.'</th></tr>';
                            $xperf = $perf;
                        }

                    /*********************** POPUP EXCLUDE */                        
                    $url_del = PATH.MODULE.'popup/colaboration/'.$line['id_ust'].'/'.$th.'/del/yes';
                    $link = '<a href="'.PATH.MODULE.'th_config/'.$th.'/colaboration" onclick="if (confirm(\''.lang('thesa.confirm_exclusion?').'\')) { '.newwin($url_del,800,400).' }">';
                    $link .= bsicone('trash');
                    $link .= '</a>';
                    if ($class != '')
                        {
                            $link = '';
                        }
                    if (($line['pa_creator'] == $line['ust_user_id']) or ($class != ''))
                        {
                            $link = '';
                        }

                    /*************************** TABLE INSIDE */
                    $sx .= '<tr>';
                    $sx .= '<td>'.($id).'</td>';
                    $sx .= '<td class="'.$class.'">'.$line['us_nome'].'</td>';
                    $sx .= '<td class="'.$class.'">'.$line['us_email'].'</td>';
                    $sx .= '<td class="'.$class.'">'.stodbr(sonumero($line['ust_created'])).'</td>';
                    $sx .= '<td class="'.$class.'">'.$line['up_tipo'].'</td>';
                    $sx .= '<td>'.$link.'</td>';
                    $sx .= '</tr>';
                }
            $sx .= '</table>';
            return $sx;
        }
}
