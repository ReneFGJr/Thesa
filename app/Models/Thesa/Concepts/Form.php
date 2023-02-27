<?php

namespace App\Models\Thesa\Concepts;

use CodeIgniter\Model;

class Form extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'forms';
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

    function form($id)
    {
        $sx = '';
        $Concept = new \App\Models\Thesa\Concepts\Index();
        $Thesa = new \App\Models\Thesa\Index();
        $Notes = new \App\Models\Thesa\Notes\Index();
        $Terms = new \App\Models\Thesa\Terms\Index();
        $Midias = new \App\Models\Thesa\Midias();
        $Relations = new \App\Models\Thesa\Relations\Index();

        /****************** Cabecalho Thesauro */
        $th = $Concept->recover_th($id);
        $sx .= $Thesa->show_header($th);

        /****************** Habilita Edição ****/
        $edit = true;

        /****************** Cabecalho Conceito */
        $data = [];
        $data['id'] = $id;
        $data['action'] = $Terms->btn_add($th);
        $data['header'] = $Terms->show_header($id);

        $dt = $Concept->le($id);

        /******** TERMOS */
        $forms = [];
        $forms['prefLabel'] = $Terms->recover($dt,'prefLabel',$edit);
        $forms['altLabel'] = $Terms->recover($dt, 'altLabel', $edit);
        $forms['hiddenLabel'] = $Terms->recover($dt, 'hiddenLabel', $edit);
        $data['forms'] = $forms;

        /********* RELATIONS */
        $data['relations'] = $Relations->recover($id, $edit);

        /******** MIDIAS */
        $data['midias'] = $Midias->show($id, $edit);
        //$data['midias'] = $Midias->recover($id,$edit);

        /******** NOTAS */
        $data['notes'] = $Notes->recover($id,$edit);

        $sx .= view('Theme/Standard/ConceptEdit',$data);



        $sx .= 'FORM';
        $sx = bs(bsc($sx, 12));
        return $sx;
    }

}
