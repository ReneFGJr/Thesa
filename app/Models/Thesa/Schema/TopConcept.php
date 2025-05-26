<?php

namespace App\Models\Thesa\Schema;

use CodeIgniter\Model;

class TopConcept extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_topconcept';
    protected $primaryKey       = 'id_ttc';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_ttc',
        'th_thesa',
        'th_concept'
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

    function getTopConceptsByThesa($thesaId)
    {
        return $this->where('th_thesa', $thesaId)->findAll();
    }

    function setTopConcept($thesaId, $conceptId)
    {
        $dt = $this->where([
            'th_thesa' => $thesaId,
            'th_concept' => $conceptId
        ])->first();

        if ($dt) {
            return $dt['id_ttc'];
        }
        $data = [
            'th_thesa' => $thesaId,
            'th_concept' => $conceptId
        ];
        return $this->set($data)->insert();
    }
}
