<?php

namespace App\Models;

use CodeIgniter\Model;

class ThConceptTerms extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'th_concept_term';
    protected $primaryKey           = 'id_ct';
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
            $dt = $this->where('ct_th',$id)->get();
            $rst = $dt->resultID->num_rows;
            return $rst;
        }
    function data($id)
        {
            $sx = '';    

            $this->where('ct_concept',$id); 
            $this->orwhere('ct_concept_2',$id); 
            $dt =  $this->findAll();

            for($r=0;$r < count($dt);$r++)
                {
                    $line = $dt[$r];
                    echo '<pre>';
                    print_r($line);
                    echo '</pre>';                    
                    //$sx .= bsc(strtoupper($line['rs_propriety']),3);
                    //$sx .= bsc($line['rl_value'],9);
                } 
            return '';


            $this->join('rdf_resource','ct_propriety = id_rs');
            $this->join('rdf_literal','ct_term = id_rl','left');
            $this->where('ct_concept',$id);   
            $this->orWhere('th_concept_term.ct_concept_2',$id);
            $dt =  $this->findAll();

            for($r=0;$r < count($dt);$r++)
                {
                    $line = $dt[$r];
                    echo '<pre>';
                    print_r($line);
                    echo '</pre>';                    
                    $sx .= bsc(strtoupper($line['rs_propriety']),3);
                    $sx .= bsc($line['rl_value'],9);
                } 

            
            $this->join('th_concept_term as lt1','th_concept_term.ct_propriety = lt1.id_ct');
            $this->join('rdf_resource','th_concept_term.ct_propriety = id_rs');
            $this->join('rdf_literal','lt1.ct_term = id_rl', 'rifght');
            $this->where('th_concept_term.ct_concept_2',$id);
            $dt =  $this->findAll();

            for($r=0;$r < count($dt);$r++)
                {
                    $line = $dt[$r];
                    echo '<pre>';
                    print_r($line);
                    echo '</pre>';
                    $sx .= bsc($line['rs_propriety'],3);
                    //$sx .= bsc($line['rl_value'],9);
                }

            $sx = '<div class="row">'.$sx.'</div>';    
            return $sx;
        }

}
