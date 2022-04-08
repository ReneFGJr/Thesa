<?php

namespace App\Models\Thesaurus;

use CodeIgniter\Model;

class ThConfig extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thconfigs';
    protected $primaryKey       = 'id';
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

    function config_menu($id, $ac = '')
    {
        $sx = '';

        $sx .= '<ul class="nav flex-column">';
        $it = array('description', 'language', 'colaboration', 'relations','relations_custom');
        if ($ac == '') {
            $ac = $it[0];
        }

        for ($r = 0; $r < count($it); $r++) {
            if ($it[$r] == $ac) {
                $cl = 'disabled';
            } else {
                $cl = '';
            }
            $sx .= '<li class="mb-2 h5 nav-item text-end"><a href="' . PATH . MODULE . 'th_config/' . $id . '/' . $it[$r] . '" class="nav-link ' . $cl . '">' . lang('thesa.' . $it[$r]) . '</a></li>';
        }
        $sx .= '</ul>';
        return $sx;
    }

    function config_itens($id, $ac = '')
    {
        if ($ac == '') {
            $ac = 'description';
        }
        switch ($ac) {
            default:
                $sx = 'ERROR';
                break;
            case 'relations':
                $sx = $this->relations($id, $ac);
                break;
            case 'colaboration':
                $sx = $this->colaboration($id,$ac);
                break;
            case 'language':
                $sx = $this->language($id,$ac);
                break;
            case 'description':
                $sx = $this->description($id,$ac);
                break;
            case 'relations_custom':
                $sx = $this->relations_custom($id,$ac);
                break;                
        }
        return ($sx);
    }
    function relations_custom($id,$ac)
        {
            $Th = new \App\Models\Thesaurus\ThRelations();
            $sx = $Th->index($id,$ac);
            return $sx;    
        }
    function description($id)
    {
        $Th = new \App\Models\Thesaurus\ThConfigDescription();
        $sx = $Th->edit($id);
        return $sx;
    }

    function language($id)
    {
        $Th = new \App\Models\Thesaurus\ThConfiglanguage();
        $sx = $Th->edit($id);
        return $sx;
    }
    function colaboration($id)
    {
        $Th = new \App\Models\Thesaurus\ThConfigColaboration();
        $sx = $Th->edit($id);
        return $sx;
    }
    function relations($id)
    {
        $Th = new \App\Models\Thesaurus\ThConfigRelations();
        $sx = $Th->edit($id);
        return $sx;
    }
}
