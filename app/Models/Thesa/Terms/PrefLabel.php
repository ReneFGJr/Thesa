<?php

namespace App\Models\Thesa\Terms;

use CodeIgniter\Model;

class PrefLabel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'preflabels';
    protected $primaryKey       = 'id';
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

    function register($id, $idt, $th)
    {
        $ThConcept = new \App\Models\Thesa\Concepts\Index();
        $dt = $ThConcept->le($id);
        $Language = new \App\Models\Thesa\Language();
        $langs = $Language->extract_languages($dt);
        $idt = round($idt);

        $ThTerm = new \App\Models\Thesa\Terms\Index();
        $dt = $ThTerm->le($idt);
        if (count($dt) > 0) {
            $lang = $dt['lg_code'];
            if (isset($langs[$lang])) {
                return lang("Thesa.erro_form_prefLabel_already");
            } else {
                /* Salvar */
                $prop_prefLabel = 'prefLabel';
                $VocabularyVC = new \App\Models\RDF\Ontology\VocabularyVC();
                $prop = $VocabularyVC->findClass($prop_prefLabel);

                $ThConceptPropriety = new \App\Models\RDF\ThConceptPropriety();
                $ThConceptPropriety->register($th, $id, $prop, 0, 0, $idt);

                /***************************** Look Term in Thesauro */
                $ThTermTh = new \App\Models\RDF\ThTermTh();
                $ThTermTh->update_term_th($idt, $th, $id);

                return wclose();
            }
        }
    }

    function form($id)
    {
        $Thesa = new \App\Models\Thesa\Index();
        $th = $Thesa->getThesa();

        $sx = '';
        $erro = '';
        $act = get("action");
        if ($act != '') {
            $idt = sonumero('0' . get("term"));
            if ($idt > 0) {
                $erro = $this->register($id, $idt, $th);
            } else {
                $erro = '<span class="text-danger">' . lang('Thesa.erro_form_prefLabel') . '</span>';
            }
        }
        $ThConcept = new \App\Models\Thesa\Concepts\Index();
        $dt = $ThConcept->le($id);

        $Language = new \App\Models\Thesa\Language();
        $langs = $Language->extract_languages($dt);

        /************************************ Termos Candidatos */
        $ThTerm = new \App\Models\Thesa\Terms\Index();
        $Candidates = $ThTerm->caditate_prefLabel($id, $langs, $th);

        $op = [];
        foreach ($Candidates as $id => $line) {
            $op[$line['id_term']] = $line['term_name'] . '@' . $line['lg_code'];
        }

        $sx .= h(lang("Thesa.prefLabel"));
        $sx .= form_open();
        $sx .= form_label(lang('Thesa.select_a_term'));
        $sx .= form_dropdown(array('name' => 'term', 'options' => $op, 'class' => 'form-control', 'size' => 12));
        $sx .= form_submit(array('name' => 'action', 'value' => lang("Thesa.save"))) . '<br>';
        $sx .= form_label($erro);
        $sx .= form_close();

        $sx = bs(bsc($sx, 12));

        return $sx;
    }
}
