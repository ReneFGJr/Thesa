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
                        $sx .= bs(bsc($this->btn_new_thesa(),12, 'mt-5'));
                        break;
                }
            $sx .= view('Theme/Standard/footer');
            return $sx;
        }

    function t($id)
        {
            $sx = '';
            $ThConceptPropriety = new \App\Models\RDF\ThConceptPropriety();
            $dt = $ThConceptPropriety
                ->select('p_group, term_name, vc1.vc_label as vc_label, vc2.vc_label as vc_resource, lg_code, lg_language')
                ->join('thesa_terms', 'ct_literal = id_term', 'left')
                ->join('owl_vocabulary_vc as vc1', 'ct_propriety = vc1.id_vc', 'left')
                ->join('owl_vocabulary_vc as vc2', 'ct_resource = vc2.id_vc', 'left')
                ->join('language','term_lang = id_lg','left')
                ->join('thesa_property', 'vc1.vc_label = p_name','left')
                ->where('ct_concept', $id)
                ->orderBy('p_group, term_name')
                ->findAll();

                //echo $this->getlastquery();
                $prefLabel = '';

                $sx .= '<table class="table_theme">';
                $xgr = '';
                for ($r=0;$r < count($dt);$r++)
                    {
                        $line = $dt[$r];
                        $gr = $line['p_group'];

                        if ($line['vc_label'] == 'prefLabel')
                            {
                                $prefLabel = $line['term_name'];
                                $prefLabel .= ' <sup>('.$line['lg_code'].')</sup>';
                            }

                        if (strlen(trim($line['term_name'])) > 0)
                        {
                            $sx .= '<tr>';
                            $classF = '';
                            if ($gr != $xgr)
                                {
                                    $sx .= '<td width="20%" class="small trh">' .
                                        lang('thesa.'.$line['vc_label']) . '</td>';
                                        $xgr = $gr;
                                    $classF = 'tdh';
                                } else {
                                    $sx .= '<td></td>';
                                }

                            $sx .= '<td class="ps-3 '.$classF.'">'. $line['term_name'];
                            $sx .= ' <sup>(' . $line['lg_code'] . ')</sup>';
                            $sx .= '</td>';
                            $sx .= '</tr>';
                        } else {
                            $sx .= '<tr>';
                            $sx .= '<td width="20%" class="small trh">' . lang('thesa.'.$line['vc_label']) . '</td>';
                            $sx .= '<td class="ps-3 tdh">' . $line['vc_resource'] . '</td>';
                            $sx .= '</tr>';
                        }
                    }
                $sx .= '</table>';

                /******************************* PrefLabel */
                $edit = '<a href="'.base_url(PATH.'a/'.$id).'" class="ms-2">'.bsicone('edit').'</a>';
                $st = '<h1>'.$prefLabel.'</h1>';
                $st .= '<h6>URI: '.anchor(PATH.'v/'.$id).$edit.'</h6>';
            return $st.$sx;
        }

    function terms($id)
        {
            $sx = '';
            $ThConceptPropriety = new \App\Models\RDF\ThConceptPropriety();

            $dt = $ThConceptPropriety
                ->join('thesa_terms', 'ct_literal = id_term', 'left')
                ->join('owl_vocabulary_vc', 'id_vc = ct_propriety', 'left')
                ->where('ct_th', $id)
                ->where('ct_literal > 0')
                ->orderBy('term_name', 'ASC')
                ->findAll();


                //$sx .= '<ul class="list-unstyled">';
                //pre($dt);
                $sx .= '<select id="thesa_terms" size=25 style="width: 100%; border: 0px solid #000; outline: none;">';
                $xlt = '';
                for($r=0;$r < count($dt);$r++)
                    {
                        $line = $dt[$r];
                        $lt = mb_strtoupper(substr(ascii($line['term_name']),0,1));
                        if ($lt != $xlt)
                            {
                                $sx .= '<option value="" style="background-color: #f8f8f8; border-top: 1px solid #000; border-bottom: 1px solid #000; text-align: center; font-weight: bold;">= = '.$lt.' = =</option>';
                                $xlt = $lt;
                            }
                        $link = '<a href="#" onclick="view('.$line['ct_concept'].');">';
                        $linka = '</a>';
                        //$sx .= '<li>'.$link.$line['term_name'].$linka.'</li>';
                        $sx .= '<option value="'.$line['ct_concept'].'">'.$line['term_name'].'</option>';
                    }
                //$sx .= '</ul>';
                $sx .= '</select>';
                $sx .= '<script>';
                $sx .= '
                        $("#thesa_terms").change(function() {
                            var value = $(this).val();
                             $("#desc").load("' . PATH . '/t/"+value);
                        });
                ';
                $sx .= 'function view(id)
                            {

                            }';
                $sx .= '</script>';
            return($sx);

        }

    function store()
    {
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
                if ($id_th == 0) {
                    $this->save($data);
                } else {
                    $this->set($data)->where('id_th', $id_th)->update();
                }

                header("location: admin");
                exit;
            } else {

                $data['ERROS'] = $validation->getErrors();
                $data['validation'] = $this->validator;

                return view('Thesa/Forms/ThesaNew', $data);
            }
        }
    }

    function btn_new_thesa()
        {
            $sx = '<a href="'.base_url(PATH.'admin/thesaurus/new').'" class="btn btn-outline-primary">';
            $sx .= lang('thesa.new_thesa');
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
            $header = 'Theme/Standard/headerTh';
            $sx = view($header,$dt);
            return $sx;
        }

    function new_thesa()
        {
            $sx = '';
            $sx .= $this->store();

            return $sx;
        }

    function getThesa()
        {
            return $this->setThesa();
        }

    function setThesa($id='')
        {
            if ($id != '')
                {
                    $_SESSION['th'] = $id;
                    return $id;
                } else {
                    if (isset($_SESSION['th']))
                        {
                            $id = $_SESSION['th'];
                            return $id;
                        } else {
                            echo "OPS";
                            exit;
                        }
                }
            return $id;
        }

    function list()
        {
            $sx = '<h1>'.lang('thesa.Thesaurus'). '</h1>';
            $ThIcone = new \App\Models\Thesa\ThIcone();
            $dt = $this
                ->orderBy('th_name', 'ASC')
                ->findAll();

            for($r=0;$r < count($dt);$r++)
                {
                    $line = $dt[$r];
                    $line['img'] = $ThIcone->icone($dt);
                    $sx .= bsc(view('Theme/Standard/ViewThList',$line),2,'p-2');
                }
            $sx .= '';

            $sx = bs($sx);
            return $sx;
        }

}
