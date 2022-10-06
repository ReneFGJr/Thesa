<?php

namespace App\Models\Thesa;

use CodeIgniter\Model;

class Licenses extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_descriptions';
    protected $primaryKey       = 'id_ds';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_ds', 'ds_prop', 'ds_descrition', 'ds_th',
        'created_at', 'updated_at'
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

    function saveLicense($th, $lc)
    {
        $Descriptions = new \App\Models\Thesa\Descriptions();
        $dd = $this
            ->where('ds_th', $th)
            ->where('ds_prop', 'License')
            ->findAll();
        if (count($dd) == 0) {
            $Descriptions->register($th, 'License', $lc);
        }
        return '';
    }

    function radiobox($th)
    {
        $Licenses = array('CC-BY', 'CC-BY-SA', 'CC-BY-ND', 'CC-BY-NC', 'CC-BY-NC-SA', 'CC-BY-NC-ND', 'CC0');

        $dt = $this
            ->where('ds_th', $th)
            ->where('ds_prop', 'License')
            ->findAll();
        if (count($dt) == 0) {
            $lc = $Licenses[0];
            $this->saveLicense($th, $lc);
        } else {
            $lc = $dt[0]['ds_descrition'];
        }

        $sx = '<div class="input-group mb-5">';
        $sx .= '<select name="License" id="License" class="form-control">';
        for ($r = 0; $r < count($Licenses); $r++) {
            $check = '';
            if ($Licenses[$r] == $lc) {
                $check = 'selected';
            }
            $sx .= '<option value="' . $Licenses[$r] . '" ' . $check . '> ' . lang('thesa.' . $Licenses[$r]) . '</option>';
        }
        $sx .= '</select>';
        $sx .= '<div class="input-group-append">';
        $sx .= '<button class="btn btn-outline-primary" onclick="form_field_save(\'License\','.$th.');" type="button">'.lang('thesa.save').'</button>';
        $sx .= '</div>';
        $sx .= '<div id="status_License"></div>';
        $sx .= '</div>';

        return $sx;
    }
}
