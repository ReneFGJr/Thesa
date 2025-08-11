<?php

namespace App\Models\Skos\Schemes;

use CodeIgniter\Model;

class Skosmos extends Model
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
        $URL = $json['url'];
        if (strpos($URL,'&') !== false) {
            $URL = substr($URL, 0, strpos($URL, '&'));
        }
        $URL = explode('/', $URL);
        $IDN = trim($URL[count($URL) - 1]);

        foreach ($data as $item) {

            $type = $item['type'] ?? '';
            echo $type.chr(10);
            if (($type == 'skos:Concept') and (strpos($item['uri'].'#', $IDN.'#') > 0)) {

                $prefLabel = $item['prefLabel'] ?? [];
                $altLabel = $item['altLabel'] ?? [];
                $hiddenLabel = $item['hiddenLabel'] ?? [];
                $noteScope = $item['skos:scopeNote'] ?? [];

                $RSP['status'] = '200';
                $RSP['message'] = 'Termos extraídos com sucesso.';
                $RSP['terms']['prefLabel'] = $prefLabel;
                $RSP['terms']['altLabel'] = $altLabel;
                $RSP['terms']['hiddenLabel'] = $hiddenLabel;
                $RSP['notes']['scopeNote'] = $noteScope;

                /* ID */
                $RSPID = $this->extractID($item['uri']);
                $RSP['ID'] = $RSPID['ID'];
                $RSP['Source'] = $RSPID['IDs']['id_lds'];
                $RSP['Agency'] = $RSPID['IDs']['lds_name'].':'.$RSPID['ID'];
            }
        }
        return $RSP;
    }

    function extractID($ID)
    {
        $LinkedData = new \App\Models\Linkeddata\Source_rdf();
        # Recupera o ID do LinkedData
        $IDs = $LinkedData->source($ID);

        $Domain = $LinkedData->extrairDominio($ID);
        $ID = substr($ID, strpos($ID, $Domain) + strlen($Domain));
        if (substr($ID, 0, 1) == '/') {
            $ID = substr($ID, 1);
        }
        $ID = str_replace('ark:/', '', $ID);
        $RSP = [];
        $RSP['ID'] = $ID;
        $RSP['IDs'] = $IDs;
        return $RSP;
    }
}
