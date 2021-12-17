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

    function prefLabel($id,$class="prefLabel")
        {
            $sql = "select * from th_concept_term 
                    INNER JOIN rdf_literal ON id_rl = ct_term
                    INNER JOIN rdf_resource ON ct_propriety = id_rs
                    where ct_concept = ".$id." and rs_propriety = 'prefLabel'";
            $dt = $this->query($sql)->getResult();
            return $dt;
        }

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
                        $sx .= bsc(lang('thesa.'.$line['rs_propriety']).':',4,'text-end mb-2');
                        $term = $line['rl_value'];
                        $term .= ' <sup>('.$line['rl_lang'].')</sup>';
                        $sx .= bsc($term,8);
                    }
                } 
 

            //$sx = bs($sx);    
            return $sx;
        }

}
