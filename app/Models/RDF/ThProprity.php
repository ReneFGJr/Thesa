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
        $dt=$this->find_prop($name);
        return($dt['id_p']);
    }
    function find_prop($name)
        {
            $dt = $this
                ->join('thesa_property_range', 'p_range = id_rg', 'left')
                ->where('p_name', $name)
                ->findAll();

            if (count($dt) > 0)
                {
                    return $dt[0];
                } else {
                    echo "ERRO";
                    exit;
                }
        }



    function edit($id, $th)
        {
            $ThProprityType = new \App\Models\RDF\ThProprityType();
            $data = array();

            $data['select_prop'][1] = $ThProprityType->select_prop(1);
            $data['select_prop'][2] = $ThProprityType->select_prop(2);
            $data['select_prop'][3] = $ThProprityType->select_prop(3);

            $sx = view('Thesa/Forms/Propriety', $data);
            return $sx;
        }

    function list($th)
        {
            $dt = $this->where('p_th', $th)->orwhere('p_global',0)->findAll();
        }
}
