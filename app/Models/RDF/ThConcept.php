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
            ->join('thesa_concept_term', 'ct_concept = id_c')
            ->join('thesa_terms', 'ct_literal = id_term', 'left')
            ->join('owl_vocabulary_vc as vc1', 'ct_propriety = vc1.id_vc', 'left')
            ->join('owl_vocabulary', 'vc1.vc_prefix = id_owl', 'left')
            ->join('owl_vocabulary_vc as vc2', 'ct_resource = vc2.id_vc', 'left')
            ->join('language', 'term_lang = id_lg', 'left')

            ->where('id_c', $id)
            ->findAll();
        return $dt;
    }

    function form($id)
    {
        $Thesa = new \App\Models\Thesa\Thesa();
        $ThConcept = new \App\Models\RDF\ThConcept();
        $ThProprity = new \App\Models\RDF\ThProprity();
        $sx = '';
        $sx .= bsc(h('Propriedades', 3), 12);

        $dt = $ThConcept->le($id);
        $th = $dt[0]['c_th'];

        $ts = $Thesa->find($th);

        $type = $ts['th_type'];


        $cp = 'p_name, p_reverse, p_group, rg_range';
        $f = $ThProprity
            ->select($cp)
            ->join('owl_vocabulary_vc', 'p_name = vc_label', 'left')
            ->join('thesa_property_range', 'p_name = rg_class', 'left')
            ->where('p_th', $th)
            ->orwhere('p_global >= ' . $type)
            ->groupBy($cp)
            ->orderBy('p_group')
            ->findAll();

        $xgr = '';
        for ($r = 0; $r < count($f); $r++) {
            $line = $f[$r];
            $gr = substr($line['p_group'], 0, 1);
            if ($xgr != $gr) {
                if ($xgr != '') {
                    $sx .= '</div>';
                }

                $sx .= '<div id="grupo_' . $gr . '" class="row">';
                $sx .= h('<b>' . lang('thesa.form_group_' . $gr) . '</b>', 5);
                $xgr = $gr;
            }
            $sx .= bsc(lang('thesa.' . $line['p_name']), 3, 'text-end');

            /*********************************************** RECUPERA VALORES  */
            $st = '<div id="form_thesa_' . $line['p_name'] . '">';
            for ($y = 0; $y < count($dt); $y++) {
                $dtl = $dt[$y];
                if ($dtl['property'] == $line['p_name']) {
                    $linkr = '<span style="color: red">' . bsicone('trash', 18) . '</span>';
                    $st .= $linkr;
                    $st .= '<b>' . $dtl['label'] . '</b>' . '<sup>@' . $dtl['lg_code'] . '</sup><br/>';
                }
            }
            $st .= $this->form_field($id, $line['p_name']);
            $st .= '</div>';
            $sx .= bsc($st, 10, 'over');
        }


        if ($xgr != '') {
            $sx .= '</div>';
        }
        //$sx .= '<style> div { border: 1px solid #000; } </style>';
        $sx = bs($sx);
        return $sx;
    }

    function list_concepts_terms($id, $prop = '')
    {

        $Thesa = new \App\Models\Thesa\Thesa();
        $ThConcept = new \App\Models\RDF\ThConcept();
        $ThProprity = new \App\Models\RDF\ThProprity();
        $ThConceptPropriety = new \App\Models\RDF\ThConceptPropriety();
        $sx = '';
        $sx .= bsc(h('Propriedades', 3), 12);

        $dt = $ThConcept->le($id);
        $th = $dt[0]['c_th'];

        $ts = $Thesa->find($th);

        $type = $ts['th_type'];
        $cp = 'vc_label, term_name, lg_code';

        $f = $ThConceptPropriety
            ->select($cp)
            ->join('owl_vocabulary_vc', 'id_vc = ct_propriety', 'left')
            ->join('thesa_terms', 'id_term = ct_literal', 'left')
            ->join('language', 'term_lang = id_lg', 'left')
            ->where('ct_th', $th)
            ->where('ct_concept', $id)
            ->where('vc_label', $prop)
            ->groupby($cp)
            ->orderby($cp)
            ->findAll();

        for ($r = 0; $r < count($f); $r++) {
            $line = $f[$r];
            $linkr = '<span style="color: red">' . bsicone('trash', 18) . '</span>';
            $sx .= $linkr;
            $sx .= '<b>' . $line['term_name'] . '</b>' . '<sup>@' . $line['lg_code'] . '</sup><br/>';
            }
        $sx .= $this->form_field($id, $line['vc_label']);
        echo $sx;
        exit;
    }

    function ajax_save($id, $prop, $vlr)
    {
        $ThTermTh = new \App\Models\RDF\ThTermTh();
        /***************************************************** PROPRERTY */
        $VocabularyVC = new \App\Models\RDF\Ontology\VocabularyVC();
        $id_prop = $VocabularyVC->find_prop($prop);

        /***************************************************** RANGE */
        $ThProprity = new \App\Models\RDF\ThProprity();
        $dp = $ThProprity->find_prop($prop);
        if (count($dp) == 0) {
            echo "ERRO DE RANGE - $prop";
        }

        /************************************************* ID CONCEPT */
        $ThConcept = new \App\Models\RDF\ThConcept();
        $dtc = $ThConcept->find($id);
        $th = $dtc['c_th'];
        $concept = $id;
        $resource = 0;
        $literal = $vlr;
        $range = $dp['rg_range'];

        switch ($range) {
            case 'Literal':
                $ThConceptPropriety = new \App\Models\RDF\ThConceptPropriety();
                $ThConceptPropriety->register($th, $concept, $id_prop, $resource, $literal);
                $ThTermTh->update_term_th($vlr,$th, $concept);
                break;
            default:
                echo "save $id - $prop - $vlr";
                break;
        }
        return "";
    }

    function ajax_edit($id, $prop)
    {
        $sx = '';
        $ThPropertyRange = new \App\Models\RDF\ThPropertyRange();
//        $da = $ThPropertyRange->find_prop($prop);

        echo h($prop);
        $tp = 'Literal';

        switch ($tp) {
            case 'Literal':
                $sx .= $this->form_field_level($id, $prop);
                break;
        }
        return $sx;
    }

    function form_field_level($id, $prop)
    {
        $ThConcept = new \App\Models\RDF\ThConcept();
        $ThTermTh = new \App\Models\RDF\ThTermTh();

        $dc = $ThConcept->le($id);
        $th = $dc[0]['c_th'];


        echo h($prop);

        $dt = $ThTermTh->termNoUse($th);


        $sx = '';
        $sx .= '<div class="row">';
        $sx .= '<div class="col-md-3">';
        $sx .= '<input type="text" id="form_thesa_' . $prop . '_label" class="form-control">';
        $sx .= 'Filter';
        $sx .= '</div>';
        $sx .= '<div class="col-md-10">';
        $sx .= '<select id="form_thesa_prop_' . $prop . '_label" class="form-control" size=5>';
        for ($r = 0; $r < count($dt); $r++) {
            $line = $dt[$r];
            $sx .= '<option value="' . $line['id_term'] . '">' . $dt[$r]['term_name'] . ' (' . $dt[$r]['lg_code'] . ')</option>';
        }
        $sx .= '</select>';
        $sx .= '<span class="btn btn-outline-primary" onclick="save_form_' . $prop . '();">' . lang('thesa.save') . '</span>';
        $sx .= '</div>';

        $sx .= '<script>';
        $sx .= 'function save_form_' . $prop . '()' . cr();
        $sx .= '{' . cr();
        $sx .= '$vlr = $("#form_thesa_prop_' . $prop . '_label").val();' . cr();
        $sx .= ' var url = "' . PATH . '/admin/ajax_form_save/?id=' . $id . '&prop=' . $prop . '&vlr="+$vlr;' . cr();
        $sx .= ' $("#form_thesa_' . $prop . '").load(url);' . cr();
        $sx .= '}' . cr();
        $sx .= '</script>';
        return $sx;
    }

    function form_field($id = 0, $prop = 0, $gr = 2)
    {
        global $jsf;

        $sx = '<div id="form_thesa_' . $id . '">';
        $sx .= '<a href="#" onclick="form_thesa_label(\'' . $id . '\',\'' . $prop . '\');">' . bsicone('plus', 18) . '</a>';
        $sx .= '</div>';

        if (!isset($jsf)) {
            $jsf = true;
            $sx .= '
                    <script>
                    function form_thesa_label($id,$prop)
                        {
                            $("#form_thesa_"+$prop).html("' . lang('thesa.loading') . '");
                            var url = "' . PATH . '/admin/ajax_form/?id=" + $id + "&prop=" + $prop;
                            $.ajax({
                                type: "POST",
                                url: url,
                                success: function(rsp)
                                {
                                    $("#form_thesa_"+$prop).html(rsp);
                                }
                            });
                        }
                    </script>';
        }
        return $sx;
    }

    function edit($id)
    {
        $sx = '';
        $Term = new \App\Models\RDF\ThTerm();

        $sx .= $this->form($id);

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
        for ($r = 0; $r < count($dt); $r++) {
            $line = $dt[$r];
            if ($line['property'] == 'prefLabel') {
                $prefTerm[$line['lg_code']] = $line['label'];
            }
        }


        $sa = '';
        $sb = '';

        foreach ($prefTerm as $key => $value) {
            $sb = '<span id="prefTerm" class="big">' . $value . '</span>';
            $sb .= textClipboard('prefTerm');
        }

        $sc = anchor(PATH . 'v/' . $dt[0]['id_c'], bsicone('eye'));

        $sd = '';
        $sd .= '<span class="btn btn-outline-secondary" id="btnEdit">Thesa:c' . $dt[0]['id_c'] . '</span>';

        $sx = bs(
            bsc($sa, 3, 'text-end') .
                bsc($sb, 7) .
                bsc($sc, 1, 'text-end') .
                bsc($sd, 1, 'text-end')
        );
        return $sx;
    }

    function register($id_term, $th, $agency = '')
    {
        /******************************************************* NEW CONCEPT */
        $dt = $this->where('c_concept', -1)->where('c_th', $th)->findAll();

        if (count($dt) == 0) {
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
        $sx = '<a href="' . PATH . 'v/' . $id_concept . '" class="btn btn-outline-secondary">' . 'thesa:c' . $id_term . '</a>' . ' created';
        return $sx . '<br>';
    }
}
