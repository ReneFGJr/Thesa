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
                            t1.id_ct as id_ct,
                            term_name as label,
                            vc2.vc_label as resource_name,
                            lg_code, lg_language,
                            t1.ct_resource as ct_resource,
                            t1.ct_concept_2 as ct_concept_2,
                            vc1.vc_label as property, spaceName')
            /*  */
            ->join('thesa_concept_term as t1', 't1.ct_concept = id_c')
            ->join('thesa_terms', 'ct_literal = id_term', 'left')
            ->join('owl_vocabulary_vc as vc1', 'ct_propriety = vc1.id_vc', 'left')
            ->join('owl_vocabulary', 'vc1.vc_prefix = id_owl', 'left')
            ->join('owl_vocabulary_vc as vc2', 'ct_resource = vc2.id_vc', 'left')
            ->join('language', 'term_lang = id_lg', 'left')
            ->where('id_c', $id)
            ->findAll();
        return $dt;
    }

    function le_relation($id,$prop='')
    {
        $ThConceptPropriety = new \App\Models\RDF\ThConceptPropriety();
        $dt =
            $ThConceptPropriety
            ->join('owl_vocabulary_vc', 'ct_propriety = id_vc', 'left')
            ->where('(ct_concept = '.$id. ' or ct_concept_2 = '.$id. ') and ct_literal = 0')
            ->findAll();

        //pre($dt,false);
        return $dt;
        /* Relations */
        $this
            ->select('*')
            ->join('thesa_concept_term as t1', 't1.ct_concept = id_c')
            ->join('thesa_concept_term as t2', 't1.ct_concept = t2.ct_concept')
            ->join('thesa_terms', 't2.ct_literal = id_term', 'left')
            ->join('language', 'term_lang = id_lg', 'left');

        /* Proposition */
        if ($prop != '')
            {
                $this->join('owl_vocabulary_vc', 't1.ct_propriety = id_vc', 'left');
                //$this->where('ct_propriety',$prop);
            }
        $this
            ->where('c_concept', $id)
            ->where('t1.ct_concept_2 >', 0)
            ->where('term_name is not null');
        $dr = $this->findAll();
        pre($dr,false);
        return $dr;
    }

    function form($id)
    {
        $Thesa = new \App\Models\Thesa\Thesa();
        $ThConcept = new \App\Models\RDF\ThConcept();
        $ThProprity = new \App\Models\RDF\ThProprity();
        $sx = '';
        $sx .= bsc(h('Propriedades-1', 3), 12);

        $concept_data = $ThConcept->le($id);
        $th = $concept_data[0]['c_th'];
        $ts = $Thesa->find($th);
        $type = $ts['th_type'];


        /* Form */
        $cp = 'p_name, p_reverse, p_group, rg_range';

        $f = $ThProprity
            ->select($cp)
            ->join('owl_vocabulary_vc', 'p_name = vc_label', 'left')
            ->join('thesa_property_range', 'p_group >= rg_group_1 and p_group <= rg_group_2', 'left')
            ->where('p_th', $th)
            ->orwhere('p_global >= ' . $type)
            ->groupBy($cp)
            ->orderBy('p_group')
            ->findAll();
        $xgr = '';
        for ($r = 0; $r < count($f); $r++) {
            $line = $f[$r];

            /**** Groups */
            $gr = substr($line['p_group'], 0, 1);
            if ($xgr != $gr) {
                if ($xgr != '') {
                    $sx .= '</div>';
                }

                $sx .= '<div id="grupo_' . $gr . '" class="row mt-4">';
                $sx .= h('<b>' . lang('thesa.form_group_' . $gr) . '</b>', 5);
                $xgr = $gr;
            }

            switch ($line['rg_range']) {
                case 'Concept':
                    $btn_plus = $this->form_field_concept($id, $line['p_name']);
                    /*********************************************** RECUPERA VALORES  */
                    $st = '<div id="form_thesa_' . $line['p_name'] . '" class="mb-3">';
                    $st .= $this->list_concepts_relations($id, $line['p_name'], false);
                    $st .= '</div>';
                    $st = '';
                    break;
                case 'Literal':
                    $btn_plus = $this->form_field($id, $line['p_name']);
                    /*********************************************** RECUPERA VALORES  */
                    $st = '<div id="form_thesa_' . $line['p_name'] . '" class="mb-3">';
                    $st .= $this->list_concepts_terms($id, $line['p_name'], false);
                    $st .= '</div>';
                    break;
                case 'Text':
                    $st = '<div id="form_thesa_' . $line['p_name'] . '" class="mb-3">';
                    $st .= $this->list_concepts_text($id, $line['p_name'], false);
                    $st .= '</div>';
                    $btn_plus = $this->form_field_text($id, $line['p_name']);
                    break;
            }

            $sx .= bsc(lang('thesa.' . $line['p_name']) . $btn_plus, 4, 'mb-3');
            $sx .= bsc($st, 8, 'over');
        }

        $Reference = new \App\Models\Thesa\Reference();
        $btn_plus = $this->form_field_reference($id, 'reference');
        $sx .= bsc(lang('thesa.reference') . $btn_plus,4);
        $st = '<div id="form_thesa_reference'. '" class="mb-3">';
        $st .= $Reference->list_reference($id);
        $st .= '</div>';
        $sx .= bsc($st, 8, 'over');

        if ($xgr != '') {
            $sx .= '</div>';
        }
        //$sx .= '<style> div { border: 1px solid #000; } </style>';
        $sx = bs($sx);
        return $sx;
    }

    /************************************************** LISTA CONCEITOS */
    function list_concepts_relations($id, $prop = '', $stop = true)
    {
        $Thesa = new \App\Models\Thesa\Thesa();
        $ThConcept = new \App\Models\RDF\ThConcept();
        $sx = '';
        $dt = $ThConcept->le_relation($id,$prop);

        //pre($dt);
        return "XX";

        $th = $dt[0]['c_th'];

        $ts = $Thesa->find($th);

        for ($r = 0; $r < count($f); $r++) {
            $line = $f[$r];
            $linkr = '<span style="color: red" onclick="term_delete(' . $line['id_ct'] . ',\'' . $line['vc_label'] . '\');">' . bsicone('trash', 18) . '</span>';
            $sx .= $linkr;
            $sx .= '<b>' .
                $line['term_name'] . '</b>' .
                '<sup>@' . $line['lg_code'] .
                '</sup><br/>';
        }

        if ($stop) {
            echo $sx;
            exit;
        } else {
            return $sx;
        }
    }

    /*************************************************** TEXT CONCEPT */
    function list_concepts_text($id, $prop = '', $stop = true)
        {
            $ThNotes = new \App\Models\RDF\ThNotes();
            $sx = $ThNotes->list($id, $prop);

            if ($sx == '')
            {
                $sx = '<span class="small ms-2 text-danger"><i>' . mb_strtolower(lang('thesa.without') . ' ' . lang('thesa.' . $prop)) . '</i></span>';
            }

            return $sx;
        }

    /*************************************************** LISTA TERMOS */
    function list_concepts_terms($id, $prop = '', $stop = true)
    {
        $Thesa = new \App\Models\Thesa\Thesa();
        $ThConcept = new \App\Models\RDF\ThConcept();
        $ThProprity = new \App\Models\RDF\ThProprity();
        $ThConceptPropriety = new \App\Models\RDF\ThConceptPropriety();
        $sx = '';
        //$sx .= bsc(h('Propriedades-2', 3), 12);

        $dt = $ThConcept->le($id);

        $th = $dt[0]['c_th'];

        $ts = $Thesa->find($th);

        $type = $ts['th_type'];
        $cp = 'vc_label, term_name, lg_code, id_ct, ct_resource';

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
            $linkr = '<span style="color: red" onclick="term_delete(' . $line['id_ct'] . ',\'' . $line['vc_label'] . '\');">' . bsicone('trash', 18) . '</span>';
            $sx .= $linkr;
            $sx .= '<b>' .
                $line['term_name'] . '</b>' .
                '<sup>@' . $line['lg_code'] .
                '</sup><br/>';
        }

        if ($stop) {
            echo $sx;
            exit;
        } else {
            return $sx;
        }
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
                $ThTermTh->update_term_th($vlr, $th, $concept);
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
        $ThProprity = new \App\Models\RDF\ThProprity();

        $dtp = $ThProprity->find_prop($prop);

        $tp = $dtp['rg_range'];

        switch ($tp) {
            case 'Literal':
                if ($prop == 'prefLabel') {
                    $no = true;
                    $sx .= $this->form_field_level($id, $prop, $no);
                } else {
                    $sx .= $this->form_field_level($id, $prop);
                }
                break;
            case 'Text':
                $sx .= $this->form_link_concept_text($id, $prop);
                break;
            case 'Concept':
                /* CONCEPT */
                $sx .= $this->form_link_concept($id, $prop);
                break;
            case 'Reference':
                $Reference = new \App\Models\Thesa\Reference();
                $sx .= $Reference->form_link_concept_reference($id, $prop);
                break;
            default:
                echo 'Method not found <b>' . $tp . '</b>';
        }
        return $sx;
    }

    function form_link_concept_text($id,$prop)
        {
            $ThNotes = new \App\Models\RDF\ThNotes();
            $sx = $ThNotes->form_link_concept_text($id, $prop);
            return $sx;
        }

    function form_link_concept($id, $prop)
    {
        $prop = mb_strtolower($prop);
        switch ($prop) {
            case 'broader':
                $sx = $this->form_link_concept_broader($id);
                break;
            default:
                $sx = lang('thesa.method_not_found_concept') . ' ' . $prop;
                break;
        }
        return $sx;
    }

    function form_link_concept_broader($id)
    {
        $ThConceptPropriety = new \App\Models\RDF\ThConceptPropriety();
        $sx = $ThConceptPropriety->candidate_broader($id);
        return $sx;
    }

    function form_field_level($id, $prop, $nr = false)
    {
        $ThConcept = new \App\Models\RDF\ThConcept();
        $ThTermTh = new \App\Models\RDF\ThTermTh();

        $dc = $ThConcept->le($id);
        $th = $dc[0]['c_th'];

        if ($nr == true) {
            $langs = array();
            for ($r = 0; $r < count($dc); $r++) {
                $line = $dc[$r];
                if (strlen(trim($line['lg_code'])) > 0) {
                    $langs[$line['lg_code']] = 1;
                }
            }
            $dt = $ThTermTh->termNoUse($th, $langs);
        } else {
            $dt = $ThTermTh->termNoUse($th);
        }

        $sx = '';
        $sx .= '<div class="row">';
        if (count($dt) > 0) {
            $sx .= '<div class="col-md-3">';
            $sx .= '<input type="text" id="form_thesa_' . $prop . '_label" class="form-control">';
            $sx .= 'Filter-Form';
            $sx .= '</div>';
        }
        $sx .= '<div class="col-md-10">';
        if (count($dt) > 0) {

            /***** Select terms */
            $sx .= '<select id="form_thesa_prop_' . $prop . '_label" class="form-control" size=5>';
            for ($r = 0; $r < count($dt); $r++) {
                $line = $dt[$r];
                $sx .= '<option value="' . $line['id_term'] . '">' . $dt[$r]['term_name'] . ' (' . $dt[$r]['lg_code'] . ')</option>';
            }
            $sx .= '</select>';

            /**** Bobutton saver */
            $sx .= '<span class="btn btn-outline-primary" onclick="save_form_' . $prop . '();">' . lang('thesa.save') . '</span>';
        } else {

            /**** Message No Terms */
            $sx .= bsmessage(lang('thesa.no_terms'), 3);
        }

        /**** Bobutton cancel */
        $sx .= '<span class="btn btn-outline-danger ms-2" onclick="return_form_' . $prop . '();">' . lang('thesa.cancel') . '</span>';
        $sx .= '</div>';

        /**** JavaScript */
        $sx .= '<script>';
        $sx .= 'function return_form_' . $prop . '()' . cr();
        $sx .= '{' . cr();
        $sx .= ' var url = "' . PATH . '/admin/ajax_form_save/?id=' . $id . '&prop=' . $prop . '";' . cr();
        $sx .= ' $("#form_thesa_' . $prop . '").load(url);' . cr();
        $sx .= '}' . cr();

        $sx .= 'function save_form_' . $prop . '()' . cr();
        $sx .= '{' . cr();
        $sx .= '$vlr = $("#form_thesa_prop_' . $prop . '_label").val();' . cr();
        $sx .= ' var url = "' . PATH . '/admin/ajax_form_save/?id=' . $id . '&prop=' . $prop . '&vlr="+$vlr;' . cr();
        $sx .= ' $("#form_thesa_' . $prop . '").load(url);' . cr();
        $sx .= '}' . cr();
        $sx .= '</script>';
        return $sx;
    }

    function form_field_concept($id = 0, $prop = 0, $gr = 2)
    {
        $sx = '';
        $sx .= '<a href="#' . $id . '" class="ms-2" onclick="form_thesa_concept(\'' . $id . '\',\'' . $prop . '\');">' . bsicone('plusn', 18) . '</a>';
        return $sx;
    }

    function form_field_text($id = 0, $prop = 0, $gr = 2)
    {
        $sx = '';
        $sx .= '<a href="#' . $id . '" class="ms-2" onclick="form_thesa_text(\'' . $id . '\',\'' . $prop . '\');">' . bsicone('plus', 18) . '</a>';
        return $sx;
    }

    function form_field_reference($id = 0, $prop = 0, $gr = 2)
    {
        $sx = '';
        $sx .= '<a href="#' . $id . '" class="ms-2" onclick="form_thesa_reference(\'' . $id . '\',\'' . $prop . '\');">' . bsicone('plus', 18) . '</a>';
        return $sx;
    }

    function form_field($id = 0, $prop = 0, $gr = 2)
    {
        $sx = '';
        $sx .= '<a href="#' . $id . '" class="ms-2" onclick="form_thesa_label(\'' . $id . '\',\'' . $prop . '\');">' . bsicone('plus', 18) . '</a>';
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
        $sx .= '    $("#s2").load("' . (PATH . '/rdf/term/prefLabel/' . $id) . '");';
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

    function header_concept($dt)
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
        $prop_prefLabel = $ClassPropriety->Class($class);

        $idr = $ThConceptPropriety->register($th, $id_concept, $prop_prefLabel, 0, $id_term);

        /********************************************** Trava o Termos do Vocabulario */
        $Term = new \App\Models\RDF\ThTerm();
        $Term->term_block($id_term, $id_concept, $th);

        $sx = '<a href="' . PATH . 'v/' . $id_concept . '" class="btn btn-outline-secondary">' . 'thesa:c' . $id_term . '</a>' . ' created';
        return $sx . '<br>';
    }
}
