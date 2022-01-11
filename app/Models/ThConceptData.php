<?php

namespace App\Models;

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

function data($id)
        {
            $sx = '';    
            //$this->where('ct_concept',$id); 
            $this->join('rdf_literal','ct_term = id_rl','left');
            $this->join('rdf_resource','ct_propriety = id_rs','left');            
            $this->where('ct_concept',$id); 
            $this->orderBy('rs_propriety desc, rl_value');
            $dt =  $this->findAll();

            /***************************** Termos */
            //$sx = '<style> div { border: 1px solid #000000; </style>';
            for($r=0;$r < count($dt);$r++)
                {
                    $line = $dt[$r];

                    if (strlen(trim($line['rl_value'])) > 0)
                    {
                        $label = lang('thesa.'.$line['rs_propriety']).':';
                        $sx .= bsc($label,4,'text-end mb-2');
                        $term = $line['rl_value'];
                        $term .= ' <sup>('.$line['rl_lang'].')</sup>';
                        $sx .= bsc($term,8);
                    }
                } 
            //$sx = bs($sx);    
            return $sx;
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
            for($r=0;$r < count($dt);$r++)
                {
                    $line = $dt[$r];                    

                    if (strlen(trim($line['rl_value'])) > 0)
                    {
                        $label = lang('thesa.'.$line['propriety']).':';
                        $sx .= bsc($label,4,'text-end mb-2');
                        $link = '<a href="'.PATH.MODULE.'c/'.$line['ct_concept'].'" class="text-primary">';
                        $linka = '</a>';
                        $term = $link.$line['rl_value'].$linka;
                        $term .= ' <sup>('.$line['rl_lang'].')</sup>';
                        $sx .= bsc($term,8);
                    } else {
                        //print_r($line);
                    }
                } 
            $sx = bs($sx);    
            return $sx;
        }            

function data_TE($id)
        {
            $sx = '';    
            $dt = $this->TE($id);    
            for($r=0;$r < count($dt);$r++)
                {
                    $line = $dt[$r];                    

                    if (strlen(trim($line['rl_value'])) > 0)
                    {
                        $label = lang('thesa.'.$line['propriety']).':';
                        $sx .= bsc($label,4,'text-end mb-2');
                        $link = '<a href="'.PATH.MODULE.'c/'.$line['ct_concept'].'" class="text-primary">';
                        $linka = '</a>';
                        $term = $link.$line['rl_value'].$linka;
                        $term .= ' <sup>('.$line['rl_lang'].')</sup>';
                        $sx .= bsc($term,8);
                    } else {
                        print_r($line);
                    }
                } 
            $sx = bs($sx);    
            return $sx;
        }            

}
