<?php

namespace App\Models\Thesaurus;

use CodeIgniter\Model;

class ThAccess extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'th_users';
    protected $primaryKey       = 'id_ust';
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

    function access($th=0,$us='')
        {
            /********************* Loged? */
            if (!isset($_SESSION['id'])) {
                return false;
            }
            $us = $_SESSION['id'];

            /********************* Thesa ID? */
            if (!isset($_SESSION['th'])) {
                return false;
            } else {
                if ($th == 0)
                    {
                        $th = $_SESSION['th'];
                    }
            }

            /********************* Member? */
            $dt = $this
                    ->where('ust_user_id',$us)
                    ->where('ust_th',$th)
                    ->findAll();

            if (count($dt) == 0)
                {
                    return false;
                }
            return true;
        }
}
