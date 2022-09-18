<?php

namespace App\Models\RDF\Ontology;

use CodeIgniter\Model;

class Index extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = '*';
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

    function index($d1, $d2, $d3)
    {
        $sx = '';

        switch ($d1) {
            case 'ajax_import':
                $OWL = new \App\Models\RDF\Ontology\OWL();
                $OWL->ajax_import($d2);
                exit;
                break;
            case 'import':
                $sx .= $this->import($d2);
                break;
            case 'view':
                $Vocabulary = new \App\Models\RDF\Ontology\Vocabulary();
                $sx .= $Vocabulary->view($d2);
                break;
            default:
                $Vocabulary = new \App\Models\RDF\Ontology\Vocabulary();
                $sx = $Vocabulary->list();
                break;
        }
        return $sx;
    }

    /********************************** Import */
    function import($id)
    {
        $Vocabulary = new \App\Models\RDF\Ontology\Vocabulary();
        $dt = $Vocabulary->find($id);
        $sx = $Vocabulary->header($dt);

        $iframe = '<iframe src="' . base_url(PATH . COLLECTION . '/ontology/ajax_import/' . $id) . '" width="100%" height="300px"></iframe>';
        $sx .= bs(bsc($iframe, 12, 'border border-primary'));

        return $sx;
    }
}
