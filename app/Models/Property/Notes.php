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

    function register($IDC,$prop,$note,$lang,$th)
        {
            $dt = $this
                ->where('nt_concept',$IDC)
                ->where('nt_content',$note)
                ->where('nt_prop',$prop)
                ->first();

            if ($dt==[])
                {
                    $dd['nt_concept'] = $IDC;
                    $dd['nt_content'] = $note;
                    $dd['nt_prop'] = $prop;
                    $dd['nt_lang'] = $lang;
                    $this->set($dd)->insert();
                }
            return true;
        }

    function le($id)
    {
        $ThNotes = new \App\Models\RDF\ThNotes();
        $dt = $ThNotes
            ->join('thesa_property', 'id_p = nt_prop')
            ->where('nt_concept', $id)->findAll();
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
