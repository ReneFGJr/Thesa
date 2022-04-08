<?php

namespace App\Models\Help;

use CodeIgniter\Model;

class Index extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'indices';
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

    function index($d1,$d2)
        {
            $sa = $this->help_menu($d1,$d2);
            $sb = $this->help_itens($d1,$d2);

            $sx = h('thesa.Help');
            $sx = bs($sx.bsc($sa,3).bsc($sb,9));
            return $sx;
        }

    function help_menu($id, $ac = '')
    {
        $sx = '';

        $sx .= '<ul class="nav flex-column">';
        $it = array('about','api');
        if ($ac == '') {
            $ac = $it[0];
        }

        for ($r = 0; $r < count($it); $r++) {
            if ($it[$r] == $ac) {
                $cl = 'disabled';
            } else {
                $cl = '';
            }
            $sx .= '<li class="mb-2 h5 nav-item text-end"><a href="' . PATH . MODULE . 'help/'. $it[$r] . '" class="nav-link ' . $cl . '">' . lang('thesa.' . $it[$r]) . '</a></li>';
        }
        $sx .= '</ul>';
        return $sx;
    }   

    function about()
        {
            $sx = h('thesa.About',4);
            return $sx;
        }

    /***************************************************************************************** MD */     
    function md($act)
        {
            $sx = '';
            $dir = '../_document/help/';
            $file = $dir.$act.'.md';
            if (file_exists($file)) {
                $sx = file_get_contents($file);
                $sx = troca($sx,'$URL',URL);
            } else {
                $sx = bsmessage('File not found - '.$file);
            }
            return $sx;
        }
    
    /***************************************************************************************** ITENS */     
    function help_itens($ac = '')
    {
        if ($ac == '') {
            $ac = 'description';
        }
        switch ($ac) {
            default:
                $sx = $this->about();
                break;
            case 'api':
                $sx = h('thesa.Api',4);
                $sx .= $this->md($ac);
                break;
        }
        return ($sx);
    }        
}
