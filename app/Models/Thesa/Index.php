<?php

namespace App\Models\Thesa;

use CodeIgniter\Model;

class Index extends Model
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
        'id_th',
        'th_name',
        'th_achronic',
        'th_status',
        'th_cover',
        'th_description',
        'th_type',
        'th_icone',
        'th_icone_custom',
        'th_licence',
        'th_visibility',
        'th_user_create',
        'th_user_update',
        'th_date_create',
        'th_date_update',
        'th_terms',

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

    function getDescription($d1, $d2)
    {
        $Descriptions = new \App\Models\Thesa\Descriptions();
        $dt = $Descriptions
            ->where('ds_prop', $d2)
            ->where('ds_th', $d1)
            ->first();
        return $dt;
    }

    function chageStatus($th = 0, $type = 0)
    {
        $dd = [];
        $dd['th_status'] = $type;
        if ($type != '') {
            $this->set($dd)->where('id_th', $th)->update();
            $RSP['status'] = '200';
            $RSP['message'] = 'Thesa status updated';
            $RSP['data'] = $dd;
        } else {
            $RSP['status'] = '500';
            $RSP['message'] = 'Thesa status not informed';
            $RSP['data'] = $dd;
        }
        return $RSP;
    }

    function chageLicence($th = 0, $type = 0)
    {
        $dd = [];
        $dd['th_licence'] = $type;
        if ($type != '') {
            $this->set($dd)->where('id_th', $th)->update();
            $RSP['status'] = '200';
            $RSP['message'] = 'Thesa type updated';
            $RSP['data'] = $dd;
        } else {
            $RSP['status'] = '500';
            $RSP['message'] = 'Thesa type not informed';
            $RSP['data'] = $dd;
        }
        return $RSP;
    }

    function chageTypes($th = 0, $type = 0)
    {
        $dd = [];
        $dd['th_type'] = $type;
        if ($type != '') {
            $this->set($dd)->where('id_th', $th)->update();
            $RSP['status'] = '200';
            $RSP['message'] = 'Thesa type updated';
            $RSP['data'] = $dd;
        } else {
            $RSP['status'] = '500';
            $RSP['message'] = 'Thesa type not informed';
            $RSP['data'] = $dd;
        }
        return $RSP;
    }

    function thesaTypes()
    {
        $ThesaTypes = new \App\Models\Thesa\Types();
        $dt = $ThesaTypes->findAll();
        $tp = [];
        foreach ($dt as $key => $value) {
            $dd = [];
            $dd['id'] = $value['id_tt'];
            $dd['name'] = $value['tt_name'];
            $dd['description'] = $value['tt_description'];
            array_push($tp, $dd);
        }
        return $tp;
    }

    function thesaLicences()
    {
        $ThesaTypes = new \App\Models\Thesa\Licenses();
        $dt = $ThesaTypes->findAll();
        $tp = [];
        foreach ($dt as $key => $value) {
            $dd = [];
            $dd['id'] = $value['id_lc'];
            $dd['name'] = $value['lc_name'];
            $dd['icon'] = $value['lc_icone'];
            $dd['description'] = $value['lc_description'];
            array_push($tp, $dd);
        }
        return $tp;
    }

    function canCreateNewThesa()
    {
        $Social = new \App\Models\Socials();

        $apikey = get('apikey');
        $apikey = troca($apikey, '"', '');
        $Thesa = new \App\Models\Thesa\Thesa();
        $dt = $Thesa
            ->select('count(*) as total')
            ->join('users', 'th_own = id_us')
            ->where('us_apikey', $apikey)
            ->first();
        $RSP = [];
        $RSP['total'] = $dt['total'];
        /************* Multiple *************/
        $du = $Social->where('us_apikey', $apikey)->first();
        $RSP['multiples'] = $du['us_verificado'];
        return $RSP;
    }

    function saveDescription($dt = array())
    {
        $Descriptions = new \App\Models\Thesa\Descriptions();
        $dt = array_merge($dt, $_GET);

        if (isset($dt['title']) and (isset($dt['acronic'])) and (isset($dt['thesaurus']))) {
            $Thesa = new \App\Models\Thesa\Thesa();
            $dd = [];
            $dd['th_name'] = $dt['title'];
            $dd['th_achronic'] = $dt['acronic'];
            $Thesa->set($dd)->where('id_th', $dt['thesaurus'])->update();

            $dd = [];
            $dd['status'] = '500';
            $dd['message'] = 'Title and Acronic saved';
            $dd['data'] = $dt;
            return $dd;
        }

        if ((!isset($dt['field'])) or (!isset($dt['thesaurus']))) {
            $dd = [];
            $dd['status'] = '500';
            $dd['message'] = 'Field, Thesaurus ou Description not informed';
            $dd['data'] = $dt;
            return $dd;
        }

        $dd = [];
        $dd['ds_prop'] = $dt['field'];
        $dd['ds_descrition'] = $dt['description'];
        $dd['ds_th'] = $dt['thesaurus'];


        $dr = $Descriptions
            ->where('ds_prop', $dd['ds_prop'])
            ->where('ds_th', $dd['ds_th'])
            ->first();

        if ($dr == null) {
            $Descriptions->insert($dd);
            $dd['status'] = '200';
            $dd['message'] = 'Description created';
        } else {
            $Descriptions->update($dr['id_ds'], $dd);
            $dd['status'] = '200';
            $dd['message'] = 'Description updated';
        }
        return $dd;
    }

    function thopen($user = 0)
    {
        $cp = 'id_th,th_name,th_achronic,th_cover,th_icone_custom, th_status';
        $Icone = new \App\Models\Thesa\Icone();
        if ($user > 0) {
            $dt = $this
                ->select($cp)
                ->join('thesa_users', 'th_us_th = id_th', 'left')
                ->join('thesa_users_perfil', 'th_us_perfil = id_pf', 'left')
                ->where('th_us_user', $user)
                ->OrWhere('th_own', $user)
                ->groupBy($cp)
                ->orderBy('th_name', 'ASC')
                ->findAll();
        } else {
            $dt = $this
                ->select($cp)
                ->where('th_status', 1)
                ->groupBy($cp)
                ->orderBy('th_name', 'ASC')
                ->findAll();
        }

        foreach ($dt as $id => $da) {
            $dt[$id]['th_cover'] = $Icone->icone($da);
        }
        return $dt;
    }

    function getThesa($th = 0)
    {
        if ($th > 0) {
            return $this->setThesa($th);
        } else {
            if (isset($_SESSION['th'])) {
                return $_SESSION['th'];
            } else {
                return 0;
            }
        }
    }

    function terms($th, $lt = '')
    {
        $Concept = new \App\Models\Thesa\Concepts\Index();
        $cp = 'term_name as Term,c_concept as Concept, p_name as Propriety, lg_code as Lang';
        $cpg = 'Term, Concept, Propriety';
        //$cp = '*';
        $dt = $Concept
            ->select($cp)
            ->join('thesa_concept_term', 'ct_concept = c_concept')
            ->join('thesa_terms', 'ct_literal = id_term')
            ->join('thesa_property', 'ct_propriety = id_p')
            ->join('language', 'term_lang = id_lg')
            ->where('c_th', $th)
            ->groupby($cpg)
            ->orderby('term_name')
            ->findAll();
        return $dt;
    }

    function le($id)
    {
        $ThIcone = new \App\Models\Thesa\Icone();
        $dt = $this->find($id);
        $dt['icone'] = $ThIcone->icone($dt);
        return $dt;
    }

    function show_header($th)
    {
        $dt = $this->le($th);
        return $this->header($dt);
    }

    function header($dt)
    {
        $header = 'Theme/Standard/headerTh';
        $sx = view($header, $dt);
        return $sx;
    }

    function setThesa($th = '')
    {
        if ($th != '') {
            $th = round('0' . $th);
            $_SESSION['th'] = $th;
            return $th;
        } else {
            if (isset($_SESSION['th'])) {
                $th = $_SESSION['th'];
                return $th;
            } else {
                return 0;
                exit;
            }
        }
        return $th;
    }

    function user_total_tesauros($id_us)
    {
        $total = 0;
        $sql = "select count(*) as total, th_us_user
                    from thesa_users
                    where th_us_user = $id_us
                    group by th_us_user";
        $rlt = $this->db->query($sql);
        $rlt = (array)$rlt->getResult();
        if (isset($rlt[0])) {
            $rlt = (array)$rlt[0];
            $total = $rlt['total'];
        }
        return $total;
    }

    function summary($id = '')
    {
        $data = array();
        $Thesa = new \App\Models\Thesa\Thesa();
        $data['nr_thesaurus'] = '';

        if ($id == '') {
            $dt = $Thesa->select('count(*) as total')->findAll();
            $data['nr_thesaurus'] = $dt[0]['total'];

            $ThConcept = new \App\Models\Thesa\Concepts\Index();
            $dt = $ThConcept->select('count(*) as total')->findAll();
            $data['nr_concepts'] = $dt[0]['total'];

            $ThTerm = new \App\Models\RDF\ThTerm();
            $dt = $ThTerm->select('count(*) as total')->findAll();
            $data['nr_terms'] = $dt[0]['total'];
            /* Nao aplicavel */
            $data['nr_terms_candidates'] = 0;
        } else {
            $ThConcept = new \App\Models\Thesa\Concepts\Index();
            $dt = $ThConcept
                ->select('count(*) as total')
                ->where('c_th', $id)
                ->findAll();
            $data['nr_concepts'] = $dt[0]['total'];

            $ThTerm = new \App\Models\RDF\ThTermTh();
            $dt = $ThTerm
                ->select('count(*) as total')
                ->where('term_th_thesa', $id)
                ->findAll();
            $data['nr_terms'] = $dt[0]['total'];

            $dt = $ThTerm
                ->select('count(*) as total')
                ->where('term_th_thesa', $id)
                ->where('term_th_concept', 0)
                ->findAll();
            $data['nr_terms_candidates'] = $dt[0]['total'];
        }
        return $data;
    }
}
