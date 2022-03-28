<?php

namespace App\Models\Schema;

use CodeIgniter\Model;

class SchemaExternalTh extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'schema_external_th';
    protected $primaryKey       = 'id_set';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_set','set_th','set_se','set_active'
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

    function show($th)
        {
            $this->join('th_thesaurus','set_se = id_pa');
            $this->where('set_th',$th);
            $this->orderBy('pa_name','asc');
            $dt = $this->findAll();

            $sx = '<table class="table table-sm table-striped">';
            $sx .= '<tr><th width="5%" class="text-center">'.msg('thesa.set_th').'</th>
                        <th width="90%">'.msg('thesa.set_se').'</th>
                        <th width="1%">'.msg('thesa.set_tr').'</th>
                        </tr>';
            for($r=0;$r < count($dt);$r++)
                {
                    $line = $dt[$r];

                    $class = '';

                    if ($line['set_active'] == 0)
                        {
                            $class = 'text-decoration-line-through';
                        }

                    $sx .= '<tr>';
                    $sx .= '<td class="text-center">'.$line['id_pa'].'</td>';
                    $sx .= '<td class="'.$class.'">';
                    $sx .= $line['pa_name'];
                    $sx .= '</td>';

                    /*********************** POPUP EXCLUDE */
                    $url_del = PATH.MODULE.'popup/relations/'.$line['id_pa'].'/'.$th.'/del/yes';
                    $link = '<a href="'.PATH.MODULE.'th_config/'.$th.'/relations" onclick="if (confirm(\''.lang('thesa.confirm_exclusion?').'\')) { '.newwin($url_del,800,400).' }">';
                    $link .= bsicone('trash');
                    $link .= '</a>';  

                    if ($class != '') { $link = ''; }                  

                    $linka = '</a>';
                    $sx .= '<td class="text-center">';
                    $sx .= $link;
                    $sx .= '</td>';

                    $sx .= '</tr>';
                }
            if (count($dt)==0)
                {
                    $sx .= '<tr><td colspan="3" class="text-center">'.msg('thesa.no_relations').'</td></tr>';
                }                
            $sx .= '</table>';
            return $sx;
        }

function list_thesa($id)
        {
            $SchemaExternalTh = new \App\Models\Schema\SchemaExternalTh();
            $sx = '';
            $sx .= h(lang('thesa.relations_thesa'),4);

            $th = get("select_thesa");
            if (round($th) > 0)
                {
                    $SchemaExternalTh->setting($id,$th);
                    $sx = wclose();
                    return $sx;
                }

            /************************************************************************* THESA OPEN */
            $sql = "select id_set, pa_name, id_pa, pa_status, set_se
                        from th_thesaurus 
                        left join schema_external_th ON set_se = id_pa and set_th = 250
                        where pa_status = 2 and id_pa <> $id
                        order by pa_name, id_pa";    
             
            $rlt = $this->db->query($sql);
            $rlt = $rlt->getResult();

            $sx .= form_open();
            $sx .= '<select name="select_thesa" class="form-control mb-2" size=6 style="font-size: 110%;">';
            for ($r=0;$r < count($rlt);$r++)
                {
                    $line = (array)$rlt[$r];
                    $st = trim($line['set_se']);
                    if (trim($st) == '')
                    {
                        $sx .= '<option value="'.$line['id_pa'].'">';
                        $sx .= $line['pa_name'];
                        $sx .= ' ('.strzero($line['id_pa'],6).')';
                        $sx .= '</option>'.cr();
                    }
                }
            $sx .= '</select>';
            $sx .= '<input type="submit" value="'.lang('thesa.select_th_external').'" class="btn btn-outline-primary">';
            $sx .= form_close();
            $sx .= '<div class="mt-5"></div>';

            return $sx;
        }        



    function setting($th,$th2)
        {
            $dt = $this->where('set_th',$th)->where('set_se',$th2)->findAll();
            if (count($dt)==0)
            {
                $dt['set_th'] = $th;
                $dt['set_se'] = $th2;
                $dt['set_active'] = 1;
                $this->insert($dt);
            } else {
                $dt['set_active'] = 1;
                $this->set($dt)->where('set_th',$th)->where('set_se',$th2)->update();
            }
            return true;
        }
}
