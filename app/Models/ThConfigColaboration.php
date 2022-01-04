<?php

namespace App\Models;

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

    function edit($id)
        {
            $sx = $this->show($id);
            $sx .= $this->btn_ass($id);
            return $sx;
        }

    
    function btn_ass($id)
        {
            $sx = '
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
            Adicionar colaboradores
            </button>
            ';
            $sx .= '<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title" id="myModalLabel">Adicionar colaboradores</h4>
            </div>
            <div class="modal-body"><div class="middle">Informe o e-mail do colaborador</div><br><span class="small">email</span><br><input type="text" name="email" id="email" class="form-control" aria-label="find"><span class="small">function</span><br><input type="radio" name="function" id="function_1" value="1"> Autor<br><input type="radio" name="function" id="function_2" value="2"> Orientador<br><input type="radio" name="function" id="function_3" value="3"> Colaborador<br>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">cancelar</button>
            <button type="button" class="btn btn-primary" data-dismiss="modal" id="submit">adicionar termo</button>
            </div>
            </div>
            </div>
            </div>';
            return $sx;
        }

    function show($th)
        {
            $sx = '';
            $this->join('th_users_perfil','ust_user_role = id_up','left');
            $this->join('users','ust_user_id = id_us','left');
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

                    $perf = $line['up_tipo'];
                    if ($xperf != $perf)
                        {
                            $sx .= '<tr><th colspan="4" class="h5">'.$perf.'</th></tr>';
                            $xperf = $perf;
                        }
                    $link = '<a href="'.PATH.MODULE.'th_config/'.$th.'/colaboration">';
                    $link .= bsicone('trash');
                    $link .= '</a>';
                    if ($line['pa_creator'] == $line['ust_user_id'])
                        {
                            $link = '';
                        }
                    $sx .= '<tr>';
                    $sx .= '<td>'.($id).'</td>';
                    $sx .= '<td>'.$line['us_nome'].'</td>';
                    $sx .= '<td>'.$line['us_email'].'</td>';
                    $sx .= '<td>'.stodbr(sonumero($line['ust_created'])).'</td>';
                    $sx .= '<td>'.$line['up_tipo'].'</td>';
                    $sx .= '<td>'.$link.'</td>';
                    $sx .= '</tr>';
                }
            $sx .= '</table>';
            return $sx;
        }
}
