<?php

namespace App\Models\Skos\Schemes;

use CodeIgniter\Model;

class Loterre extends Model
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
        $RSP = [];
        $data = $json['data']['graph'];
        pre($json);
        foreach ($data as $item) {
            $type = $item['type'] ?? '';
            if ($type == 'skos:Concept') {
                $prefLabel = $item['prefLabel'] ?? [];
                $altLabel = $item['altLabel'] ?? [];
                $hiddenLabel = $item['hiddenLabel'] ?? [];

                $RSP['status'] = '200';
                $RSP['message'] = 'Termos extraídos com sucesso.';
                $RSP['terms']['prefLabel'] = $prefLabel;
                $RSP['terms']['altLabel'] = $altLabel;
                $RSP['terms']['hiddenLabel'] = $hiddenLabel;
            }
        }
        return $RSP;
    }
}
