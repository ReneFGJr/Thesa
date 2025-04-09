<?php

namespace App\Models\Thesa\Concepts;

use CodeIgniter\Model;

class Index extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_concept';
    protected $primaryKey       = '	id_c';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_c', 'c_concept', 'c_th',
        'c_ativo', 'c_agency'
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

    function change_status($id,$st)
        {
            $data['c_status'] = $st;
            $this->set($data)->where('id_c',$id)->update();
            return "";
        }

    function reserve($th, $agency)
        {
            $dt = $this
                ->where('c_agency',$agency)
                ->where('c_th', $th)
                ->first();
            if ($dt=='')
                {
                    $data['c_agency'] = $agency;
                    $data['c_th'] = $th;
                    $data['c_ativo'] = -1;
                    $data['c_concept'] = 0;
                    $this->set($data)->insert();
                }
        }

    function register($id_term, $th, $agency = '',$rsp='')
    {
        $id_concept = $this->register_concept($th, $agency);

        /***************************************** Class - SkosConcept */
        $ClassPropriety = new \App\Models\RDF\Ontology\ClassPropryties();
        $ThConceptPropriety = new \App\Models\RDF\ThConceptPropriety();

        /********************************************** Update Concept */
        $du = array();
        $du['c_concept'] = $id_concept;
        $du['c_ativo'] = 1;
        $this->set($du)->where('id_c', $id_concept)->update();

        $class = 'skos:prefLabel';
        $prop_prefLabel = $ClassPropriety->Class($class);
        $idr = $ThConceptPropriety->register($th, $id_concept, $prop_prefLabel, 0, 0, $id_term);

        /********************************************** Trava o Termos do Vocabulario */
        $Term = new \App\Models\RDF\ThTerm();
        $Term->term_block($id_term, $id_concept, $th);

        if ($rsp == 'id')
            {
                return $id_concept;
            }

        $sx = '<a href="' . PATH . '/v/' . $id_concept . '" class="btn btn-outline-secondary">' . 'thesa:c' . $id_term . '</a>' . ' created';
        return $sx . '<br>';
    }

    function register_concept($th, $agency)
    {
        /******************************************************* NEW CONCEPT */
        if ($agency != '') {
            $dt = $this->where('c_agency', $agency)->where('c_th', $th)->findAll();
        } else {
            $dt = $this->where('c_concept', -1)->where('c_th', $th)->findAll();
        }

        if (count($dt) == 0) {
            $data['c_th'] = $th;
            $data['c_concept'] = -1;
            $data['c_ativo'] = 1;
            $data['c_agency'] = $agency;
            $id_concept = $this->insert($data);
        } else {
            $id_concept = $dt[0]['id_c'];
        }

        /***************************************** Class - SkosConcept */
        $ClassPropriety = new \App\Models\RDF\Ontology\ClassPropryties();
        $ThConceptPropriety = new \App\Models\RDF\ThConceptPropriety();

        $class = 'skos:Concept';
        $id_class = $ClassPropriety->Class($class);

        $class = 'rdf:isInstanceOf';
        $id_prop = $ClassPropriety->Class($class);

        /****************************************** Class- Register ****/
        $ThConceptPropriety->register($th, $id_concept, $id_prop, 0, $id_class, 0);
        $data['c_concept'] = $id_concept;
        $this->set($data)->where('id_c', $id_concept)->update();

        return $id_concept;
    }

    function recover_th($id)
        {
            $dt = $this->where('c_concept',$id)->first();
            return($dt['c_th']);
        }

    function le($id)
    {
        $dt = $this->find($id);
        $th = $dt['c_th'];

        $dt = $this
            /*  */
            ->select('
                id_c, c_concept, c_th,
                t1.id_ct as id_ct,
                term_name as label,
                p_name as resource_name,
                lg_code, lg_language,
                t1.ct_resource as ct_resource,
                t1.ct_concept_2 as ct_concept_2')
            /*  */
            ->join('thesa_concept_term as t1', 't1.ct_concept = id_c')
            ->join('thesa_terms', 'ct_literal = id_term', 'left')
            ->join('thesa_property as vc1', 'ct_propriety = vc1.id_p', 'left')
            ->join('language', 'term_lang = id_lg', 'left')
            ->join('thesa_language', 'lgt_language = id_lg')
            ->where('id_c', $id)
            ->where('lgt_th',$th)
            ->orderBy('p_name desc, lgt_order')
            ->first();

            $ThesaMidias = new \App\Models\Thesa\Medias\Index();
            $dt['medias'] = $ThesaMidias->le($id);
        return $dt;
    }
}
