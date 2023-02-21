<?php

namespace App\Models\RDF;

use CodeIgniter\Model;

class ThTermTh extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_terms_th';
    protected $primaryKey       = 'term_th_id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'term_th_id', 'term_th_thesa', 'term_th_term',
        'term_th_concept'
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

    function total($th)
    {
        $dt = $this
            ->select('count(*) as total')
            ->where('term_th_thesa', $th)
            ->findAll();
        return $dt[0]['total'];
    }
    function totalNoUse($th)
    {
        $dt = $this
            ->select('count(*) as total')
            ->where('term_th_thesa', $th)
            ->where('term_th_concept', 0)
            ->findAll();
        return $dt[0]['total'];
    }

    function le($id)
    {
        $dt = $this
            ->join('thesa_terms', 'term_th_term = id_term')
            ->join('language', 'term_lang = id_lg')
            ->where('id_term', $id)
            ->where('term_th_concept', 0)
            ->orderBy('term_name', 'ASC')
            ->findAll();
        return $dt;
    }

    function termNoUse($th, $langs = array())
    {
        $this
            ->join('thesa_terms', 'term_th_term = id_term')
            ->join('language', 'term_lang = id_lg')
            ->where('term_th_thesa', $th)
            ->where('term_th_concept', 0);
        foreach ($langs as $lang => $txt) {
            $this->where('lg_code <> \'' . $lang . '\'');
        }

        $dt = $this
            ->orderBy('term_name, lg_code', 'ASC')
            ->findAll();

        return $dt;
    }



    /************************* Atualiza Themo do Conceito */
    function update_term_th($id, $th, $concept)
    {
        $dt['term_th_concept'] = $concept;
        $dt = $this
            ->set($dt)
            ->where('term_th_term', $id)
            ->where('term_th_thesa', $th)
            ->update();
    }

    function link_th($id, $th)
    {
        $dt = $this
            ->where('term_th_term', $id)
            ->where('term_th_thesa', $th)
            ->findAll();

        if (count($dt) == 0) {
            $data = array();
            $data['term_th_term'] = $id;
            $data['term_th_thesa'] = $th;
            $this->insert($data);
            return true;
        } else {
            return false;
        }
    }
}
