<?php

namespace App\Models\Thesa\Relations;

use CodeIgniter\Model;

class RelationsGroup extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_related_group';
    protected $primaryKey       = 'id_ct';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_rg ',
        'rg_name',
        'rg_description'
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

    function getGroup($th)
    {
        $th = get("thesaID");
        if ($th == '') { $th = 0; }
        $dt = $this
            ->join('thesa_related_property','rp_group = id_rg')
            ->join('thesa_related_thesa','(rt_group = id_rg) and (rt_th = '.$th.')', 'left')
            ->orderBy('id_rg')
            ->findAll();


        $dn = [];
        $dd = [];
        foreach ($dt as $d) {
            $name = $d['rg_name'];
            if (!isset($dn[$name])) {
                $dr = [];
                $dr['name'] = $name;
                $dr['ID'] = $d['id_rg'];
                $dr['property'] = [];
                $dn[$name] = count($dn);
            }
            $idn = $dn[$name];
            $dd[$idn]['property'][] = $d;
            $dd[$idn]['name'] = $name;
            $dd[$idn]['checked'] = $d['rt_group'] ? 1 : 0;
            $dd[$idn]['ID'] = $d['id_rg'];
        }
        $dr = [];
        $dr['groups'] = $dd;
        return $dr;
    }
}
