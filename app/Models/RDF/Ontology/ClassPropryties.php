<?php

namespace App\Models\RDF\Ontology;

use CodeIgniter\Model;

class ClassPropryties extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'OWL_resource';
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

    function change_prefix($url, $prefix)
    {
        foreach ($prefix as $name => $link) {

            if (strpos(' ' . $url, $link) > 0) {
                $url = troca($url, $link, $name . ':');
                return ($url);
            }
        }
        echo "OPS PREFIX";
        exit;
    }

    function ClassPropryties($URL)
        {
            $Prefix = new \App\Models\RDF\Ontology\Prefix();
            $prefix = $Prefix->prefix($URL);

            $nameSpeace = $URL;

            if (strpos($URL,'http:'))
                {
                    echo "HTTP encontrada";
                    echo '<br>'.$URL;
                    exit;
                }

            echo (h($nameSpeace,1));
            exit;
            $dt = $this
                ->where('rs_namespace',$spaceName)
                ->where('rs_prefix', $spaceName)
                ->findAll();
            if (count($dt) == 0)
                {
                    $data['rs_namespace'] = $spaceName;
                    $data['rs_prefix'] = 0;
                    $data['rs_url'] = $URL;
                    $data['updated_at'] = date("Y-m-d H:i:s");
                    $id = $this->insert($data);
                } else {
                    $id = $dt[0]['id_rs'];
                }
                return $id;
        }
}
