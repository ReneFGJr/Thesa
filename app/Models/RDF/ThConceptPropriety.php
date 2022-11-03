<?php

namespace App\Models\RDF;

use CodeIgniter\Model;

class ThConceptPropriety extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_concept_term';
    protected $primaryKey       = 'id_ct';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_ct', 'ct_concept', 'ct_th',
        'ct_literal', 'ct_use', 'ct_propriety',
        'ct_resource', 'ct_concept_2', 'ct_concept_2_qualify'
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

    function broader_save()
    {
        $ThConcept = new \App\Models\RDF\ThConcept();
        $ThProprity = new \App\Models\RDF\ThProprity();

        $id = get('id');
        $prop = get('prop');
        $concept = get('concept');
        $qualify = round('0'.get('form_thesa_'.$prop.'_qualifier'));

        $dtc = $ThConcept->find($id);
        $th = $dtc['c_th'];

        $prop = $ThProprity->find_prop_id($prop);

        /* CHECK LOOP */
        //$loop = $this->loop_check($th, $prop, $concept);

        $da = $this
            ->where('ct_th', $th)
            ->where('ct_concept', $id)
            ->where('ct_concept_2', $concept)
            ->findAll();
        if (count($da) == 0) {
            $da['ct_th'] = $th;
            $da['ct_concept'] = $id;
            $da['ct_concept_2'] = $concept;
            $da['ct_propriety'] = $prop;
            $da['ct_resource'] = 0;
            $da['ct_use'] = 0;
            $da['ct_literal'] = 0;
            $da['ct_concept_2_qualify'] = $qualify;
            $this->set($da)->insert();
        } else {
            echo "OPS";
        }
    }

    function candidate_broader($id)
    {
        $ThConcept = new \App\Models\RDF\ThConcept();
        $dtc = $ThConcept->find($id);
        $th = $dtc['c_th'];

        $dt = $this
            ->join('thesa_terms', 'id_term = ct_literal', 'inner')
            ->where('ct_th', $th)
            ->where('ct_concept !=', $id)
            ->orderBy('term_name', 'ASC')
            ->findAll();

        $sx = '<select id="select_broader" name="select_broader" class="form-control" size=6>';
        for ($r = 0; $r < count($dt); $r++) {
            $line = $dt[$r];
            $idc = $line['ct_concept'];
            $term = $line['term_name'];
            $sx .= '<option value="' . $idc . '">' . $term . '</option>';
        }
        $sx .= '</select>';

        $sx .= '<span class="btn btn-outline-primary" onclick="save_ajax_broader(' . $id . ',\'broader\');">';
        $sx .= 'SAVE BROADER';
        $sx .= '</span>';
        return $sx;
    }


    function ajax_term_delete()
    {
        $ThTermTh = new \App\Models\RDF\ThTermTh();
        $ThConcept = new \App\Models\RDF\ThConcept();


        $id = get("id");
        $prop = get("prop");
        $dt = $this->find($id);

        $th = $dt['ct_th'];
        $idt = $dt['ct_literal'];
        $idc = $dt['ct_concept'];

        /* Term FREE */
        $dt['term_th_concept'] = 0;
        $ThTermTh->set($dt)->where('term_th_term', $idt)->where('term_th_thesa', $th)->update();

        /* Delete */
        $this->where('id_ct', $id)->delete();

        echo $ThConcept->list_concepts_terms($idc, $prop);
        exit;
    }


    function register($th, $concept, $prop, $qualy, $resource, $literal)
    {
        $data['ct_concept'] = $concept;
        $data['ct_propriety'] = $prop;
        $data['ct_th'] = $th;
        $data['ct_literal'] = $literal;
        $data['ct_resource'] = $resource;
        $data['ct_concept_2_qualify'] = $qualy;
        $data['ct_use'] = 0;

        $da = $this
            ->where('ct_th', $th)
            ->where('ct_concept', $concept)
            ->where('ct_propriety', $prop)
            ->where('ct_propriety', $prop)
            ->where('ct_resource', $literal)
            ->findAll();

        if (count($da) == 0) {
            $id = $this->set($data)->insert();
        } else {
            $id = $da[0]['id_ct'];
        }
        return $id;
    }
}
