<?php

namespace App\Models\Thesa\Concepts;

use CodeIgniter\Model;

class Lists extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_concept_term';
    protected $primaryKey       = 'id_ct';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

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


    function terms_alphabetic($th, $lang = '', $other = '')
    {
        $Terms = new \App\Models\Thesa\Terms\Index();
        $data = array();
        $data['terms'] = $this->terms_alphabetic_index($th, $lang, $other);
        $data['terms'] .= $Terms->btn_add($th, 'full');

        $data['other'] = $other;

        $sx = view("Theme/Standard/List", $data);
        return $sx;
    }

    function terms_alphabetic_array($th)
    {
        $dt = $this->terms_alphabetic_index($th, '*', '');
        $dta = [];
        $dti = [];

        foreach ($dt as $id => $line) {
            $class = $line['vc_label'];
            $name = $line['term_name'];
            $lang = $line['lg_code'];
            $idc = $line['ct_concept'];
            switch ($class) {
                case 'prefLabel':
                    if (!isset($dta[$name])) {
                        $dta[$name] = [];
                    }
                    $dti[$idc]['name'] = $name;
                    break;
            }
        }

        foreach ($dt as $id => $line) {
            $class = $line['vc_label'];
            $name = $line['term_name'];
            $lang = $line['lg_code'];
            $idc = $line['ct_concept'];
            switch ($class) {
                case 'altLabel':
                    $idt = $dti[$idc];
                    $term = $idt['name'];
                    if (!isset($dta[$term])) {
                        $dta[$term] = [];
                    }
                    if (!isset($dta[$term]['desc'])) {
                        $dta[$term]['desc'] = '';
                    }
                    $dta[$term]['desc'] .= '<tr><td><tt>UP      </tt></td><td>' . $name . '</td></tr>';
                    break;
                default:
                    //echo '<br>==>' . $class;
                    break;
            }
        }

        /******************** Notas */
        $thesa_notes = new \App\Models\RDF\ThNotes();
        $notes = $thesa_notes->notes_array($th);
        foreach ($dti as $id => $name) {
            if (isset($notes[$id])) {
                foreach ($notes[$id] as $note => $value) {
                    if (!isset($dta[$term]['desc'])) {
                        $dta[$term]['desc'] = '';
                    }
                    $dta[$term]['desc'] .= '<tr>';
                    $dta[$term]['desc'] .= '<td valign="top" width="100px">';
                    $dta[$term]['desc'] .= '<tt>NOTE</tt><br>';
                    $dta[$term]['desc'] .= '<span class="small">('.$note. ')</span>';
                    $dta[$term]['desc'] .= '</td>';
                    $dta[$term]['desc'] .= '<td class="small">' . $value . '</td></tr>';
                }
            }
        }
        return $dta;
    }

    function terms_alphabetic_index($th, $lang = '', $other = '')
    {
        /********* Language */
        $Language = new \App\Models\Thesa\Language();
        $langs = $Language->getLanguage($th);

        if ($lang == '') {
            $lang = $Language->getLang($langs[0]['lg_code']);
        }
        $lang = $Language->setting($lang);

        $sx = '';

        $Thesa = new \App\Models\Thesa\Index();
        $Terms = new \App\Models\Thesa\Terms\Index();

        $th = $Thesa->getThesa();

        $this
            ->join('thesa_terms', 'ct_literal = id_term', 'left')
            ->join('language', 'term_lang = id_lg', 'left')
            ->join('owl_vocabulary_vc', 'id_vc = ct_propriety', 'left')
            ->where('ct_th', $th)
            ->where('ct_literal > 0');
        if ($lang != '*') {
            $this->where('lg_code', $lang);
        }
        $dt = $this->orderBy('term_name')
            ->findAll();

        /******************* EMPTY */
        if (count($dt) == 0) {
            return $this->terms_empty($th);
        }

        if ($lang == '*') {
            return $dt;
        }

        $xlt = '';
        foreach ($dt as $id => $line) {

            ######################################## A ########
            $lt = mb_strtoupper(substr(ascii($line['term_name']), 0, 1));
            if ($lt != $xlt) {
                $sx .= '<div class="abrev">= = ' . $lt . ' = =</div>';
                $xlt = $lt;
            }
            $type = $line['vc_label'];

            switch ($type) {
                case 'prefLabel':
                    $sx .= '<div onclick="load_content(' . $line['ct_concept'] . ')"
                                class="full prefLabel bghover handle"
                                id= "colm_' . $line['id_term'] . '" draggable="true" ondragstart="dragStart (event)">' .
                        $line['term_name'] . '</div>';
                    break;
                case 'altLabel':
                    $sx .= '<div onclick="load_content(' . $line['ct_concept'] . ')"
                                class="full altLabel bghover ps-2 handle"
                                id= "colm2">' .
                        $line['term_name'] . '</div>';
                    break;
            }
        }
        return $sx;
    }

    function terms_empty($th)
    {
        $Terms = new \App\Models\Thesa\Terms\Index();
        $sx = '';
        $sx .= bsmessage(lang('thesa.term_empty'), 3);
        $sx .= $Terms->btn_add($th, 'full');

        /************************************ Termos Candidatos */
        $ThTerm = new \App\Models\Thesa\Terms\Index();
        $Candidates = $ThTerm->caditate_prefLabel($th, array(), $th);

        //pre($Candidates,false);

        return $sx;
    }
}

/*
$sx .= '<div id="colm3" ondrop="dragDrop(event)" ondragover ="allowDrop(event)" class="btn btn-primary"></div>';
*/