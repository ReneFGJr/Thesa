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

    var $tree = array();

    function terms_alphabetic($th, $lang = '', $other = '')
    {
        $Terms = new \App\Models\Thesa\Terms\Index();
        $data = array();
        $data['terms'] = $this->terms_alphabetic_index($th, $lang, $other);
        $data['terms'] .= $Terms->btn_add($th, 'full');
        $data['th'] = $th;
        $data['urls'] = anchor(PATH . '/tz/' . $th, lang('thesa.index_systematic'));

        $data['other'] = $other;

        $sx = view("Theme/Standard/List", $data);
        return $sx;
    }

    function terms_systematic($th, $lang = '', $other = '')
    {
        $Terms = new \App\Models\Thesa\Terms\Index();
        $data = array();
        $data['terms'] = $this->terms_systematic_index($th, $lang, $other);
        $data['terms'] .= $Terms->btn_add($th, 'full');
        $data['th'] = $th;
        $data['urls'] = anchor(PATH . '/th/' . $th, lang('thesa.index_alphabetic'));

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

        /******************** TG */
        $Broader = new \App\Models\Thesa\Relations\Broader();
        $dtb = $Broader
            ->select('t1.term_name as t1, t2.term_name as t2')
            ->join('thesa_concept as tc1', 'tc1.c_concept = b_concept_boader', 'right')
            ->join('thesa_concept_term as tct1', 'tct1.ct_concept = tc1.c_concept and tct1.ct_literal <> 0')
            ->join('thesa_terms as t1', 't1.id_term = tct1.ct_literal')

            ->join('thesa_concept as tc2', 'tc2.c_concept = b_concept_narrow', 'right')
            ->join('thesa_concept_term as tct2', 'tct2.ct_concept = tc2.c_concept and tct2.ct_literal <> 0')
            ->join('thesa_terms as t2', 't2.id_term = tct2.ct_literal')

            ->where('b_th',$th)
            ->findAll();

        foreach ($dtb as $id => $line) {
            $term = $line['t2'];
            $term_2 = $line['t1'];
            if (!isset($dta[$term]['desc'])) {
                $dta[$term]['desc'] = '';
            }
            $dta[$term]['desc'] .= '<tr>';
            $dta[$term]['desc'] .= '<td valign="top" width="100px">';
            $dta[$term]['desc'] .= '<tt>TG</tt><br>';
            $dta[$term]['desc'] .= '</td>';
            $dta[$term]['desc'] .= '<td class="small">' . $term_2 . '</td></tr>';
        }

        foreach($dtb as $id=>$line)
            {
                $term = $line['t1'];
                $term_2 = $line['t2'];
                if (!isset($dta[$term]['desc'])) {
                    $dta[$term]['desc'] = '';
                }
                $dta[$term]['desc'] .= '<tr>';
                $dta[$term]['desc'] .= '<td valign="top" width="100px">';
                $dta[$term]['desc'] .= '<tt>TE</tt><br>';
                $dta[$term]['desc'] .= '</td>';
                $dta[$term]['desc'] .= '<td class="small">' . $term_2 . '</td></tr>';
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
                    $dta[$term]['desc'] .= '<span class="small">(' . $note . ')</span>';
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
                    $sx .= '<div onclick="load_content(' . $line['ct_concept'] . ',\''.PATH.'\')"
                                class="full prefLabel bghover handle"
                                id= "colm_' . $line['id_term'] . '" draggable="true" ondragstart="dragStart (event)">' .
                        ''.$line['term_name'] . ''.'</div>';
                    break;
                case 'altLabel':
                    $sx .= '<div onclick="load_content(' . $line['ct_concept'] . ',\'' . PATH . '\')"
                                class="full altLabel bghover ps-2 handle"
                                id= "colm2">' .
                        '' . $line['term_name'] . '' . '</div>';
                    break;
            }
        }
        return $sx;
    }

    function terms_systematic_index($th, $lang='', $other='')
    {
        $Broader = new \App\Models\Thesa\Relations\Broader();
        $Broader->where('b_th', $th);
        $dt = $Broader->findAll();
        $tree = array();
        $c = [];
        $terms = [];
        foreach ($dt as $id => $line) {
            $b = $line['b_concept_boader'];
            $n = $line['b_concept_narrow'];
            $terms[$b] = '';
            $terms[$n] = '';

            if (!isset($tree[$b])) {
                $tree[$b] = [];
            }
            $tree[$b][$n] = 1;

            if (!isset($c[$n])) {
                $c[$n] = $b;
            }
        }
        $this->tree = $tree;
        $unset = [];


        /*********************************** TERMOS */
        $Concepts = new \App\Models\Thesa\Concepts\Index();
        $Concepts->select('c_concept as idc, term_name');
        $Concepts->join('thesa_concept_term', 'c_concept = ct_concept and ct_literal <> 0');
        $Concepts->join('thesa_terms', 'id_term = ct_literal');
        $Concepts->where('ct_th',$th);
        $dterm = $Concepts->findAll();
        foreach($dterm as $id=>$line)
            {
                $idc = $line['idc'];
                $name = $line['term_name'];
                $terms[$idc] = $name.'#'.$idc;
            }
        for ($r = 0; $r < 20; $r++) {
            foreach ($this->tree as $id => $line) {
                foreach ($line as $idl => $vlr) {
                    if (isset($this->tree[$idl])) {
                        $this->tree[$id][$idl] = $this->tree[$idl];
                        $unset[$idl] = 1;
                    }
                }
            }
        }
        foreach ($unset as $id => $null) {
            unset($this->tree[$id]);
        }

        $sx = '';

        foreach ($this->tree as $id => $line) {
            $sx .= $terms[$id].'<br>';
            $nv = 0;
            if(is_array($line))
                {
                    $sx .= $this->t($line,$terms,$nv);
                }
        }
        $ln = explode('<br>',$sx);
        $sx ='';

        foreach($ln as $id=>$line)
            {
                $t = explode('#',$line);
                if (isset($t[1]))
                {
                $sx .= '<div onclick="load_content(' . $t[1] . ',\'' . PATH . '\')"
                                    class="full prefLabel bghover handle"
                                    id= "colm_' . $t[1] . '" draggable="true" ondragstart="dragStart (event)">' .
                '<tt>'.$t[0].'</tt>'. '</div>';

                unset($terms[$t[1]]);
                }
            }

        $sa = '';

        if (count($terms) > 0)
            {
                foreach($terms as $id=>$line)
                    {
                        $t = explode('#', $line);
                        $sa .= '<div onclick="load_content(' . $t[1] . ',\'' . PATH . '\')"
                                            class="full prefLabel bghover handle text-danger"
                                            id= "colm_' . $t[1] . '" draggable="true" ondragstart="dragStart (event)">' .
                        '<tt>' . $t[0] . '</tt>' . '</div>';
                    }
            }
        return $sa.$sx;

    }

    function t($line,$terms,$nv)
        {
            $sx = '';
            if (is_array($line))
            {
                foreach($line as $idx=>$linex)
                    {
                        $sx .= str_repeat('.',$nv+1).$terms[$idx]. '<br>';
                        if (is_array($line)) {
                            $sx .= $this->t($linex, $terms, $nv+1);
                        }
                    }
            }
            return $sx;
        }


    function terms_empty($th)
    {
        $Terms = new \App\Models\Thesa\Terms\Index();
        $sx = '';
        $sx .= bsmessage(lang('thesa.term_empty'), 3);

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