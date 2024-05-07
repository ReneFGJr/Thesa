<?php

namespace App\Models\Concept;

use CodeIgniter\Model;

class Index extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_concept';
    protected $primaryKey       = 'id_c';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_c','c_concept','c_th','c_ativo','c_agency'
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

    function createConcept($id,$th)
        {
            $RSP = [];
            return $RSP;
            $TermsTh = new \App\Models\Term\TermsTh();
            $RDFclass = new \App\Models\RDF\ThProprity();

            $classID = $RDFclass->getClass('prefLabel');
            $RSP['class'] = $classID;
            return $RSP;

            $dt = $TermsTh
                    ->where('term_th_thesa',$th)
                    ->where('term_th_term', $id)
                    ->first();
            if ($dt['term_th_concept'] == 0)
                {
                    $prop = $RDFclass->getClass('prefLabel');
                    $RSP['prop'] = $prop;
                    return $RSP;

                    $dc = [];
                    $dc['c_th'] = $th;
                    $dc['c_ativo'] = 1;
                    $dc['c_agency'] = '';
                    $idC = $this->set($dc)->insert();

                    /*********** PrefLabel */


                    $dd = [];
                    $dd['term_th_concept'] = $idC;
                    $TermsTh->set($dd)->where('term_th_id',$dt['term_th_id '])->update();
                }

            pre($dt);
        }

    function createConceptAPI($dt)
        {
            $RSP['result'] = '';
            $itens = $dt['it'];
            $th = $dt['th'];
            $RSP = [];
            $RSP['terms'] = explode(',',$itens);

            foreach($RSP['terms'] as $id=>$item)
                {
                    $RSP['result'] .= $this->createConcept($item,$th).'<br>';
                }
            return $RSP;
        }

    function findAgent($agent,$th)
        {
            $this->where('c_agency',$agent);
            $this->where('c_th', $th);
            $dt = $this->first();
            return($dt['id_c']);
        }

    function registerInport($ag,$th)
        {
            $ag = troca($ag,'#','');
            $ag = mb_strtolower($ag);

            $dt = $this
            ->where('c_agency',$ag)
            ->where('c_th', $th)
            ->first();
            if ($dt == [])
                {
                    $dd = [];
                    $dd['c_th'] = $th;
                    $dd['c_agency'] = $ag;
                    $dd['c_concept'] = 0;
                    $dd['c_ativo'] = 1;
                    $id  = $this->set($dd)->insert();
                    $dd['c_concept'] = $id;
                    $this->set($dd)->where('id_c',$id)->update();
                } else {
                    $id = $dt['id_c'];
                }
            return $id;
        }
}
