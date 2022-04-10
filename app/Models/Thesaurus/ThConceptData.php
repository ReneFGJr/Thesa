<?php

namespace App\Models\Thesaurus;

use CodeIgniter\Model;

class ThConceptData extends Model
{
    protected $DBGroup          = 'default';
    protected $table                = 'th_concept_term';
    protected $primaryKey           = 'id_ct';
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

function getData($id)
        {
            $sx = '';    
            $this
                ->select('id_c,c_concept,rdf_literal.rl_value,rdf_literal.rl_lang,
                            ct_propriety,c_th,ct_concept,rs_propriety,rs_prefix,')
                ->join('rdf_literal','ct_term = id_rl','left')
                ->join('rdf_resource','ct_propriety = id_rs','left')
                ->join('th_concept_term as r2','rdf_concept.ct_concept_2 = r2.id_ct','left')
                ->join('rdf_literal as l2','r2.ct_term = l2.id_rl','left','left')
                ->where('ct_concept',$id)
                ->orderBy('rs_propriety, rl_value');

            $dt =  $this->findAll();
            pre($dt);
            return $dt;
        } 

function TG($id)
        {
        /************ TGS */
        $sql = "select * from rdf_resource where rs_group = 'TG'";
        $dt = (array)$this->query($sql)->getResultArray();
        $wh = '';
        for ($r=0;$r < count($dt);$r++)
        {
            $line = $dt[$r];
            if (strlen($wh) > 0) { $wh .= ' or '; }
            $wh .= '(t1.ct_propriety = '.$line['id_rs'].')';
        }        
        
        $cp = 't1.id_ct as id_ct, t2.id_ct as id_ct2, 
                    rl_value, rl_lang, 
                    rs_propriety,
                    t1.ct_concept as ct_concept, 
                    t1.ct_created as ct_created,
                    pa_name, pa_type,
                    concat(rs_propriety,pa_type) as propriety ';

        $sql = "select $cp
        FROM th_concept_term as t1
        INNER JOIN th_concept_term as t2 ON t1.ct_concept = t2.ct_concept and t2.ct_propriety = 25 
        INNER JOIN rdf_literal ON id_rl = t2.ct_term
        INNER JOIN rdf_resource ON t1.ct_propriety = id_rs
        INNER JOIN th_thesaurus ON t1.ct_th = id_pa
        WHERE t1.ct_concept_2 = $id and ($wh) ";

        $rlt = $this->query($sql)->getResultArray();
        if (count($rlt) > 0) {
            return ($rlt);
        } else {
            return ( array());
        }
    }

function TE($id)
        {
        /************ TGS */
        $sql = "select * from rdf_resource where rs_group = 'TG'";
        $dt = (array)$this->query($sql)->getResultArray();
        $wh = '';
        for ($r=0;$r < count($dt);$r++)
        {
            $line = $dt[$r];
            if (strlen($wh) > 0) { $wh .= ' or '; }
            $wh .= '(t1.ct_propriety = '.$line['id_rs'].')';
        }        
        
        $cp = "t1.id_ct as id_ct, t2.id_ct as id_ct2, 
                    rl_value, rl_lang, 
                    rs_propriety_inverse as rs_propriety, 
                    t1.ct_concept as ct_concept, 
                    t1.ct_created as ct_created,
                    pa_name, pa_type,
                    concat(rs_propriety_inverse,pa_type) as propriety ";

        $sql = "select $cp 
        from th_concept_term as t1
        INNER JOIN th_concept_term as t2 ON t1.ct_concept_2 = t2.ct_concept and t2.ct_propriety = 25 
        INNER JOIN rdf_literal ON id_rl = t2.ct_term
        INNER JOIN rdf_resource ON t1.ct_propriety = id_rs
        INNER JOIN th_thesaurus ON t1.ct_th = id_pa
        WHERE t1.ct_concept = $id and ($wh)";

        $rlt = $this->query($sql)->getResultArray();
        if (count($rlt) > 0) {
            return ($rlt);
        } else {
            return ( array());
        }
    }

function data_TG($id)
        {
            $sx = '';    
            $dt = $this->TG($id);   
            if (count($dt) > 0)
                {
                    $label = lang('thesa.'.$dt[0]['propriety']).':';
                    $sx .= $label;
                    $sx .= '<ul>';
                }             
            for($r=0;$r < count($dt);$r++)
                {
                    $line = $dt[$r];                    

                    if (strlen(trim($line['rl_value'])) > 0)
                    {
                        $link = '<a href="'.PATH.MODULE.'c/'.$line['ct_concept'].'" class="text-primary">';
                        $linka = '</a>';
                        $term = $link.$line['rl_value'].$linka;
                        $term .= ' <sup>('.$line['rl_lang'].')</sup>';
                        $sx .= '<li class="ms-4 h6">'.$term.'</li>';
                    } else {
                        //print_r($line);
                    }
                } 
            if (count($dt) > 0)
                {
                    $sx .= '</ul>';
                }                
            $sx = bs($sx);    
            return $sx;
        }            

function data_TE($id)
        {
            $sx = '';    
            $dt = $this->TE($id);    
            if (count($dt) > 0)
                {
                    $label = lang('thesa.'.$dt[0]['propriety']).':';
                    $sx .= $label;
                    $sx .= '<ul>';
                }
            for($r=0;$r < count($dt);$r++)
                {
                    $line = $dt[$r];            
                    if (strlen(trim($line['rl_value'])) > 0)
                    {                        
                        $link = '<a href="'.PATH.MODULE.'c/'.$line['id_ct2'].'" class="text-primary">';
                        $linka = '</a>';
                        $term = $link.$line['rl_value'].$linka;
                        $term .= ' <sup>('.$line['rl_lang'].')</sup>';
                        $sx .= '<li class="ms-4 h6">'.$term.'</li>';
                    } else {
                        print_r($line);
                    }
                } 
            if (count($dt) > 0)
                {
                    $sx .= '</ul>';
                }

            $sx = bs($sx);    
            return $sx;
        }            

}
