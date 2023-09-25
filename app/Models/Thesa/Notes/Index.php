<?php

namespace App\Models\Thesa\Notes;

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

    function le($id)
        {
            $ThNotes = new \App\Models\RDF\ThNotes();
            $dt = $ThNotes->where('nt_concept',$id)->findAll();
            return $dt;

        }

    function recover($id=0,$edit=0)
        {
            $ThNotes = new \App\Models\RDF\ThNotes();
            $nt = ['definition','scopeNote', 'notation', 'note', 'changeNote', 'editorialNote', 'example', 'historyNote'];
            $dt = [];
            foreach($nt as $note)
                {
                    $dt[$note] = $ThNotes->list($id,$note,$edit);
                }
            return $dt;

        }
}
