<?php

namespace App\Models\Thesa\Terms;

use CodeIgniter\Model;

class Search extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'searches';
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

    function search()
        {
            $sx = view('Theme/Standard/Search');

            $all = get("all");
            $action = get("action");
            $search = get("search");

            if ($search != '')
                {
                    $sx .= $this->results($search,$all);
                }
            return $sx;
        }

        function results($search,$all)
            {
                $sx = '<hr>';
                $Terms = new \App\Models\Thesa\Terms\Index();
                $cp = 'term_name,ct_concept,id_th,th_name,ct_propriety';
                //$cp = '*';
                $Terms
                    ->select($cp)
                    ->join('thesa_concept_term','ct_literal = id_term')
                    ->join('thesa', 'ct_th = id_th')
                    ->like('term_name',$search);
                if ($all != '')
                    {
                        //$Terms = '';
                    }
                $dt = $Terms
                    ->orderBy('term_name')
                    ->findAll(0,100);
                $sx .= h(lang('thesa.results'),4);
                $sx .= '<ul>';
                foreach($dt as $id=>$line)
                    {
                        $term = $line['term_name'];
                        $link = '<a href="'.PATH."/v/".
                            $line['ct_concept']
                            .'">';
                        $linka = '</a>';
                        $sx .= '<li class="p-1">'.$link.$term.$linka.'</li>';
                    }
                    $sx .= '</ul>';
                    $sx = bs(bsc($sx,12));
                    return $sx;
            }
}
