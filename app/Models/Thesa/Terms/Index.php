<?php

namespace App\Models\Thesa\Terms;

use CodeIgniter\Model;

class Index extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_terms';
    protected $primaryKey       = 'id_term';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_term', 'term_name', 'term_lang', 'term_th_concept'
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
                ->join('language', 'id_lg = term_lang')
                ->where('id_term',$id)
                ->first();
            return $dt;
        }

    function recover($dt,$prop,$edit=false)
        {
            $sx = '';
            foreach($dt as $id=>$line)
                {
                    $class = $line['property'];
                    if ($class == $prop)
                        {
                            $sx .= $this->show_value($line,$edit).'<br>';
                        }
                }
            return $sx;
        }

    function show_header($id)
    {

        $Concept = new \App\Models\Thesa\Concepts\Index();
        $Collaborators = new \App\Models\Thesa\Collaborators();

        $dt = $Concept->le($id);

        $data = [];
        $data['prefLabel'] = $this->prefLabel($dt);
        $data['url'] = PATH . '/v/' . $id;
        $data['edit'] = '';

        if ($Collaborators->own($id)) {
            $data['edit'] = anchor(PATH . '/a/' . $id, bsicone('edit'));
        }

        $sx = view("Theme/Standard/TermHeader", $data);
        return $sx;
    }

    function show_term($id)
        {
            $dt = $this->le($id);
            return view('Theme/Standard/TermGeral',$dt);
        }

    function show($id,$edit=false)
        {
            $sx = '';
            $Midias = new \App\Models\Thesa\Midias();
            $Concept = new \App\Models\Thesa\Concepts\Index();
            $NotesIndex = new \App\Models\Thesa\Notes\Index();
            $Reference = new \App\Models\Thesa\Reference();

            $data = [];
            $data['midias'] = $Midias->show($id);

            $dt = $Concept->le($id);

            $data['values'] = $this->show_proprieties($dt,$edit);
            $data['notes'] = $NotesIndex->recover($id,false);
            $sx .= $this->show_header($id);
            $sx .= view("Theme/Standard/Term", $data);
            return $sx;
        }

    function caditate_prefLabel($id, $langs = array(), $th = 0)
    {
        $this
            ->join('thesa_terms_th', 'term_th_term = id_term')
            ->join('language', 'term_lang = id_lg')
            ->where('term_th_thesa', $th)
            ->where('term_th_concept', 0);
        foreach ($langs as $lang => $temp) {
            $this->where('lg_code <> "' . $lang . '"');
        }
        $this->orderBy('term_name', 'ASC');
        $dt = $this->findAll();
        return ($dt);
    }

    function prefLabel($dt)
        {
            $prefLabel = '';
            foreach($dt as $id=>$line)
                {
                    $class = $line['property'];
                    if ($class == 'prefLabel')
                        {
                            return $line['label'].' <sup>('.$line['lg_code'].')</sup>';
                        }
                }
            return 'NaN';

        }

    function btn_add($th=0)
    {
        $sx = '';
        $sx .= '<span class="handle" onclick="newwin(\'' . PATH . '/admin/term_add/' . $th . '\',600,700);">' . bsicone('plus') . '</span>';
        return $sx;
    }

    function show_proprieties($dt,$edit)
    {
        $sv = '';
        $prop = [];
        foreach ($dt as $idx => $line) {
            $pn = $line['property'];
            if (!isset($prop[$pn])) {
                $prop[$pn] = '';
            } else {
                $prop[$pn] .= '<br/>';
            }
            $prop[$pn] .= $this->show_value($line,$edit);
        }
        return $prop;
    }

    function exclude($d1,$d2,$d3,$d4)
        {
            $sx = '';
            $sx = view('Theme/Standard/Logo');

            $ThConceptPropriety = new \App\Models\RDF\ThConceptPropriety();
            $dt = $ThConceptPropriety->le($d1);

            if ($dt == '') {
                $sx .= bsmessage('404: Relation_not_exists', 3);
                $sx .= view('Theme/Standard/Btns/Close');
                return $sx;
            }

            $Terms = new \App\Models\Thesa\Terms\Index();
            $sx .= $Terms->show_term($dt['id_term']);


            if($d3=='confirm')
                {
                    $ThConceptPropriety = new \App\Models\RDF\ThConceptPropriety();
                    $dt = $ThConceptPropriety->le($d1);

                    $th = $dt['ct_th'];
                    $idt = $dt['ct_literal'];
                    $id_ct = $dt['id_ct'];

                    /* Libera termo */
                    $ThTerms = new \App\Models\RDF\ThTermTh();
                    $concept = 0;
                    $ThTerms->update_term_th($idt, $th, $concept);

                    /* Ativa log */

                    /* Exclui registro */
                    $ThConceptPropriety->where('id_ct',$id_ct)->delete();
                    return wclose();

                }

            $sx .= '<hr>';
            $sx .= bs(bsc(h(lang('thesa.exclude')), 12, 'text-center'));
            $btn1 = '<a href="'.PATH.'/admin/ajax_exclude/'.$d1.'/'.$d2.'/confirm" class="btn btn-outline-danger">Confirmar</a>';
            $btn2 = '<a href="#" onclick="wclose();" class="btn btn-outline-secondary">Cancel</a>';
            $sx .= bs(bsc($btn1.' '.$btn2,12,'text-center'));
            return $sx;
        }

    function edit_link($line, $edit)
        {
            if ($edit != true)
                {
                    return "";
                }

            $class = $line['property'];

            switch($class)
                {
                    case 'altLabel':
                        $onclick = ' onclick="newwin(\'' . PATH . '/admin/ajax_exclude/' . $line['id_ct'] . '/' . $class . '\',600,300);"';
                        break;
                    case 'hiddenLabel':
                        $onclick = ' onclick="newwin(\''.PATH.'/admin/ajax_exclude/'.$line['id_ct'].'/'.$class.'\',600,300);"';
                        break;
                    default:
                        $onclick = '';
                        break;
                }
            $sx = '<span class="text-danger handle me-1" '.$onclick.'>'.bsicone('trash').'</span>';
            return $sx;
        }

    function show_value($line,$edit=false)
    {
        $prop = $line['property'];
        $lang = '';
        if (trim($line['label']) != '') {
            $lang = ' <sup class="small" title="' . $line['lg_language'] . '">(' . $line['lg_code'] . ')</sup>';
        }

        switch ($prop) {
            case 'isInstanceOf':
                $sx = $line['resource_name'];
                break;
            case 'prefLabel':
                $sx = $this->edit_link($line,$edit);
                $sx .= '<b>'.$line['label'] . '</b>'. $lang;
                break;
            case 'altLabel':
                $sx = $this->edit_link($line, $edit);
                $sx .= '<span style="font-size: 0.8em;">' . $line['label'] . '</span>' . $lang;
                break;
            case 'hiddenLabel':
                $sx = $this->edit_link($line, $edit);
                $sx .= '<span style="font-size: 0.8em;">' . $line['label'] . '</span>' . $lang;
                break;
            default:
                $sx = $line['label'] . $lang;
                break;
        }
        return $sx;
    }
}
