<?php

namespace App\Models\Schema;

use CodeIgniter\Model;

class Index extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'schema_external_th';
    protected $primaryKey       = 'id_set';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'is_set','set_th','set_se',
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'set_created';
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

    function own($id)
        {
            pre($_SESSION);
        }

    function btn_inport_external($id)
        {
            $sx = form_open();
            $sx .= h(lang('thesa.import_external_schema'),4);
            $sx .= '<span class="small">'.lang('thesa.informe_url').' (XML)</span>';
            $sx .= form_input(array('name'=>'url_schema','class'=>'form-control'));            
            $sx .= '<span class="small">Ex: http://vocabularies.unesco.org/browser/rest/v1/thesaurus/data?format=application/rdf%2Bxml</span><br/>';
            $sx .= form_submit('action',lang('thesa.import_external_schema'),'class="btn btn-outline-primary"');
            $sx .= form_close();

            $sx .= h('thesa.or',4);
            $sx .= form_open_multipart();
            $sx .= form_upload('schemas');
            $sx .= '<br>';
            $sx .= form_submit('action',lang('thesa.import_external_schema'),'class="btn btn-outline-primary"');
            $sx .= form_close();


            /***********************************************************************************************************************/
            $url = get("url_schema");
            if (strlen($url) > 0)
                {
                    $SchemaExternal = new \App\Models\Schema\SchemaExternal();
                    $sx = $SchemaExternal->import_external_schema($url,$id);
                }
            if (isset($_FILES['schemas']))
                {
                    $SchemaExternal = new \App\Models\Schema\SchemaExternal();
                    $sx = $SchemaExternal->import_external_schema_upload($_FILES['schemas'],$id);
                }
            
            return $sx;
        }

    function list($id)
        {
            $sx = '';
            $dt = $this->where('set_th',$id)->findAll();
            if (count($dt) > 0)
                {

                } else {
                    $sx .= bsmessage('thesa.there_are_no_relations',3);
                }
            return $sx;
        }

    function list_thesa_show($id)
        {
            $SchemaExternalTh = new \App\Models\Schema\SchemaExternalTh();
            $sx = h(lang('thesa.relations_thesa'),5);
            $sx .= $SchemaExternalTh->show($id);
            return $sx;
        }

    function list_skos_show($id)
        {
            $SchemaExternal = new \App\Models\Schema\SchemaExternal();
            $sx = h(lang('thesa.relations_skos'),5);
            $sx .= $SchemaExternal->show($id);
            return $sx;
        }    

    function btn_relation_skos($th)
        {
            $url = PATH . MODULE . 'popup/relation_skos/' . $th;
            $sx = '';
            $sx = onclick($url,800,600,'btn btn-outline-primary');
            $sx .= lang('thesa.relations_skos_add');
            $sx .= '</span>';

            return $sx;
        }            

    function btn_relation_thesa($th)
        {
            $url = PATH . MODULE . 'popup/relation_thesa/' . $th;
            $sx = '';
            $sx = onclick($url,800,600,'btn btn-outline-primary');
            $sx .= lang('thesa.relations_thesa_add');
            $sx .= '</span>';

            return $sx;
        }
    
    
}