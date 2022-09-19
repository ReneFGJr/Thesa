<?php

namespace App\Models\RDF;

use CodeIgniter\Model;

class ThConcept extends Model
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

    function register($id_term, $th, $agency='')
        {
            /******************************************************* NEW CONCEPT */
            $dt = $this->where('c_concept', -1)->where('c_th', $th)->findAll();

            if (count($dt) == 0)
            {
                $data['c_concept'] = 0;
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
            $ThConceptPropriety->register($th, $id_concept, $id_prop, $id_class, 0);

            /********************************************** Update Concept */
            $du['c_concept'] = $id_concept;
            $this->set($du)->where('id_c', $id_concept)->update();

            $class = 'skos:prefLabel';
            $prop_prefLabel = $ClassPropriety->Class($class, 'Property');

            $idr = $ThConceptPropriety->register($th, $id_concept, $prop_prefLabel, 0, $id_term);


            /********************************************** Trava o Termos do Vocabulario */
            $Term = new \App\Models\RDF\ThTerm();
            $Term->term_block($id_term, $id_concept, $th);

            echo '
            <script>
                var url = "'.base_url(PATH.COLLECTION. '/terms/ajax_term_update/').'";
                $("#result").html("");
                $("#term_list_div").load(url);
            </script>
            ';
            echo h($id_concept);
            exit;

        }
}
