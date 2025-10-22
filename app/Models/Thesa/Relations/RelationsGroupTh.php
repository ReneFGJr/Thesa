<?php

namespace App\Models\Thesa\Relations;

use CodeIgniter\Model;

class RelationsGroupTh extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_related_thesa';
    protected $primaryKey       = 'id_rt';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_rt',
        'rt_group',
        'rt_th'
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

    function getRelationsTh($th)
        {
        $th = get("thesaID");
        if ($th == '') { $th = 0; } // Default to 0 if no thesaID is provided
        $cp = 'id_rp, rp_achronic, rp_achronic_reverse, rg_name';
        $dt = $this
            ->select($cp)
            ->join('thesa_related_group', 'rt_group = id_rg')
            ->join('thesa_related_property', 'rt_group = rp_group')
            ->where('rt_th', $th)
            ->orderBy('id_rt')
            ->findAll();
        $dd = [];
        $dd['relations'] = $dt;
        return $dd;
    }

    function setRelationsType()
    {
        $idGr = get("idGr");
        $thesaID = get("thesaID");
        $check = get("checked");

        $RelationsGroupTh = new \App\Models\Thesa\Relations\RelationsGroupTh();
        if ($check == '1') {

            $dt = $RelationsGroupTh
                ->where('rt_group', $idGr)
                ->where('rt_th', $thesaID)
                ->first();

            if (!$dt) {
                $da['rt_group'] = $idGr;
                $da['rt_th'] = $thesaID;
                $this->set($da)->insert();

                $RSP = [];
                $RSP['status'] = 'success';
                $RSP['message'] = 'Relation updated successfully';
            } else {
                $RSP = [];
                $RSP['status'] = '201';
                $RSP['message'] = 'Relation already exists';
            }
        } else {
            $RelationsGroupTh
                ->where('rt_group', $idGr)
                ->where('rt_th', $thesaID)
                ->delete();
            $RSP['status'] = '200';
            $RSP['checked'] = $check;
            $RSP['message'] = 'Relation deleted successfully';
        }
        return $RSP;
    }
}
