<?php

namespace App\Models\Language;

use CodeIgniter\Model;

class Index extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'language';
    protected $primaryKey       = 'id_lg';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_lg', 'lg_code', 'lg_language',
        'lg_order', 'lg_active', 'lg_cod_marc'
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

    function form($th=0)
        {
            $dt = $this->orderBy('lg_order')->findAll();
            $sx = '';
            for ($r=0;$r < count($dt);$r++)
                {
                    $sx .= '<br>';
                    $sx .= '<input class="form-check-input" type="radio" name="lg" id="lg" value="'.$dt[$r]['id_lg'].'">';
                    $sx .= '&nbsp;';
                    $sx .= $dt[$r]['lg_language'];
                    $sx .= '</input>';
                }
            return $sx;
        }
}
