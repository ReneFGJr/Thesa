<?php

namespace App\Models\Thesaurus;

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
    protected $allowedFields        = [
        'id_ct','ct_concept','ct_th','ct_term','ct_concept_2','ct_propriety'
    ];

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
            $dt = $this
                    ->select('count(*) as total, ct_term')
                    ->where('ct_th',$id)
                    ->groupBy('ct_th, ct_term')
                    ->findAll();
            $sx = count($dt);

            $sql = "select count(*) as total 
                        from rdf_literal_th 
                        where lt_thesauros = $id";
            $rlt = $this->db->query($sql)->getResult();
            $line = (array)$rlt[0];
            $sx .= ' / '.$line['total'];
            return $sx;
        }
    function create_concept_term($term,$concept,$th)
        {
            $dt = $this->where('ct_term',$term)->where('ct_th',$th)->findAll();
            if (count($dt) == 0)
                {
                    $dd['ct_propriety'] = '25';
                    $dd['ct_concept_2'] = '0';
                    $dd['ct_term'] = $term;
                    $dd['ct_th'] = $th;
                    $dd['ct_concept'] = $concept;
                    $this->set($dd)->insert();
                }
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
}
