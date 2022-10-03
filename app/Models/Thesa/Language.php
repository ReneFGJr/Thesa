<?php

namespace App\Models\Thesa;

use CodeIgniter\Model;

class Language extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_language';
    protected $primaryKey       = 'id_lgt';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_lgt', 'lgt_th', 'lgt_language', 'lgt_order'
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

    function register($th,$lang)
        {
            $dt = $this->where('lgt_th',$th)->where('lgt_language',$lang)->findAll();
            if (count($dt) == 0)
                {
                    $data['lgt_th'] = $th;
                    $data['lgt_language'] = $lang;
                    $this->insert($data);
                }
            return true;
        }

    function remove($th, $lang)
    {
        $dt = $this->where('lgt_th', $th)->where('lgt_language', $lang)->findAll();
        if (count($dt) > 0) {
            $this->where('lgt_th', $th)->where('lgt_language', $lang)->delete();
        }
        return true;
    }
}
