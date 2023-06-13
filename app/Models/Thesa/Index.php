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

    function getThesa($th=0)
    {
        if ($th > 0)
            {
                return $this->setThesa($th);
            } else {
                if (isset($_SESSION['th']))
                    {
                        return $_SESSION['th'];
                    } else {
                        return 0;
                    }

            }

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
            $th = round('0'.$th);
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
            if (isset($rlt[0]))
                {
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
                ->where('term_th_concept',0)
                ->findAll();
            $data['nr_terms_candidates'] = $dt[0]['total'];
        }

        return $data;
    }
}