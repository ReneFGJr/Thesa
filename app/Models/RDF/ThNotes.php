<?php

namespace App\Models\RDF;

use CodeIgniter\Model;

class ThNotes extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_notes';
    protected $primaryKey       = 'id_nt';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_nt', 'nt_concept', 'nt_prop', 'nt_lang', 'nt_content', 'updated_at'
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

    function notes_array($th)
    {
        $dt = $this
            ->join('thesa_concept', 'nt_concept = c_concept')
            //->join('owl_vocabulary_vc', 'nt_prop = id_vc')
            ->join('thesa_property', 'nt_prop = id_p')
            ->where('c_th', $th)
            ->findAll();
        $da = [];
        foreach ($dt as $id => $line) {
            $idc = $line['c_concept'];
            //$class = $line['vc_label'];
            $class = $line['p_name'];
            if (!isset($da[$idc])) {
                $da[$idc] = [];
            }

            if (!isset($da[$idc][$class])) {
                $da[$idc][$class] = '';
            }

            $da[$idc][$class] .= $line['nt_content'].'<br/>';
        }
        return ($da);
    }

    function show($id)
    {
        $sx = '';
        $dt = $this
            ->join('thesa_property', 'id_p = nt_prop', 'left')
            ->where('nt_concept', $id)->findAll();
        for ($r = 0; $r < count($dt); $r++) {
            $line = $dt[$r];
            //pre($line);
            $sx .= '<tr>';
            $sx .= '<td class="col-2 small trh align-top">';
            $sx .= lang('thesa.' . $line['p_name']);
            $sx .= '</td>';
            $sx .= '<td class="lh-1 mb-3 trh ps-3 ">';
            $txt = troca($line['nt_content'], chr(10), '<br><br>');
            $txt = troca($txt, '<br><br><br><br>', '<br><br>');
            $sx .= $txt;
            $sx .= '</td>';
            $sx .= '</tr>';
        }
        return $sx;
    }

    function form($prop, $id, $reg = 'new')
    {
        $sx = view('Theme/Standard/Logo');

        if ($reg == '') {
            $reg = 'new';
        }

        $lang = 1;
        $text = '';

        $sx .= $this->form_link_concept_text($id, $prop, $reg);
        return $sx;
    }

    function register($concept, $prop, $text, $lang)
    {
        if (trim($text) == '') {
            echo "Description empty";
            exit;
        }
        $data['nt_concept'] = $concept;
        $data['nt_prop'] = $prop;
        $data['nt_lang'] = $lang;
        $data['nt_content'] = $text;
        $data['updated_at'] = date("Y-m-d H:i:s");

        $this->set($data)->insert();
        return true;
    }

    function delete_note()
    {
        $Thesa = new \App\Models\Thesa\Thesa();
        $th = $Thesa->getThesa();
        $sx = '';
        $id = get("id");
        $prop = get("prop");

        $dt = $this->find($id);

        if ($dt != '') {
            $concept = $dt['nt_concept'];
            $this->where('id_nt', $id);
            $this->delete();
            $sx .= $this->list($concept, $prop);
        } else {
            $sx .= bsmessage('error: Note not found', 3);
        }

        return $sx;
    }

    function form_link_concept_text($id, $prop, $reg = 'new')
    {
        $Thesa = new \App\Models\Thesa\Index();
        $th = $Thesa->getThesa();

        $Language = new \App\Models\Thesa\Language();
        $act = get("action");
        $txt = get("text");
        $lang = get("lg");
        $text = get("text");
        if ($act != '') {
            if ($txt != '') {
                $this->text_save();
                exit;
            }
        } else {
            $dt = $this->find($reg);
            if ($dt != '') {
                $text = $dt['nt_content'];
                $lang = $dt['nt_lang'];
            } else {
                $lang = 3;
            }
        }

        /************************* LANG */
        $langs = $Language->lang_form($th, $lang);

        $sx = '';
        $sx .= lang('thesa.description_input');
        $sx .= '(' . $reg . ')';
        $sx .= form_open();
        $sx .= form_textarea(array('name' => 'text', 'id' => 'text', 'class' => 'form-control', 'rows' => 8, 'value' => $text));
        $sx .= form_hidden(array('reg' => $reg, 'id' => $id, 'prop' => $prop));
        $sx .= form_label(lang('thesa.language')) . ': ';

        $sx .= $langs;
        $sx .= '<br/>';
        $sx .= '<br/>';
        $sx .= form_submit(array('name' => 'action', 'value' => lang('thesa.save')));
        $sx .= $this->btn_close();
        $sx .= form_close();

        return bs(bsc($sx, 12));
    }

    function btn_close()
    {
        $sx = '';
        $sx .= '<a href="#" onclick="wclose();" class="btn btn-outline-warning">' . lang("thesa.close") . '</a>';
        return $sx;
    }

    function text_save()
    {
        $sx = '';

        $id = get('id');
        $prop = get('prop');
        $reg = get('reg');
        $text = get('text');
        $lang = get("lg");

        /*
        echo "<br>ID==>".$id;
        echo "<br>PROP==>".$prop;
        echo "<br>REG==>".$reg;
        echo "<br>TEXT==>".$text;
        echo "<br>LANG==>" . $lang;
        exit;
        */
        $text = strip_tags($text);

        if ($reg == 'new') {
            $ThProprity = new \App\Models\RDF\ThProprity();
            $prop = $ThProprity->find_prop($prop);
            $prop = $prop['id_p'];
            $this->register($id, $prop, $text, $lang);
        } else {
            $data['nt_content'] = $text;
            $data['nt_lang'] = $lang;
            $data['updated_at'] = date("Y-m-d H:i:s");
            $this->where('id_nt', $reg);
            $this->set($data);
            $this->update();
        }
        echo wclose();
        exit;
    }

    function list($concept, $prop, $edit = true)
    {
        $sx = '';
        $this
            ->join('thesa_property', 'id_p = nt_prop', 'left')
            ->where('nt_concept', $concept);
        if (round($prop) > 0) {
            $this->where('nt_prop', $prop);
        } else {
            $this->where('p_name', $prop);
        }
        $dt = $this->findAll();


        for ($r = 0; $r < count($dt); $r++) {
            $line = $dt[$r];
            $url = PATH . '/admin/notes/scopeNote/' . $line['nt_concept'] . '/' . $line['id_nt'];
            $sx .= '<p class="mb-2 lh-1 notes">';
            if ($edit == true) {
                $sx .= '<span style="cursor: pointer;" onclick="newwin(\'' . $url . '\');">';
                $sx .= bsicone('edit');
                $sx .= '</span>';

                /* Remove */
                $sx .= '<span style="color: red; cursor: pointer;" onclick="text_delete(' . $line['id_nt'] . ',\'' . $line['p_name'] . '\');">';
                $sx .= bsicone('trash', 16);
                $sx .= '</span>';
            }

            $sx .= troca($line['nt_content'], chr(13), '<br/>');
            $sx .= '</p>';
        }
        return $sx;
    }
}
