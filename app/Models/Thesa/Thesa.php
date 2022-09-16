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
        'th_description', 'th_status','',
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

    function index($d1,$d2,$d3)
        {
            switch($d1)
                {
                    case 'new':
                        $sx = $this->new_thesa();
                        break;
                    default:
                        $sx = $this->list();

                        $sx .= bs(bsc($this->btn_new_thesa()));
                        break;
                }
            return $sx;
        }

    function store()
        {

        }

    function btn_new_thesa()
        {
            $sx = '<a href="'.base_url(PATH.'admin/thesaurus/new').'" class="btn btn-primary">';
            $sx .= msg('new_thesa');
            $sx .= '</a>';
            return $sx;
        }

    function le($id)
        {
            $ThIcone = new \App\Models\Thesa\ThIcone();
            $dt = $this->find($id);
            $dt['icone'] = $ThIcone->icone($dt);
            return $dt;
        }

    function header($dt)
        {
            $header = 'Theme\Standard\headerTh';
            $sx = view($header,$dt);
            return $sx;
        }

    function new_thesa()
        {
            $data = array();

            $sx = '';
            $sx .= '<h1>'.msg('new_thesaurus').'</h1>';
            $sx .= view('Thesa/Forms/ThesaNew',$data);
            return $sx;
        }

    function list()
        {
            $sx = tableview($this);
            $sx = bs(bsc($sx,12));
            return $sx;
        }
}
