<?php

namespace App\Models\Thesa;

use CodeIgniter\Model;

class ThIcone extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thicones';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

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

    function icone($dt=array())
        {
            if (!isset($dt['th_icone']))
                {
                    $img = strzero(0, 4) . '.png';
                    $img = PATH . '/img/icons/' . $img;
                } else {
                    $img = strzero($dt['th_icone'],4).'.png';
                    $img = PATH.'/img/icons/'.$img;
                    if ($dt['th_icone'] != 0)
                        {
                            $img = strzero($dt['th_icone'], 4) . '.png';
                            $img = PATH . '/img/icons/' . $img;
                        }
                }
            return $img;
        }
}
