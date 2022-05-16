<?php

namespace App\Models\Tools;

use CodeIgniter\Model;

class Index extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'indices';
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

    function index($d1, $d2, $d3, $d4)
    {
        $sx = h('thesa.tools' . $d1);

        $sa = $this->tools_menu($d1, $d2, $d3);
        $sb = $this->tools_itens($d1, $d2, $d3);

        $sx .= bs(bsc($sa, 3) . bsc($sb, 9));

        $sx = bs(bsc($sx, 12));
        return $sx;
    }

    function tools_menu($ac = '')
    {
        $sx = '';

        $sx .= '<ul class="nav flex-column">';
        $it = array('inport_description', 'inport_csv', 'inport_skos');
        if ($ac == '') {
            $ac = $it[0];
        }

        for ($r = 0; $r < count($it); $r++) {
            if ($it[$r] == $ac) {
                $cl = 'disabled';
            } else {
                $cl = '';
            }
            $sx .= '<li class="mb-2 h5 nav-item text-end"><a href="' . PATH . MODULE . 'tools/' .  $it[$r] . '" class="nav-link ' . $cl . '">' . lang('thesa.' . $it[$r]) . '</a></li>';
        }
        $sx .= '</ul>';
        return $sx;
    }

    function tools_itens($ac = '')
    {
        if ($ac == '') {
            $ac = 'inport_description';
        }
        switch ($ac) {
            default:
                $sx = 'ERROR';
                break;
            case 'inport_csv':
                $sx = $this->inport_csv($ac);
                break;
            case 'relations_custom':
                $sx = $this->relations_custom($ac);
                break;
        }
        return ($sx);
    }

    function inport_csv($id, $ac = '')
    {
        $sx = '';
        if ((isset($_FILES['file_csv']['name'])) and (strlen($_FILES['file_csv']['name']) > 0)) {
            $ThThesaurus = new \App\Models\Thesaurus\ThThesaurus();
            $ThLiteral = new \App\Models\Thesaurus\ThLiteral();
            $ThRelations = new \App\Models\Thesaurus\ThRelations();

            $lang = '';

            $th = $ThThesaurus->getTh();

            $txt = file_get_contents($_FILES['file_csv']['tmp_name']);
            $txt = troca($txt, chr(13), '');
            $txt = explode(chr(10), $txt);

            $hd = explode(';', $txt[0]);
            $pref = -1;
            for ($r = 0; $r < count($hd); $r++) {
                if ($hd[$r] == 'preLabel') {
                    $pref = $r;
                }
                if ($hd[$r] == 'prefLabel') {
                    $pref = $r;
                }
            }
            if ($pref == 0) {
                echo "OP";
                exit;
            }
            $sx .= '<ul>';
            for ($r = 1; $r < count($txt); $r++) {
                $line = $txt[$r];
                $line = explode(';', $line);

                if (isset($line[$pref])) {

                    $prefLabel = $line[$pref];
                    $IDC = $this->create_concept($prefLabel);

                    for ($z = 0; $z < count($hd); $z++) {
                        if ($z != $pref) {
                            $prop = $hd[$z];
                            /******************** Criar Literal */
                            $term = $line[$z];
                            $IDT = $ThLiteral->add_term($term, $lang, $th);
                            $ThRelations->relations($IDC, 0, $IDT, $prop, $th);
                        }
                    }

                    $sx .= '<li>' . $prefLabel;
                    $sx .= ' - ' . $IDC;
                    $sx .= '</li>';
                }
            }
            $sx .= '<ul>';
            return $sx;
        }

        /***************************************** FORM */
        $sx .= h('thesa.inport_csv', 4);
        $sx .= form_open_multipart();
        $sx .= form_upload(array('name' => 'file_csv'));
        $sx .= form_submit(array('name' => 'submit', 'value' => lang('thesa.inport_csv_submit')));
        $sx .= form_close();
        return $sx;
    }
    function create_concept($pl)
    {
        $ThThesaurus = new \App\Models\Thesaurus\ThThesaurus();
        $ThConcept = new \App\Models\Thesaurus\ThConcept();

        $th = $ThThesaurus->getTh();

        if (strpos($pl, '@') > 0) {
            $lang = substr($pl, strpos($pl, '@') + 1, strlen($pl));
            $pl = substr($pl, 0, strpos($pl, '@'));
        } else {
            $lang = 'por';
        }
        $IDC = $ThConcept->create_conecpt_literal($pl, $lang, $th);
        return $IDC;
    }

    function description($id, $ac)
    {
        $sx = h('thesa.description', 4);
        $sx .= '<p>' . lang('thesa.description_text') . '</p>';
        return $sx;
    }
}
