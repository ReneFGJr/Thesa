<?php

namespace App\Models\Thesa\Schema;

use CodeIgniter\Model;

class TopConcept extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_concept_scheme';
    protected $primaryKey       = 'id_cs';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_cs',
        'cs_th',
        'cs_topConcept'
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
        return $this->where('cs_th', $thesaId)->findAll();
    }

    function getTopConcept($conceptID)
    {
        $dt = $this->where('cs_topConcept', $conceptID)->first();
        if ($dt) {
            return true;
        }
        return false;
    }

    function setTopConcept()
    {
        $Token = get("apikey");
        $Social = new \App\Models\Socials();
        if ($Social->validaAPIKEY($Token) == 0) {
            return [
                'status' => '500',
                'message' => 'Invalid API key',
                'token' => $Token
            ];
        }
        $conceptId = get("conceptId");
        $thesaId = get("thesaId");

        $dt = $this->where([
            'cs_th' => $thesaId,
            'cs_topConcept' => $conceptId
        ])->first();

        if ($dt) {
            // Remove the top concept
            $this->where('id_cs', $dt['id_cs'])->delete();
            return [
                'status' => '200',
                'message' => 'Top concept removed successfully',
                'conceptId' => $conceptId
            ];
        } else {
            // Set the top concept
            $data = [
                'cs_th' => $thesaId,
                'cs_topConcept' => $conceptId
            ];
            $this->insert($data);
            return [
                'status' => '200',
                'message' => 'Top concept set successfully',
                'conceptId' => $conceptId
            ];
        }

        return [
            'status' => '200',
            'message' => 'OK',
            'rsp'=>$dt
        ];
    }
}
