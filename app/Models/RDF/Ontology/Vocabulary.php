<?php

namespace App\Models\RDF\Ontology;

use CodeIgniter\Model;

class Vocabulary extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'owl_vocabulary';
    protected $primaryKey       = 'id_owl';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_owl', 'owl_title', 'owl_url',
        'owl_description', 'owl_status', 'created_at',
        'updated_at', 'owl_prefix', 'spaceName'
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

    function view($id)
        {
            $Vocabulary = new \App\Models\RDF\Ontology\VocabularyVC();

            $dt = $this->find($id);
            $sx = $this->header($dt);
            $sa = $this->btn_update($id);

            $sx .= bs(bsc($sa,12));

            $sx .= $Vocabulary->view($id);
            return $sx;
        }

    function btn_update($id)
        {
            $sx = '<a href="'.base_url(PATH. 'admin/ontology/import/'.$id).'" class="btn btn-outline-secondary">'.msg('update').'</a>';
            return $sx;
        }

    function header($dt)
        {
            $sx = '';
            $sx .= bsc('<h1>'.$dt['owl_title'].'</h1>',12);
            $sx .= bsc('<p>'.$dt['owl_description'].'</p>',10);
            $sx .= bsc('<p>' . $dt['updated_at'] . '</p>', 2);
            $sx .= bsc('<p>'.anchor($dt['owl_url']).'</p>',10);
            $sx .= bsc('<hr>',12);
            $sx = bs($sx);
            return $sx;
        }

    function list()
        {
            $sx = '';
            $dt = $this->findAll();
            $sx .= h('thesa.vocabulary.list',3);
            for ($r=0;$r < count($dt);$r++)
                {
                    $line = $dt[$r];
                    $sx .= '
                    <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">'.$line['owl_title'].'</h5>
                        <span style="font-size: 75%" class="card-subtitle mb-2 text-muted">'.$line['updated_at']. '</span>
                        <p class="card-text">' . substr($line['owl_description'],0,100) . '</p>
                        <a href="'.PATH.COLLECTION. '/ontology/view/'.$line['id_owl'].'" class="card-link">'.lang('thesa.ontology_access'). '</a>
                        <a href="' . PATH . COLLECTION . '/ontology/edit/' . $line['id_owl'] . '" class="card-link">' . lang('thesa.ontology_edit') . '</a>
                    </div>
                    </div>
                    ';
                }
            $sx = bs($sx);
            return $sx;
        }
}
