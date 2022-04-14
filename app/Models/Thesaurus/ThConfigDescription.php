<?php

namespace App\Models\Thesaurus;

use CodeIgniter\Model;

class ThConfigDescription extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'th_thesaurus';
    protected $primaryKey       = 'id_pa';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_pa','pa_name','pa_achronic',
        'pa_description','pa_status',
        'pa_classe','pa_version',
        'pa_introdution','pa_methodology','pa_audience',
        'pa_type',

    ];
    protected $typeFields    = [    
        'hidden',
        'string:150*','asc:30*',
        'text',
        'status*',
        'sql:id_pac:pac_name:th_thesaurus_class*',
        'string:10',
        'text',
        'text',
        'text*',
        'hidden',
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

    function edit($id,$type='')
        {
            $this->id = $id;
            
            $this->path = PATH.MODULE.'th_config/'.$id.'/description';
            $this->path_back = PATH.MODULE.'th_config';
            $this->pre = 'thesa.';
            $sx = form($this);
            return $sx;
        }
}
