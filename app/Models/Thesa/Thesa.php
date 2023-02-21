<?php

namespace App\Models\Thesa;

use CodeIgniter\Model;

class Thesa extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa';
    protected $primaryKey       = 'id_th';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_th', 'th_name', 'th_achronic',
        'th_description', 'th_status', '',
        'th_terms', 'th_version', 'th_icone',
        'th_type', 'th_own'
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

    function index($d1, $d2, $d3)
    {
        switch ($d1) {
            case 'new':
                $sx = $this->new_thesa();
                break;
            default:
                $sx = $this->list();
                $sx .= bs(bsc($this->btn_new_thesa(), 12, 'mt-5'));
                break;
        }
        return $sx;
    }

    function btn_new_thesa()
    {
        $sx = '<a href="' . (PATH . 'admin/thesaurus/new') . '" class="btn btn-outline-primary">';
        $sx .= lang('thesa.new_thesa');
        $sx .= '</a>';
        return $sx;
    }

    function le($id)
    {
        $ThIcone = new \App\Models\Thesa\Icone();
        $dt = $this->find($id);
        $dt['icone'] = $ThIcone->icone($dt);
        return $dt;
    }

    function setThesa($th='')
        {
            $Thesa = new \App\Models\Thesa\Index();
            return $Thesa->setThesa($th);
        }

    function list($user = 0)
    {
        $sx = '';
        $ThIcone = new \App\Models\Thesa\Icone();

        if ($user > 0) {
            $dt = $this
                ->join('thesa_users', 'th_us_th = id_th')
                ->join('thesa_users_perfil', 'th_us_perfil = id_pf')
                ->where('th_us_user', $user)
                ->findAll();
        } else {
            $dt = $this
                ->where('th_status', 1)
                ->orderBy('th_name', 'ASC')
                ->findAll();
        }

        for ($r = 0; $r < count($dt); $r++) {
            $line = $dt[$r];
            $line['img'] = $ThIcone->icone($dt);
            $sx .= bsc(view('Theme/Standard/ViewThList', $line), 2, 'p-2');
        }
        $sx .= '';

        $sx = bs($sx);
        return $sx;
    }

}
