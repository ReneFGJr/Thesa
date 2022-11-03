<?php

namespace App\Models\Thesa;

use CodeIgniter\Model;

class Reference extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_references';
    protected $primaryKey       = 'id_ref';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_ref', 'ref_th', 'ref_cite', 'ref_content', 'ref_status', 'updated_at'
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

    function show($term)
    {
        $sx = '';
        $dt = $this
            ->join('thesa_references_concepts', 'rfc_ref = id_ref', 'left')
            ->where('rfc_concept', $term)
            ->orderby('ref_content', 'ASC')
            ->findAll();

        for ($r = 0; $r < count($dt); $r++) {
            $line = $dt[$r];

            $sx .= '<tr>';
            if ($r==0)
                {
                    $sx .= '<td class="col-2 small trh align-top">';
                    $sx .= lang('thesa.' . 'referece');
                    $sx .= '</td>';
                    $sx .= '<td class="lh-1 mb-3 ps-3 trh">';
                } else {
                    $sx .= '<td class="col-2 small align-top"></td>';
                    $sx .= '<td class="lh-1 pt-3 ps-3">';
                }
            $txt = $line['ref_content'];
            $sx .= $txt;
            $sx .= '</td>';
            $sx .= '</tr>';
        }
        return $sx;
    }

    function register($d1,$d2,$d3)
        {
            $th = get("id");
            $data['ref_th'] = $th;
            $data['ref_cite'] = get("cited");
            $data['ref_content'] = get("reference");
            $data['ref_status'] = 1;
            $this->set($data)->insert();
            return;
        }

    function form_link_concept_reference($id,$prop)
        {
            $ThConcept = new \App\Models\RDF\ThConcept();
            $dt = $ThConcept->find($id);
            $th = $dt['c_th'];
            $sx = "";
            $sx .= $this->form_link_list($th,$id);

            $sx .= '<button type="button" class="btn btn-outline-primary" onclick="togglet(\'references\');">'.lang('thesa.referente_new').'</button>';
            $sx .= '<button type="button" class="ms-2 btn btn-outline-warning" onclick="form_thesa_refecence_cancel('.$th.',\'reference\','.$id.')">Canclear</button>';
            $sx .= '<div id="status_references" style="display: none;">';
            $sx .= $this->form_link_concept_reference_new($th,$prop,$id);
            $sx .= '</div>';
            $sx .= '<div id="form_references">';
            $sx .= '</div>';
            return $sx;
        }

    function form_link_list($th,$id)
        {
            $ReferenceConcept = new \App\Models\Thesa\ReferenceConcept();
            $sx = '';
            $sx .= h(lang('thesa.select_reference'),5);

            $dt = $this
                ->join('thesa_references_concepts', '(rfc_ref = id_ref) and (rfc_concept='.$id.')','left')
                ->where('ref_th',$th)
                ->orderby('ref_content','ASC')
                ->findAll();

            for ($r=0;$r < count($dt);$r++)
                {
                    $line = $dt[$r];
                    $ref = trim($line['ref_content']);
                    if (strlen($ref) > 0)
                    {
                        $checked = '';
                        if ($line['rfc_ref']) { $checked = 'checked'; }
                        $sx .= '<p class="lh-1 mb-3">';
                        $sx .= '<input type="checkbox"
                                    '.$checked.'
                                    id="ref_' . $line['id_ref'] . '"
                                    onchange="thesa_reference_set('.$id.','.$line['id_ref'].',this);"
                                    name="ref_'.$line['id_ref'].'"
                                    value="1"> ';
                        $sx .= $line['ref_content']. '</p>';
                    }
                }
            return $sx;
        }

    function form_link_concept_reference_new($id,$prop,$term)
        {
            $sx = '';
            $sx .= '<table class="table">';
            $sx .= '<tr>';
            $sx .= '<th width="25%">'.msg('thesa.cited').'</th>';
            $sx .= '<th width="75%">'.msg('thesa.reference').'</th>';
            $sx .= '</tr>';

            $sx .= '<tr>';
            $sx .= '<td width="25%" class="small">' . msg('thesa.cited_exemple') . '</td>';
            $sx .= '<td width="75%" class="small">' . msg('thesa.reference_exemple') . '</td>';
            $sx .= '</tr>';

            $sx .= '<tr>';
            $sx .= '<td>';
            $sx .= form_input(array('name'=>'cited','id'=> 'cited','class'=>'form-control'));
            $sx .= '<button type="button" style="width: 100%;" class="mt-2 btn btn-outline-primary" onclick="form_thesa_refecence_save(' . $id . ',\'' . $prop . '\','.$term.')">'.msg('thesa.new_cited').'</button>';
            $sx .= '<button type="button" style="width: 100%;" class="mt-2 btn btn-outline-warning" onclick="form_thesa_refecence_cancel('.$id. ',\''.$prop. '\',' . $term . ')">' . msg('thesa.cancel_cited') . '</button>';
            $sx .= '</td>';

            $sx .= '<td>';
            $sx .= form_textarea(array('name' => 'reference', 'rows'=>5, 'id' => 'reference', 'class' => 'form-control'));
            $sx .= '</td>';

            $sx .= '</tr>';
            $sx .= '</table>';
            return $sx;
        }

    function form_field_reference($th = 0, $term = 0)
    {
        $sx = '';
        $sx .= '<span class="small text-danger"><i>' . lang('thesa.reference_without') . '</i></span>';
        return $sx;
    }

    function list_reference($term=0)
        {
            $sx = '';
            $Reference = new \App\Models\Thesa\Reference();
            $dt = $this
                ->join('thesa_references_concepts', 'rfc_ref = id_ref', 'left')
                ->where('rfc_concept', $term)
                ->orderby('ref_content', 'ASC')
                ->findAll();

            if (count($dt) > 0)
                {
                    for ($r=0;$r < count($dt);$r++)
                        {
                            $line = $dt[$r];
                            $sx .= '<p class="lh-1 mb-3">';
                            $sx .= $line['ref_content'];
                            $sx .= '</p>'.cr();
                        }
                    $sx .= '</table>';
                } else {
                    $sx .= '<span class="small text-danger"><i>' . lang('thesa.reference_without') . '</i></span>';
                }
            return $sx;
        }
}
