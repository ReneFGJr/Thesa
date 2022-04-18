<?php

namespace App\Models\Thesaurus;

use CodeIgniter\Model;

class ThConfigRelations extends Model
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
        'id_set','set_th','set_se','set_active'

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

    function edit_skos_relations($d1,$d2,$d3,$d4)
        {
            $SchemaExternal = new \App\Models\Schema\SchemaExternal();
            $sx = $SchemaExternal->form_edit_skos($d1,$d2,$d3,$d4);

            $sx = bs(bsc($sx,12));
            return $sx;
        }

    function edit_thesa_relations($d1,$d2,$d3,$d4)
        {
            $SchemaExternalTh = new \App\Models\Schema\SchemaExternalTh();
            
            if ($d3=='del')
                {
                    $dd['set_active'] = 0;
                    $SchemaExternalTh->set($dd)->where('id_set',$d1)->update();
                    return wclose();
                }

            if ($d3=='udel')
                {
                    $dd['set_active'] = 1;
                    $SchemaExternalTh->set($dd)->where('id_set',$d1)->update();
                    return wclose();
                }                
            
            $sx = $SchemaExternalTh->list_thesa($d1);

            $sx = bs(bsc($sx,12));
            return ($sx);
        }

    function edit($id=0,$ac='')
        {
            $Schema = new \App\Models\Schema\Index();
            $sx = h(lang('thesa.relations'),3);

            // THESA
            $sx .= $Schema->list_thesa_show($id);
            $sx .= $Schema->btn_relation_thesa($id);

            // SEPARATOR
            $sx .= '<div class="m-5"></div>';

            // SKOS
            $sx .= $Schema->list_skos_show($id);
            $sx .= $Schema->btn_relation_skos($id);

            $sx .= '<div class="mt-5"></div>';
            //$sx .= $Schema->list($id);           
            ///$sx .= $Schema->btn_inport_external($id);
            
            return $sx;
        }

    function excluding($id,$th)
        {
            $dt['set_active'] = 0;
            $this->set($dt)->where('set_th',$th)->where('set_se',$id)->update();
            return wclose();
        }
}
