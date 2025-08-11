<?php

namespace App\Models\Skos\Schemes;

use CodeIgniter\Model;

class Usda extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_exactmatch';
    protected $primaryKey       = 'id_em';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_em',
        'em_concept',
        'em_link',
        'em_type',
        'em_source',
        'em_visible',
        'em_lastupdate'
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

    function extract($json)
    {
        $SKOSMOS = new \App\Models\Skos\Schemes\Skosmos();
        return $SKOSMOS->extract($json);
    }

    function extractID($ID)
    {
        $SKOSMOS = new \App\Models\Skos\Schemes\Skosmos();
        return $SKOSMOS->extractID($ID);
    }
}
