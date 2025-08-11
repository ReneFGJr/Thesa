<?php

namespace App\Models\Term;

use CodeIgniter\Model;

class TermConcept extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_concept_term';
    protected $primaryKey       = 'id_ct';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_ct',
        'ct_propriety',
        'ct_th',
        'ct_concept',
        'ct_literal',
        'ct_use',
        'ct_resource',
        'ct_concept_2',
        'ct_concept_2',
        'ct_concept_2_qualify'
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

    function register($th, $concept, $prop, $qualy, $resource, $literal)
    {
        $data['ct_concept'] = $concept;
        $data['ct_propriety'] = $prop;
        $data['ct_th'] = $th;
        $data['ct_literal'] = $literal;
        $data['ct_resource'] = $resource;
        $data['ct_concept_2_qualify'] = $qualy;
        $data['ct_use'] = 0;

        /* Verifica se já existe */
        $da = $this
            ->where('ct_th', $th)
            ->where('ct_concept', $concept)
            ->where('ct_propriety', $prop)
            ->orderBy('ct_literal')
            ->first();

        if (!$da) {
            /* Verifica se existe conceite sem termo */
            $db = $this
                ->where('ct_th', $th)
                ->where('ct_concept', $concept)
                ->where('ct_propriety', $prop)
                ->where('ct_literal', 0)
                ->first();
            if ($db) {
                $da = $db;
            }
        }

        if (!$da) {
            $id = $this->set($data)->insert();
        } else {
            $this->set($data)->where('id_ct', $da['id_ct'])->update();
            $id = $da['id_ct'];
        }
        return $id;
    }


    function register_term_label($thesaID, $conceptID, $termID, $prop)
        {
            $ThProprity = new \App\Models\RDF\ThProprity();
            $propID = $ThProprity->getClass($prop);

            $dd = [];
            $dt = $this->where('ct_th', $thesaID)
            ->where('ct_concept', $conceptID)
            ->where('ct_literal', $termID)
            ->findAll();
            /* Checa se o termo já existe */
            if ($dt == [])
                {
                    if ($propID == 0) {
                        $RSP['status'] = '500';
                        $RSP['message'] = 'Property not found';
                        return $RSP;
                    }
                    $dd['ct_propriety'] = $propID;
                    $dd['ct_th'] = $thesaID;
                    $dd['ct_concept'] = $conceptID;
                    $dd['ct_literal'] = $termID;
                    $dd['ct_use'] = 1;
                    $dd['ct_resoult'] = 0;
                    $dd['ct_concept_2'] = 0;
                    $dd['ct_concept_2_qualify'] = 0;
                    $idp = $this->set($dd)->insert();
                    $RSP['status'] = '200';
                    $RSP['message'] = 'Term registered in thesaurus '.$idp;

                } else {
                    $RSP['status'] = '500';
                    $RSP['message'] = 'Term already registered in thesaurus';
                    return $RSP;
                }


        }

    function le($id, $PropP='')
        {
            $ThConcept = new \App\Models\Thesa\Concepts\Index();
            $dt = $ThConcept->le($id);

            $da = $this
                    ->join('thesa_property', 'ct_propriety = id_p')
                    ->join('thesa_terms', 'ct_literal = id_term','left')
                    ->join('language', 'term_lang = id_lg','left')
                    ->where('ct_concept', $id)
                    ->where('p_name', $PropP)
                    ->findAll();
            $dd = [];
            foreach($da as $id=>$line)
                {
                    $daa = [];
                    $daa['Term'] = $line['term_name'];
                    $daa['Lang'] = $line['lg_code'];
                    $daa['idReg'] = $line['id_ct'];

                    array_push($dd,$daa);
                }
            return $dd;
        }
}
