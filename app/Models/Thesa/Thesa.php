<?php

namespace App\Models\Thesa;

use CodeIgniter\Model;

class Thesa extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_th','th_name','th_achronic',
        'th_description','th_status','th_terms',
        'th_version','th_icone','th_type','th_own'
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

    function thesa_add($dd)
    {
        $ThesaUser = new \App\Models\Thesa\ThesaUser();
        $dt = array();
        $dt['th_name'] = $dd['name'];
        $dt['th_own'] = $dd['own'];
        $dt['th_achronic'] = $dd['achronic'];
        $dt['th_description'] = $dd['description'];
        $dt['th_status'] = 1;
        $dt['th_type'] = $dd['type'];
        $dt['th_icone'] = date("s");
        $dt['th_version'] = 1;

        if ($dt['th_own'] > 0) {
            $da = $this
                ->where('th_own', $dt['th_own'])
                ->where('th_achronic', $dt['th_achronic'])
                ->findAll();

            if (count($da) == 0) {
                $id = $this->insert($dt);
            } else {
                $id = $da[0]['id_th'];
            }

        $ThesaUser->add_user_th($id,$dt['th_own'],1);
        return $id;
        }
    }
}
