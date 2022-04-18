<?php

namespace App\Models\Thesaurus;

use CodeIgniter\Model;

class ThRelationsCustom extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'th_proprieties';
    protected $primaryKey       = 'id_p';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_p','p_th','p_propriey','p_description','p_prefix','p_global','p_order','p_group'
    ];

    var $typeFields    = [
        'hidden','hidden','string:100*','string:100','sql:id_prefix:prefix_name:th_proprieties_prefix*','sn','[1-99]','sql:gr_code:gr_name:th_proprieties_group:gr_custom=1*'
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

    /*
    Process/ agenthunting /hunters
    Process/ counteragent
    fire/ flame retardants
    Action/ property
    polling/ public opinion
    Action/ product
    cloth/ weaving
    Action/ target
    crops/ harvesting
    Cause/ effect
    pathogens /infections
    Concept or object/ property
    liquids/ surface tension
    Concept or object/ origin
    Caspian Sea/ beluga caviar
    Concept or object/ unit of measurement
    temperature/ thermometers
    Raw material/ product
    wheat/ flour
    Discipline or field/ object or practitioner
    linguistics/ language
    Antony 
    msheight/ depth
    */


    function edit($d1,$d2,$d3)
        {
            $sx = '';
            if ($d2==0)
                {
                    $typeFields[1] = 'set:'.$d1;
                }
            $this->id = $d2;
            $this->path = PATH.MODULE.'popup/relation_custom/'.$d1.'/'.$d2;
            $this->path_back = 'wclose';
            $sx = form($this);
            return $sx;
        }

    function btn_relations_custom($th,$id=0)
        {
            $sx = '';
            $sx .= onclick(PATH.MODULE.'popup/relation_custom/'.$th.'/'.$id,800,800,'btn btn-outline-primary');
            $sx .= lang('thesa.edit_relations_custom');
            $sx .= '</span>';
            return($sx);
        }

    function list_relations($th)
        {
            $sx = '';
            $dt = $this
                ->join('th_proprieties_prefix','p_prefix = id_prefix')
                ->join('th_proprieties_group','p_group = gr_code','left')
                ->join('th_proprieties_th','tpt_prop = id_p and tpt_th = '.$th,'left')
                ->where('p_th',$th)
                ->OrWhere('p_global',1)
                ->orderBy('gr_order','ASC')
                ->findAll();

            $sx = '<table class="table table-sm table-striped">';
            $xgr = '';
            for ($r=0;$r < count($dt);$r++)
                {
                    $line = $dt[$r];
                    $gr = $line['p_group'];
                    /*********************************** GROUP */
                    if ($gr != $xgr)
                        {
                            $xgr = $gr;
                            $label = trim($line['gr_name']);
                            $sx .= '<tr><td colspan="2" class="h4">'.lang($label).' - '.$gr.'</td></tr>';
                        }

                    /*************************************** */
                    $disabled  = 'disabled';
                    if ($gr == 'TR') { $disabled = 'none'; }

                    $active = round('0'.$line['tpt_active']);
                    $checked = 'checked';
                    if ($active == 0) { $active = ''; $checked = 'none'; }
                    
                    $checkbox = form_checkbox(array('name'=>'p_'.$line['id_p'],
                            'value'=>'1',
                            $checked => $active,
                            $disabled=>$disabled));
                    

                    $sx .= '<tr>';
                    $sx .= '<td width="1%">'.$checkbox.'</td>';
                    $sx .= '<td>'.$line['prefix_name'].':'.$line['p_propriey'].'</td>';
                    $sx .= '</tr>';
                }
            $sx .= '</table>';
            
            $sx .= bsc($this->btn_relations_custom($th),12);
            return $sx;
        }    
}
