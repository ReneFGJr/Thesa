<?php

namespace App\Models\RDF\Ontology;

use CodeIgniter\Model;

class Prefix extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'owl_vocabulary';
    protected $primaryKey       = 'id_owl';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_owl', 'owl_prefix', 'owl_title',
        'owl_url', 'owl_description', 'owl_status',
        'created_at', 'updated_at', 'endpoint', 'spaceName'

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

    function identify($prefix)
        {
            $xprefix = $prefix;
            $xsufix = '';

            if ($pos = strpos($xprefix,'#'))
                {
                     $xprefix = substr($prefix,0,$pos);
                     $xsufix =substr($prefix, $pos+1,strlen($prefix));
                }
            $dt = $this->like('endpoint',$xprefix)->findAll();
            if (count($dt) > 0)
                {
                    $id = $dt['0']['id_owl'];
                    return $id;
                }
            /******** ERRO */
            echo "ERRO LOCALE PREFIX - $prefix";
            exit;
        }

    function prefix($name,$uri='')
    {
        if ($uri=='')
            {
                $dt = $this
                    ->where('owl_prefix', $name)
                ->find();
            } else {
                $dt = $this
                    ->where('owl_prefix', $name)
                    ->oRwhere('owl_url', $uri)
                    ->find();
            }

        if (count($dt) == 0) {
            $data = array();
            $data['owl_prefix'] = $name;
            $data['owl_title'] = $name;
            $data['owl_url'] = $uri;
            $data['endpoint'] = $uri;
            $data['spaceName'] = $name;
            $id = $this->insert($data);
        } else {
            if ($dt[0]['owl_url'] == '') {
                $data['owl_url'] = $uri;
            }
            $data['spaceName'] = $name;
            $data['endpoint'] = $uri;
            $this->set($data)->where('owl_prefix', $name)->update();

            $id = $dt[0]['id_owl'];
        }
        return $id;
    }
}
