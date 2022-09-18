<?php

namespace App\Models\RDF\Ontology;

use CodeIgniter\Model;

class Resource extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'owl_resource';
    protected $primaryKey       = 'id_rs';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_rs', 'rs_prefix', 'rs_namespace',
        'rs_url', 'created_at', 'updated_at'
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

    function identify($url)
        {
            $Prefix = new \App\Models\RDF\Ontology\Prefix();
            if ($pos = strpos($url,'#'))
                {
                    $uri = substr($url,0,$pos);
                    $name = substr($url,$pos+1,strlen($url));

                    $id_uri = $Prefix->identify($uri);

                    $dt = $this
                        ->where('rs_prefix',$id_uri)
                        ->where('rs_namespace', $name)
                        ->findAll();

                    if (count($dt) == 0)
                        {
                            $data['rs_prefix'] = $id_uri;
                            $data['rs_namespace'] = $name;
                            $data['rs_url'] = $url;
                            return $this->insert($data);
                        } else {
                            return $dt[0]['id_rs'];
                        }
                }
            echo "ERRO URL: $url";
            exit;
        }
}
