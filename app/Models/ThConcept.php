<?php

namespace App\Models;

use CodeIgniter\Model;

class ThConcept extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'th_concept';
    protected $primaryKey           = 'id_c';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = [];

    // Dates
    protected $useTimestamps        = false;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks       = true;
    protected $beforeInsert         = [];
    protected $afterInsert          = [];
    protected $beforeUpdate         = [];
    protected $afterUpdate          = [];
    protected $beforeFind           = [];
    protected $afterFind            = [];
    protected $beforeDelete         = [];
    protected $afterDelete          = [];

    function resume($id)
        {
            $dt = $this->where('c_th',$id)->get();
            $rst = $dt->resultID->num_rows;
            return $rst;
        }

    function leid($id)
        {
            $this->select("id_c, c_concept, rl_value, rl_lang, ct_propriety, ct_concept");
            $this->join('th_concept_term','id_c = ct_concept');
            $this->join('rdf_literal','ct_term = id_rl');            
            $this->where('id_c',$id);
            $this->orderBy('rl_value');
            $dt = $this->findAll();
            $dt = $dt[0];
            return($dt);
        }

    function show($id)
        {
            $sx = 'SHOW';
            $dt = $this->leid($id);
            print_r($dt);

            $sx = '';
            $classCss = 'border-bottom border-2';

            /*************************************************** PREFERENCIAL TERM */
            $sx .= bsc(strtoupper(lang('thesa.prefLabel')).':',3,$classCss.' text-right');
            $sx .= bsc('<h2>'.$dt['rl_value'].'</h2>',9,$classCss);


            $sx = '<div class="row">'.$sx.'</div>';
            return $sx;
        }
    function TermLetter($id,$lt)
        {
            $this->select("id_c, c_concept, rl_value, rl_lang, ct_propriety, ct_concept");
            $this->join('th_concept_term','id_c = ct_concept');
            $this->join('rdf_literal','ct_term = id_rl');            
            $this->where('c_th',$id);
            $this->where('c_ativo',1);
            $this->like('rl_value',$lt, 'after');
            $this->orderBy('rl_value');
            $dt = $this->findAll();
            $sx = '<ul>';
            foreach($dt as $id=>$line)
                {
                    $link = '<a href="'.base_url(PATH.'v/'.$line['ct_concept']).'">';
                    $linka = '</a>';
                    $sx .= '<li>'.$link.$line['rl_value'].'-'.$line['ct_propriety'].$linka.'</li>';
                }
            $sx .= '</ul>';
            return $sx;
        }
    function paginations($id)
        {
            $this->select("substr(rl_value,1,1) as ltr");
            $this->join('th_concept_term','id_c = ct_concept');
            $this->join('rdf_literal','ct_term = id_rl');            
            $this->where('c_th',$id);
            $this->where('c_ativo',1);
            $this->groupBy('ltr');
            $this->orderBy('ltr');
            $dt = $this->findAll();

            $sx = '<nav aria-label="Page navigation example">';
            $sx .= '<ul class="pagination">';
            for ($r=0;$r < count($dt);$r++)
                {
                    $line = $dt[$r];
                    $sx .= '<li class="page-item"><a class="page-link" href="'.base_url(PATH.'th/'.$id.'/'.$line['ltr']).'">'.$line['ltr'].'</a></li>'.cr();
                }
            $sx .= '</ul>';
            $sx .= '</nav>';
            return $sx;
        }
}
