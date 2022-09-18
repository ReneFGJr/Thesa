<?php

namespace App\Models\RDF;

use CodeIgniter\Model;

class ThProprityType extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_property_type';
    protected $primaryKey       = 'id_pt';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_pt', 'pt_name', 'pt_name_reverse',
        'pt_description',' pt_part_1','pt_part_2',
        'pt_part_3', 'pt_application'
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

    function select_prop($part)
    {
        $dt = $this
            ->where('pt_part_' . $part, 1)
            ->findAll();
        return $dt;
    }
}
