<?php

namespace App\Models\AI;

use CodeIgniter\Model;

class Radjson extends Model
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

    function checkTermArray($term_array, $term)
    {
        $existe = false;
        foreach ($term_array as $k => $v) {
            if ($v == $term) {
                $existe = true;
            }
        }
        if (!$existe) {
            $term_array[] = $term;
        }
        return $term_array;
    }

    function rad_json($th = '', $lang = '')
    {
        $Concept = new \App\Models\Thesa\Concepts\Index();
        if ($th == '') {
            return [];
        }
        $Concept
            ->join('thesa_concept_term', 'ct_concept = c_concept')
            ->join('thesa_property', 'ct_propriety = id_p')
            ->join('thesa_terms', 'ct_literal = id_term')
            ->join('language', 'term_lang = id_lg')
            ->join('thesa_notes', 'nt_concept = c_concept', 'left');
        if ($lang != '') {
            $Concept->where('lg_code', $lang);
        }
        $dt = $Concept
            ->where('ct_th', $th)
            ->findAll();

        $data = [];
        foreach ($dt as $k => $v) {
            $id = $v['c_concept'];

            $data[$id]['concept'] = $v['c_concept'];
            switch ($v['p_name']) {
                case 'prefLabel':
                    $data[$id]['prefLabel'] = $v['term_name'];
                    break;
                case 'altLabel':
                    $data[$id]['altLabel'] = $this->checkTermArray($data[$id]['altLabel'] ?? [], $v['term_name']);
                    break;

                case 'hiddenLabel':
                    $data[$id]['hiddenLabel'] = $this->checkTermArray($data[$id]['hiddenLabel'] ?? [], $v['term_name']);
                    break;
            }
            if ($v['nt_content'] != '') {
                $exist_note = false;
                if ($v['nt_lang'] > 0) {
                    $note = $v['nt_content'];
                    if (isset($data[$id]['notes']) == false) {
                        $data[$id]['notes'] = [];
                    }
                    foreach ($data[$id]['notes'] as $k_note => $v_note) {
                        if ($v_note == $note) {
                            $exist_note = true;
                        }
                    }
                    if (!$exist_note) {
                        $data[$id]['notes'][] = $note;
                    }
                }
            }
        }
        $rsp = [];
        foreach ($data as $k => $v) {
            if (!isset($v['prefLabel']))
            {
                continue;
            }
            if (!isset($v['notes']))
            {
                $v['notes'] = [];
            }
            $rsp[] = ['term'=> $v['prefLabel'], 'definition' => $v['notes']];
        }
        echo json_encode($rsp, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
        return $rsp;
    }
}
