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

    function le($id)
        {
            $dt = $this
                    /*  */
                    ->select('
                            id_c, c_concept, c_th,
                            term_name as label,
                            vc2.vc_label as resource,
                            lg_code, lg_language,
                            vc1.vc_label as property, spaceName')
                    /*  */
                    ->join('thesa_concept_term','ct_concept = id_c')
                    ->join('thesa_terms','ct_literal = id_term','left')
                    ->join('owl_vocabulary_vc as vc1','ct_propriety = vc1.id_vc','left')
                    ->join('owl_vocabulary', 'vc1.vc_prefix = id_owl', 'left')
                    ->join('owl_vocabulary_vc as vc2', 'ct_resource = vc2.id_vc', 'left')
                    ->join('language', 'term_lang = id_lg','left')

                    ->where('id_c',$id)
                    ->findAll();
            return $dt;
        }

    function edit($id)
    {
        $sx = '';
        $Term = new \App\Models\RDF\ThTerm();

        $sa = '<h3>S1</h3>';
        $sb = '<h3>S2</h3>';
        $sb .= '<div id="s2">S2</div>';

        $sa .= 'Termos';
        $sa .= '<br/>';
        $sa .= '<a href="#" id="prefLabel">prefLabel</a>';
        $sa .= '<br/>';
        $sa .= '<a href="#" id="altLabel">altLabel</a>';
        $sa .= '<br/>';
        $sa .= '<a href="#" id="hiddenLabel">hiddenLabel</a>';

        $sx .= bs(bsc($sa, 4) . bsc($sb, 8));

        $sx .= '<script>';
        $sx .= '$("#prefLabel").click(function() {';
        $sx .= '    alert("PrefLabel");';
        $sx .= '    $("#s2").load("' . (PATH . 'rdf/term/prefLabel/' . $id) . '");';
        $sx .= '});';

        $sx .= '$("#altLabel").click(function() {';
        $sx .= '    alert("altLabel");';
        $sx .= '});';

        $sx .= '$("#hiddenLabel").click(function() {';
        $sx .= '    alert("hiddenLabel");';
        $sx .= '});';

        $sx .= '</script>';

        return $sx;
    }

    function header($dt)
        {
            $sx = '';
            $prefTerm = array();
            for ($r=0;$r < count($dt);$r++)
                {
                    $line = $dt[$r];
                    if ($line['property'] == 'prefLabel')
                        {
                            $prefTerm[$line['lg_code']] = $line['label'];
                        }
                }


            $sa = '';
            $sb = '';

            foreach($prefTerm as $key => $value)
                {
                    $sb = '<span id="prefTerm" class="big">'.$value.'</span>';
                    $sb .= textClipboard('prefTerm');
                }

            $sc = anchor(PATH . 'v/' . $dt[0]['id_c'], bsicone('eye'));

            $sd = '';
            $sd .= '<span class="btn btn-outline-secondary" id="btnEdit">Thesa:c'. $dt[0]['id_c'].'</span>';

            $sx = bs(
                bsc($sa,3,'text-end') .
                bsc($sb, 7).
                bsc($sc, 1, 'text-end') .
                bsc($sd, 1, 'text-end'));
            return $sx;
        }

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

            return $id_term. ' created'.'<br>';
        }
}
