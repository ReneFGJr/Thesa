<?php

namespace App\Models\RDF;

use CodeIgniter\Model;

class ThProprityCustom extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_property_custom';
    protected $primaryKey       = 'id_pcst';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'pcst_class', 'pcst_th', 'pcst_type',
        'pcst_achronic', 'pcst_name', 'pcst_description',
        'pcst_part_1', 'pcst_part_2', 'pcst_part_3',
        'pcst_public', 'pcst_aplicable', 'updated_at',

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

    function register($dt)
        {
            $id = $dt['id_pcst'];
            if ($id == 0)
                {
                    $this->set($dt)->insert();
                } else {
                    $this->set($dt)->where('id_pcst',$id)->update();
                }
                echo $this->getlastquery();
                return true;
        }
    function find_class($name)
        {
            $Thesa = new \App\Models\Thesa\Thesa();
            $th = $Thesa->getThesa();
            $dt = $this
                ->where('pcst_achronic',$name)
                ->where(('pcst_th = '.$th.' or pcst_th = 0'))
                ->findAll();
            if (count($dt) > 0)
                {
                    return $dt[0];
                } else {
                    return array();
                }
        }
}
