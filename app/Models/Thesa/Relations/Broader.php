<?php

namespace App\Models\Thesa\Relations;

use CodeIgniter\Model;

class Broader extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_broader';
    protected $primaryKey       = 'id_b';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_b', 'b_th', 'b_concept_boader',
        'b_concept_narrow', 'b_concept_master', 'updated_at'
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

    function show($id,$edit=false)
    {
        $sx = '';
        $sx .= $this->broader($id,$edit);
        $sx .= $this->narrow($id, $edit);
        return $sx;
    }

    function broader($id,$edit=false)
    {
        $dt = $this
            ->join('thesa_concept_term', 'ct_concept = b_concept_boader and ct_literal <> 0')
            ->join('thesa_terms', 'id_term = ct_literal')
            ->where('b_concept_narrow', $id)
            ->findAll();
        $sx = '';
        foreach ($dt as $id => $line) {
            $icone = '';
            if ($edit == true) {
                $icone = '<span class="text-danger me-2">' . bsicone('trash', 18) . '</span>';
            }
            $sx .= '<span class="ms-3 prefLabel">' . $icone . anchor(PATH . '/v/' . $line['ct_concept'], $line['term_name']) . '</span>';
            $sx .= '<br>';        }
        return $sx;
    }

    function narrow($id,$edit=false)
    {
        $dt = $this
            ->join('thesa_concept_term', 'ct_concept = b_concept_narrow and ct_literal <> 0')
            ->join('thesa_terms', 'id_term = ct_literal')
            ->where('b_concept_boader', $id)
            ->findAll();
        $sx = '';
        foreach ($dt as $id => $line) {
            $icone = '';
            if ($edit == true) {
                $icone = '<span class="text-danger me-2">' . bsicone('trash', 18) . '</span>';
            }
            $sx .= '<span class="ms-3 prefLabel">'.$icone.anchor(PATH . '/v/' . $line['ct_concept'], $line['term_name']). '</span>';
            $sx .= '<br>';
        }
        return $sx;
    }

    function register($th, $c1, $c2, $master)
    {
        $sx = '';

        $data['b_th'] = $th;
        $data['b_concept_boader'] = $c1;
        $data['b_concept_narrow'] = $c2;
        $data['b_concept_master'] = $master;
        $data['updated_at'] = date("Y-m-d H:i:s");

        $dt = $this
            ->where('b_concept_narrow', $c2)
            ->findAll();

        if (count($dt) == 0) {
            $this->set($data)->insert();
            $sx .= "SAVED";
            $sx .= wclose();
        } else {
            $sx .= bsmessage("Já existe um TG", 3);
        }
        return $sx;
    }

    function exist_broader($id,$th)
        {
        $dt = $this
            ->where('b_concept_narrow',$id)
            ->where('b_th',$th)
            ->findAll();
        if (count($dt) == 0)
            {
                return false;
            }
        return true;
        }

    function form($d1, $d2, $d3, $d4)
    {
        $Concept = new \App\Models\Thesa\Concepts\Index();
        $dt = $Concept->le($d1);
        $th = $dt[0]['c_th'];

        $Collaborations = new \App\Models\Thesa\Collaborators();
        $access = $Collaborations->own($th);

        if ($access) {
            if ($this->exist_broader($d1,$th))
                {
                    $sx = bsmessage(lang('thesa.already_broader'),3);
                    return $sx;
                }

            $dc = $this->canditates_broader($th, $d1);
            $sx = '';
            $sa = '<select id="concept" class="form-control" size=15">';
            foreach ($dc as $id => $line) {
                $sa .= '<option value="' . $line['c_concept'] . '">';
                $sa .= $line['term_name'];
                $sa .= '</option>';
                $sa .= cr();
            }
            $sa .= '</select>';

            $sa .= '<script>
                        $( "select" )
                        .change(function () {
                            var str = "";
                            $( "select option:selected" ).each(function() {
                            str += $( this ).val();
                            });
                            if(str != "")
                                {
                                    url = "' . PATH . 'ts/" + str;
                                    $("#desc").load(url);
                                    $("#idc").val(str);
                                    $("#confirm").show();
                                }
                        });
                    </script>' . cr();

            $act = get("action");
            if ($act != '') {
                $idc1 = get('idc');
                $idc2 = get('idb');
                $sx .= $this->register($th, $idc1, $idc2, 0);
            }


            $sb = '<div id="desc">Selecione o conceito</div>';
            $sb .= '<div id="confirm" style="display: none;">';
            $sb .= form_open();
            $sb .= '<input type="text" value="" id="idc" name="idc">';
            $sb .= form_hidden(array('th' => $th, 'idb' => $d1));
            $sb .= form_submit('action', lang('thesa.save'));
            $sb .= form_close();
            $sb .= '</div>';

            $sx .= bs(bsc($sa, 6) . bsc($sb, 6));
        } else {
            $sx = wclose();
        }
        return $sx;
    }

    function canditates_broader($th, $id)
    {
        $tl1 = $this
            ->select('b_concept_narrow as idc')
            ->join('thesa_concept', 'c_concept = b_concept_boader', 'right')
            ->where('b_concept_boader',$id)
            ->where('c_th', $th)
            ->findAll();

        $tl2 = $this
            ->select('b_concept_boader as idc')
            ->join('thesa_concept', 'c_concept = b_concept_narrow', 'right')
            ->where('b_concept_narrow', $id)
            ->where('c_th', $th)
            ->findAll();

        /********************************/
        $Concept = new \App\Models\Thesa\Concepts\Index();
        $Concept->select('term_name, c_concept, ct_th');
        $Concept->join('thesa_concept_term', 'ct_concept = c_concept and ct_literal <> 0');
        $Concept->join('thesa_terms','id_term = ct_literal');
        foreach ($tl1 as $idx => $xline) {
            $Concept->where('c_concept <> ' . $xline['idc']);
        }
        foreach ($tl2 as $idx => $xline) {
            $Concept->where('c_concept <> ' . $xline['idc']);
        }
        $Concept->orderby('term_name');
        $dt = $Concept->findAll();
        return $dt;
        pre($dt);

    }
}
