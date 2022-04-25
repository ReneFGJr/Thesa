<?php

namespace App\Models\Thesaurus;

use CodeIgniter\Model;

class ThAssociate extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'th_concept_relation';
    protected $primaryKey       = 'id_tg';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_tg','tg_concept_1','tg_concept_2','tg_propriety','tg_th','tg_active'
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

    function relations_concepts_all($th)
        {
            $c_all = array();
            $te = array();
            $dt =
                $this
                ->join('th_proprieties','id_p = tg_propriety','left')
                ->where('tg_th',$th)
                ->where('tg_active',1)
                ->where("(p_group = 'TE' or p_group = 'TG')")
                ->findAll();

            for ($r=0;$r < count($dt);$r++)
                {
                    $line = $dt[$r];
                    $c1 = $line['tg_concept_1'];
                    $c2 = $line['tg_concept_2'];
                    if ($line['p_group'] == 'TG')
                        {
                            $c1 = $line['tg_concept_2'];
                            $c2 = $line['tg_concept_1'];
                        }
                    $c_all[$c1][$c2] = 1;
                    /************************************* Primary */
                    if (!isset($te[$c1]))
                        {
                            $te[$c1] = 'P';
                        }
                    $te[$c2] = 'S';
                }
            $tree = array();
            foreach($te as $id=>$tp)
                {
                    if ($tp == 'P')
                        {
                            $tree[$id] = array();
                        }
                }

            foreach($tree as $id=>$c)
                {
                    $tree[$id] = $this->concepts_all_map($id,$c_all);
                }                
            return $tree;
        }

    function concepts_all_map($c,$dt)
        {
            if(isset($dt[$c]))
                {
                    foreach($dt[$c] as $id=>$tp)
                        {
                            $dt[$c][$id] = $this->concepts_all_map($id,$dt);
                        }
                    return $dt[$c];
                }
        }

    function concepts_associates($th,$d1)
        {
            $d2 = $this->select('tg_concept_1 as id')->where('tg_concept_2',$d1)->where('tg_active',1)->findAll();
            $d1 = $this->select('tg_concept_2 as id')->where('tg_concept_1',$d1)->where('tg_active',1)->findAll();
            $dd = array_merge($d1,$d2);
            $dt = array();
            for ($r=0; $r < count($dd); $r++) { 
                $di = $dd[$r]['id'];
                $dt[$di] = 1;
            }
            return $dt;
        }    

    function propriety_update($id_tg,$vlr)
        {
            $dt['tg_active'] = $vlr;
            $this->set($dt)->where('id_tg',$id_tg)->update();
            //$this->where('id_tg', $id_tg)->delete();
            return true;
        }

    function le($id)
        {
            $s = '';
            $dt1 = $this
                ->join('th_proprieties','tg_propriety = id_p','inner')
                ->join('th_proprieties_prefix','p_prefix = id_prefix','inner')
                ->join('th_concept_term','ct_concept = tg_concept_2','inner')
                ->join('th_literal','ct_term = id_n','inner')
                ->where('tg_concept_1',$id)
                ->findAll();
            $dt2 = $this
                ->join('th_proprieties','tg_propriety = id_p','inner')
                ->join('th_proprieties_prefix','p_prefix = id_prefix','inner')
                ->join('th_concept_term','ct_concept = tg_concept_1','inner')
                ->join('th_literal','ct_term = id_n','inner')
                ->where('tg_concept_2',$id)
                ->findAll();
            $s = "id_c, c_concept, n_name, n_lang, ct_propriety, ct_concept, c_th";

            $dt = array_merge($dt1,$dt2);
            return($dt);
        }    

    function link($th,$c1,$c2,$prop)
        {
            $dt['tg_concept_1'] = $c1;
            $dt['tg_concept_2'] = $c2;
            $dt['tg_propriety'] = $prop;
            $dt['tg_th'] = $th;
            $this->save($dt);
            return true;
        }    

    function associate($d1,$d2,$d3,$d4,$tp='')
        {
            $Proprities = new \App\Models\RDF\Proprieties();
            $ThConcept = new \App\Models\Thesaurus\ThConcept();
            $dt = $ThConcept->le($d1);
            $th = $dt['c_th'];
            $Class = $Proprities->getPropriety('Concept');

            /************************************************************************************* */
            $associate = get("associate");
            $prop = get("proprity");
            if (($associate != "") and ($prop != "")) {
                $this->link($th,$d1,$associate,$prop);
                $sx = wclose();
                return $sx;
            }
            

            $terms = $ThConcept->concepts($th);
            $terms_associates = $ThConcept->concepts_associates($th,$d1);

            $sx = '';
            $sx .= h('thesa.associated_concepts');
            $sx .= form_open();
            $sx .= '<select name="associate" size="10" class="form-control">';

            for($r=0;$r < count($terms);$r++)
                {
                    $line = $terms[$r];
                    if (($line['id_c'] != $d1) and ($line['ct_propriety'] == $Class))
                    {
                        if (!isset($terms_associates[$line['id_c']]))
                        {
                            $sx .= '<option value="'.$line['id_c'].'" class="h5">'.$line['n_name'].'</option>';
                        }                        
                    }
                }
            $sx .= '</select>';

            /*************************************************************************************** PROP */
            $lk = $this->proprieties($tp);
            $sx .= '<hr>';
            $sx .= '<select name="proprity" size="1" class="form-control">';
            if (count($lk) > 1)
            {
                $sx .= '<option value="">'.msg('thesa.select').'</option>';
            }
            for ($r=0;$r < count($lk);$r++)
                {
                    $line = (array)$lk[$r];
                    $sel = '';
                    $sx .= '<option value="'.$line['id_p'].'" class="h5">'.$line['prefix_name'].':'.$line['p_propriey'].'</option>';
                }
            $sx .= '</select>';
            $sx .= '<br>';
            
            $sx .= form_submit(['value'=>'Associate','class'=>'btn btn-primary']);
            $sx .= form_close();
            $sx = bs(bsc($sx,12));
            return $sx;            
        }    

    function proprieties($tp)
        {
            $sql = "select * 
                    from th_proprieties 
                    inner join th_proprieties_prefix on p_prefix = id_prefix
                    where p_group = '$tp'";
            $db = $this->db->query($sql)->getResult();
            return $db;
        }
}
