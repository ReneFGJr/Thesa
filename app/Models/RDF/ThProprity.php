<?php

namespace App\Models\RDF;

use CodeIgniter\Model;

class ThProprity extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_property';
    protected $primaryKey       = 'id_p';
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

    var $quali = 0;

    function getClass($class)
        {
            return $this->class($class);
        }

    function class($term)
        {
            if (strpos($term,':'))
                {
                    $t = explode(':',$term);
                } else {
                    $t = ['',$term];
                }
            $dt = $this->where('p_name',$t[1])->first();
            if ($dt != '')
                {
                    return($dt['id_p']);
                } else {
                    echo "OPS CLASS/PROPRIETIES NOT FOUND";
                    exit;
                }
        }

    function index($d1, $d2, $d3, $d4)
    {
        $th = 1;
        $sx = '';
        switch ($d1) {
            case 'add':
                $sx .= $this->edit(0,$th);
                break;

            default:
                $menu['#TERM'] = 'Terms';
                $menu[PATH . '/admin/proprity/add'] = msg('add_proprity');

                $sx .= menu($menu);
                $sx .= $this->list($th);
                break;
        }
        $sx = bs(bsc($sx, 12));
        return $sx;
    }

    function find_prop_id($name)
    {
        $this->quali = 0;
        $dt=$this->find_prop($name);
        return($dt['id_p']);
    }

    function find_prop($name)
        {
            $this->quali = 0;
            $ThProprityCustom = new \App\Models\RDF\ThProprityCustom();
            if ($name == 'reference')
                {
                    $dtp['rg_range'] = 'Reference';
                    return $dtp;
                }
            $dt = $this
                ->join('thesa_property_range', 'p_range = id_rg', 'left')
                ->where('p_name', $name)
                ->findAll();

            if (count($dt) > 0)
                {
                    return $dt[0];
                } else {
                    $dt = $ThProprityCustom->find_class($name);
                    if (count($dt) > 0)
                        {
                            $this->quali = $dt['id_pcst'];
                            $idp = $dt['pcst_class'];
                            return $this->find($idp);
                        } else {
                            echo "ERRO DE PROPRIEDADE - " . $name;
                            exit;
                        }
                }
        }

    function edit($id, $th)
        {
            $ThProprityType = new \App\Models\RDF\ThProprityType();
            $ThProprityCustom = new \App\Models\RDF\ThProprityCustom();
            $data = array();

            if (get("action"))
                {
                    $ThProprityCustom->register($_POST);
                    $sx = metarefresh(PATH.'/admin/proprity/');
                    return $sx;
                } else {

                }

            $data['select_prop'][1] = $ThProprityType->select_prop(1);
            $data['select_prop'][2] = $ThProprityType->select_prop(2);
            $data['select_prop'][3] = $ThProprityType->select_prop(3);
            $data['pcst_th'] = $th;
            $data['id_pcst'] = 0;
            $data['pcst_part_1'] = get('pcst_part_1');
            $data['pcst_part_2'] = get('pcst_part_2');
            $data['pcst_part_3'] = get('pcst_part_3');
            $data['pcst_name'] = get("pcst_name");


            $sx = view('Thesa/Forms/Propriety', $data);

            pre($_POST,false);
            return $sx;
        }

    function list($th)
        {
            $sx = '';
            $ThProprityCustom = new \App\Models\RDF\ThProprityCustom();
            $dt = $ThProprityCustom
                    ->join('owl_vocabulary_vc', 'pcst_aplicable = id_vc', 'left')
                    ->findAll();

            $sx .= '<table class="table">';
            for ($r=0;$r < count($dt);$r++)
                {
                    $line = $dt[$r];
                    $sx .= '<tr>';
                    $sx .= '<td>'.$line['pcst_achronic'].'</td>';
                    $sx .= '<td>'.$line['vc_label'].'</td>';
                    $sx .= '</tr>';
                }
            $sx .= '</table>';
            //pre($line,false);
            return $sx;
        }
}
