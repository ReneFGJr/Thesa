<?php

namespace App\Models\Thesaurus;

use CodeIgniter\Model;

class ThLiteralTh extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'th_literal_th';
    protected $primaryKey       = 'id_lt';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_lt','lt_term','lt_th','lt_status'
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

    function search($t,$th=1)
        {
            $dt = 
            $this
                ->select('ct_concept,n_name,n_lang')
                ->join('th_literal','id_n = lt_term')
                ->join('th_concept_term','ct_term = id_lt')
                ->where('ct_th',$th)
                ->like('n_name',''.$t.'')
                ->findAll();
            return $dt;
        }

    function term_list($th)
        {
            $dt = 
            $this
                 ->select('id_n, n_name, n_lang, id_lt, lt_term, lt_status, ct_concept')
                 ->join('th_literal','id_n = lt_term')
                 ->join('th_concept_term','(ct_term = id_n) and (ct_th = lt_th)','left')
                 ->where('lt_th',$th)
                 ->where('lt_status',1)
                 ->where('ct_concept IS NULL', null, false)
                 ->orderBy('n_name','ASC')
                 ->findAll();
            return $dt;
            
        }

    function term_insert($term,$th)
        {
            $this->where('lt_th',$th);
            $this->where('lt_term',$term);
            $dt = $this->findAll();
            if (count($dt) == 0)
            {
                $dt['lt_term'] = $term;
                $dt['lt_th'] = $th;
                $dt['lt_status'] = 1;
                $this->insert($dt);
                return 1;
            } else {
                $dt['lt_status'] = 1;
                $dt = $dt[0];
                $this->set($dt)->where('id_lt',$dt['id_lt'])->update();
                return 0;
            }
        }

    function total($id)
        {
            $this->select("count(*) as total");
            $this->where('lt_th',$id);
            $dt = $this->FindAll();
            $total = $dt[0]['total'];
            return $total;
        }
}
