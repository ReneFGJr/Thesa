<?php

namespace App\Models\Property;

use CodeIgniter\Model;

class Notes extends Model
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
        'id_nt', 'nt_concept', 'nt_prop',
        'nt_lang', 'nt_content', 'updated_at'
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

    function getNote($noteID)
    {
        $RSP = [];
        if ($noteID == '') {
            $RSP['status'] = '500';
            $RSP['message'] = 'noteID not informed';
            $RSP['data'] = $_POST;
        } else {
            $RSP['status'] = '200';
            $RSP['message'] = 'Note found';
            $RSP['noteID'] = $noteID;
            $RSP['data'] = $this->find($noteID);
        }
        return $RSP;
    }

    function deleteNote()
    {
        $IDN = get('noteID');
        if ($IDN == '') {
            $RSP['status'] = '500';
            $RSP['message'] = 'noteID not informed';
            $RSP['data'] = $_POST;
            return $RSP;
        } else {
            $RSP['status'] = '200';
            $RSP['message'] = 'Note deleted';
            $this->where('id_nt', $IDN)->delete();
        }

    }

    function saveNote()
    {
        $RSP = [];
        $IDC = get('conceptID');
        if (isset($_POST['noteID']))
            {
                $IDN = get('noteID');
            }
        if($IDN == 'null')
            {
                $IDN = 0;
            }

        $thesaID =  get('thesaID');
        $note = get('note');
        $noteType = get('noteType');
        $lang = get('language');

        /* IDC */
        if ($IDC == '') {
            $RSP['status'] = '500';
            $RSP['message'] = 'conceptID not informed';
            $RSP['data'] = $_POST;
            return $RSP;
        }

        /* Language */
        $Language = new \App\Models\Language\Index();
        if (!sonumero($lang)) {
            $lg = $Language->where('lg_cod_marc', $lang)->first();
            if ($lg == []) {
                $lang = 1;
            } else {
                $lang = $lg['id_lg'];
            }
        }

        if ($note == '') {
            $RSP['status'] = '500';
            $RSP['message'] = 'Note not informed';
        } else {
            if ($lang > 0)
                {
                    $RSP['e'] = $this->register($IDC, $noteType, $note, $lang, $thesaID, $IDN);
                }
            $RSP['status'] = '200';
            $RSP['noteID'] = $IDN;
            $RSP['language_code'] = $lang;
            $RSP['language'] = get('language');
            $RSP['message'] = 'Note saved';
            $RSP['IDN'] = $IDN;
        }
        return $RSP;
    }

    function register($IDC,$prop,$note,$lang,$th,$IDN=0)
        {
            $dd['nt_concept'] = $IDC;
            $dd['nt_content'] = $note;
            $dd['nt_prop'] = $prop;
            $dd['nt_lang'] = $lang;
            $dd['updated_at'] = date('Y-m-d H:i:s');

            if ($IDN == 0)
                {
                    $this->set($dd)->insert();
                    $id = "Insert";
                } else {
                    $this->set($dd)
                        ->where('id_nt',$IDN)
                        ->update();
                    $id = 'Update';
                }
            $Concept = new \App\Models\Concept\Index();
            $Concept->updateConcept($IDC);
            return $id;
        }

    function le($id)
    {
        $cp = 'lg_code, nt_content, id_nt, p_name';
        $ThNotes = new \App\Models\RDF\ThNotes();
        $dt = $ThNotes
            ->select($cp)
            ->join('thesa_property', 'id_p = nt_prop')
            ->join('language', 'id_lg = nt_lang')
            ->where('nt_concept', $id)->findAll();

        foreach ($dt as $id => $line) {
            $dt[$id]['note'] = $line['p_name'];
            $dt[$id]['p_name'] = lang('thesa.'.$line['p_name']);
        }
        return $dt;
    }

    function recover($id = 0, $edit = 0)
    {
        $ThNotes = new \App\Models\RDF\ThNotes();
        $nt = ['definition', 'scopeNote', 'notation', 'note', 'changeNote', 'editorialNote', 'example', 'historyNote'];
        $dt = [];
        foreach ($nt as $note) {
            $dt[$note] = $ThNotes->list($id, $note, $edit);
        }
        return $dt;
    }
}
