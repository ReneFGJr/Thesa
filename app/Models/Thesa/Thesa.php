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
        'th_type', 'th_own', 'th_icone_custom',
        'th_cover'
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

    function new_thesa()
    {
        $Collaborators = new \App\Models\Thesa\Collaborators();
        $Language = new \App\Models\Thesa\Language();

        $request = \Config\Services::request();
        $validation =  \Config\Services::validation();
        $data = array();

        if (isset($_POST)) {

            $data = $request->getPost();

            /********************************* RULES */
            $rules = [
                'th_name' => ['label' => 'Name', 'rules' => 'required|min_length[3]'],
                'th_achronic' => ['label' => 'Silga', 'rules' => 'required|min_length[2]'],
                'th_status' => ['label' => 'Silga', 'rules' => 'required|min_length[1]'],
                'th_type' => ['label' => 'Silga', 'rules' => 'required|min_length[1]']
            ];
            //$validation->setRule('sc_name', 'Username', 'required|min_length[3]');
            $validation->setRules($rules);

            if ($validation->withRequest($request)->run()) {
                $data = [
                    'th_type' => $request->getVar('th_type'),
                    'th_name'  => $request->getVar('th_name'),
                    'th_status'  => $request->getVar('th_status'),
                    'th_achronic'  => $request->getVar('th_achronic'),
                    'th_description'  => $request->getVar('th_description')
                ];

                $id_th = $request->getVar('id_th');
                $Social = new \App\Models\Socials();
                $user = $Social->getUser();

                if ($id_th == 0) {
                    /************************************ CHECK */
                    $dt = $this->where('th_name', $request->getVar('th_name'))->findAll();
                    if (count($dt) > 0) {
                        $data['error'] = msg('Thesaurus already exists', 'danger');
                    } else {
                        $this->save($data);
                        $id_th = $this->getInsertID();
                        $perfil = 1; // owner
                        $Collaborators->add($user, $id_th, $perfil);
                        $data['error'] = msg('Thesaurus created', 'success');
                    }
                } else {
                    $Socials = new \App\Models\Socials();
                    $user = $Social->getUser();
                    $this->set($data)->where('id_th', $id_th)->update();
                    $Collaborators->add($id_th, $user);
                }

                /* Define linguagem padrão */
                /* POR */
                $Language->register($id_th,3);
                header("location: admin");
                exit;
            } else {
                $data['ERROS'] = $validation->getErrors();
                $data['validation'] = $this->validator;

                return view('Thesa/Forms/ThesaNew', $data);
            }
        }
    }

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
        $sx = '';
        $Socials = new \App\Models\Socials();
        $Thesa = new \App\Models\Thesa\Index();
        $user = $Socials->getUser();
        if ($user > 0) {
            $total = $Thesa->user_total_tesauros($user);
            if (($total == 0) or ($Socials->getAccess("#ADM"))) {
                $sx = '<a href="' . (PATH . '/admin/thesaurus/new') . '" class="btn btn-outline-primary">';
                $sx .= lang('thesa.new_thesa');
                $sx .= '</a>';
            }
        }

        return $sx;
    }

    function le($id)
    {
        $ThIcone = new \App\Models\Thesa\Icone();
        $dt = $this->find($id);
        $dt['icone'] = $ThIcone->icone($dt);
        return $dt;
    }

    function setThesa($th = '')
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
            $line['img'] = $ThIcone->icone($dt[$r]);
            $sx .= bsc(view('Theme/Standard/ViewThList', $line), 2, 'p-2');
        }
        $sx .= '';

        $sx = bs($sx);
        return $sx;
    }
}
