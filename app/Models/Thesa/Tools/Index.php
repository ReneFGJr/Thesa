<?php

namespace App\Models\Thesa\Tools;

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
            $sx = '';
            switch($d2)
                {
                    case 'import':
                        $Import = new \App\Models\Thesa\Tools\Import();
                        $sx .= $Import->index($d1);
                    break;

                    default:
                        $sx = bsc($this->menu(),12);
                }
            $sx = bs($sx);
            return $sx;
        }

        function menu()
            {
                $Thesa = new \App\Models\Thesa\Thesa();
                $th = $Thesa->getThesa();
                $menu = array();
                $menu['#Importação'] = '';
                $menu[PATH.'/tools/'.$th.'/import'] = lang('thesa.tools_import');
                return menu($menu);
            }
}
