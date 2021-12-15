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
            $this->join('rdf_literal','ct_term = id_rl','left');
            $this->join('rdf_class','ct_propriety = id_c','left');            
            $this->where('ct_concept',$id); 
            $dt =  $this->findAll();

            for($r=0;$r < count($dt);$r++)
                {
                    $line = $dt[$r];
                    $sx .= bsc('',1);
                    $sx .= bsc($line['c_class'].':',2,'text-end mb-2');
                    $sx .= bsc($line['rl_value'],9);
                } 
 

            $sx = bs($sx);    
            return $sx;
        }

}
