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
        'id_ct','ct_concept','ct_th','ct_term','ct_use','ct_propriety'
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

    function le($id)
        {
            $dt = $this
                ->join('th_literal','ct_term = id_n','inner')
                ->join('th_proprieties','ct_propriety = id_p','inner')
                ->join('th_proprieties_prefix','p_prefix = id_prefix','inner')
                ->where('ct_concept', $id)
                ->findAll();
            return $dt;
        }
    function resume($id)
        {
            $ThLiteralTh = new \App\Models\Thesaurus\ThLiteralTh();
            $dt = $this
                    ->select('count(*) as total, ct_term')
                    ->where('ct_th',$id)
                    ->groupBy('ct_th, ct_term')
                    ->findAll();
            $sx = count($dt);

            $tot1 = $ThLiteralTh->total($id);
            $sx .= ' / '.$tot1;
            return $sx;
        }
    function create_concept_term($term,$concept,$th)
        {
            $Proprieties = new \App\Models\RDF\Proprieties();
            $dt = $this->where('ct_term',$term)->where('ct_th',$th)->findAll();
            if (count($dt) == 0)
                {
                    $prop = $Proprieties->getPropriety('Concept');
                    $dd['ct_propriety'] = $prop;
                    $dd['ct_use'] = '0';
                    $dd['ct_term'] = $term;
                    $dd['ct_th'] = $th;
                    $dd['ct_concept'] = $concept;
                    $this->set($dd)->insert();
                }
        }

    function prefLabel($id,$class="prefLabel")
        {
            $dt = $this
                ->join('th_literal','id_n = ct_term','inner')
                ->where('id_ct',$id)
                ->FindAll();
            
            return $dt;
        }
}
