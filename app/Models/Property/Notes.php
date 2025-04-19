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

    function deleteNote()
    {
        $IDN = get('noteID');
        $this->where('id_nt', $IDN)->delete();
    }

    function saveNote()
    {
        $IDC = get('conceptID');
        $IDN = get('noteID');
        $thesaID =  get('thesaID');
        $note = get('note');
        $noteType = get('noteType');
        $lang = get('lang');
        $th = get('thesaurus');

        /* Language */
        $Language = new \App\Models\Language\Index();
        $lg = $Language->where('lg_cod_marc',$lang)->first();
        $lang = $lg['id_lg'];

        $this->register($IDC, $noteType,$note,$lang, $thesaID, $IDN);

    }

    function register($IDC,$prop,$note,$lang,$th,$IDN=0)
        {
            $dd['nt_concept'] = $IDC;
            $dd['nt_content'] = $note;
            $dd['nt_prop'] = $prop;
            $dd['nt_lang'] = $lang;

            if ($IDN == 0)
                {
                    $this->set($dd)->insert();
                } else {
                    $this->set($dd)
                        ->where('id_nt',$IDN)
                        ->update();
                }
            return true;
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
