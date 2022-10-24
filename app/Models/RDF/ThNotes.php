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
            echo '==>'.$th;
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
                $sx .= bsmessage('error: Note not found',3);
            }

            return $sx;
        }

    function form_link_concept_text($id, $prop,$reg='new',$lang='',$text='')
    {
        $sx = '';
        $sx .= lang('thesa.description_input');
        $sx .= '('.$reg.')';
        $sx .= form_textarea(array('name' => 'text_' . $prop, 'id' => 'text_' . $prop, 'class' => 'form-control', 'rows' => 8,'value'=>$text));
        $sx .= '<input type="hidden" name="' . 'text_rg_' . $prop . '" id="' . 'text_rg_' . $prop . '" value="'.$reg.'">';
        if ($reg == 'new')
            {
                /*********************** Novo conteúdo */
                $sx .= '<span class="btn btn-outline-primary me-2"
                        onclick="save_text(' . $id . ',\'' . $prop . '\',\'' . $reg . '\');">'
                    . lang('thesa.save') . '</span>';
            } else {
                /*********************** Editar conteúdo */
                $sx .= 'Id==>'.$id;
                $sx .= '<span class="btn btn-outline-primary me-2"
                            onclick="save_text(' . $id . ',\'' . $prop . '\',\'' . $reg . '\');">'
                    . lang('thesa.save') . '</span>';
            }

        $sx .= '<span class="btn btn-outline-danger"
                    onclick="close_text(' . $id . ',\'' . $prop . '\');">'
                    . lang('thesa.cancel') . '</span>';
        return $sx;
    }

    function text_save()
    {
        $sx = '';

        $id = get('id');
        $prop = get('prop');
        $reg = get('reg');
        $text = get('text');

        /*
        echo "<br>ID==>".$id;
        echo "<br>PROP==>".$prop;
        echo "<br>REG==>".$reg;
        echo "<br>TEXT==>".$text;
        */

        if ($reg == 'new')
            {
                $ThProprity = new \App\Models\RDF\ThProprity();
                $prop = $ThProprity->find_prop($prop);
                $prop = $prop['id_p'];
                $this->register($id, $prop, $text, 'pt_BR');
            } else {
                $data['nt_content'] = $text;
                $data['updated_at'] = date("Y-m-d H:i:s");
                $this->where('id_nt', $reg);
                $this->set($data);
                $this->update();
            }
        $sx = $this->list($id, $prop);
        echo $sx;
        exit;
    }

    function ajax_text_edit()
        {
            $id = get("id");
            echo '==>'.$id;
            $dt = $this
                ->join('thesa_property', 'id_p = nt_prop', 'left')
                ->find($id);
            $sx = $this->form_link_concept_text($dt['nt_concept'], $dt['p_name'], $dt['id_nt'],$dt['nt_lang'],$dt['nt_content']);
            return $sx;
            exit;
        }

    function list($concept, $prop)
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

            $sx .= '<p class="mb-2">';
            $sx .= '<span style="cursor: pointer;" onclick="text_edit(' . $line['id_nt'] . ',\'' . $line['p_name'] . '\');">';
            $sx .= bsicone('edit');
            $sx .= '</span>';

            /* Remove */
            $sx .= '<span style="color: red; cursor: pointer;" onclick="text_delete('.$line['id_nt'].',\''. $line['p_name'].'\');">';
            $sx .= bsicone('trash',16);
            $sx .= '</span>';

            $sx .= $line['nt_content'];
            $sx .= '</p>';
        }
        return $sx;
    }
}
