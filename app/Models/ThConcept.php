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

    function le($id)
        {
            $this->select("id_c, c_concept, rl_value, rl_lang, ct_propriety, ct_concept, c_th");
            $this->join('th_concept_term','id_c = ct_concept');
            $this->join('rdf_literal','ct_term = id_rl');            
            $this->where('id_c',$id);
            $this->orderBy('rl_value');
            $dt = $this->findAll();
            $dt = $dt[0];
            return($dt);
        }

    function boarder($id)
        {
            
        }

    function show($id)
        {
            $sx = 'SHOW';
            $dt = $this->le($id);

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
            $this->select("id_c as id_c, c_concept as c_concept, rl1.rl_value, rl2.rl_value as name2, rl1.rl_lang as rl_lang, rl2.rl_lang as rl_lang2, lt1.ct_propriety, lt1.ct_concept, lt1.ct_concept_2 ");
            $this->join('th_concept_term as lt1','id_c = ct_concept');
            $this->join('rdf_literal as rl1','lt1.ct_term = rl1.id_rl');
            $this->join('th_concept_term as lt2','(lt1.ct_concept = lt2.ct_concept) and (lt2.ct_propriety = 25)');
            $this->join('rdf_literal as rl2','lt2.ct_term = rl2.id_rl');
            $this->where('c_th',$id);
            $this->where('c_ativo',1);
            $this->like('rl1.rl_value',$lt, 'after');
            $this->orderBy('rl1.rl_value');
            $dt = $this->findAll();
            //echo $this->db->getLastQuery();
            $sx = '<ul>';
            foreach($dt as $id=>$line)
                {
                    $link = '<a href="'.(PATH.MODULE.'v/'.$line['ct_concept']).'">';
                    $linka = '</a>';
                    $sx .= '<li>'.
                                $link.$line['rl_value'].'<sup> ('.$line['rl_lang'].')</sup>';
                                if (trim($line['rl_value']) != trim($line['name2']))
                                    {
                                        $sx .= ' <i><b>USE</b></i> '.$line['name2'].'<sup> ('.$line['rl_lang2'].')</sup>';
                                    }                                
                                $sx .= $linka.'</li>';
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
                    $sx .= '<li class="page-item"><a class="page-link" href="'.(PATH.MODULE.'th/'.$id.'/'.$line['ltr']).'">'.$line['ltr'].'</a></li>'.cr();
                }
            $sx .= '</ul>';
            $sx .= '</nav>';
            return $sx;
        }
}
