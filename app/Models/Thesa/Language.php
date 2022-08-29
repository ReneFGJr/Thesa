<?php

namespace App\Models\Thesa;

use CodeIgniter\Model;

class Language extends Model
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

    function search($lang)
    {
        $lang = strtolower($lang);
        switch ($lang) {
            case 'en':
                $lang = 'eng';
                break;
            case 'pt_br':
                $lang = 'por';
                break;
            case 'pt-br':
                $lang = 'por';
                break;
            case 'pt':
                $lang = 'por';
                break;
        }

        $dt = $this->where('lg_code', $lang)->findAll();
        if (count($dt) > 0) {
            return ($dt[0]['id_lg']);
        } else {
            echo "ERRO '- " . $lang;
            exit;
        }
    }
}
