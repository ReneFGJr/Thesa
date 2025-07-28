<?php

namespace App\Models\Linkeddata;

use CodeIgniter\Model;

class Source_rdf extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_linked_data_source';
    protected $primaryKey       = 'id_ld';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['lds_icone','lds_name','lds_url'];

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

    function source($link)
        {
            $domain = $this->extrairDominio($link);

            $dt = $this->where('lds_url', $domain)->first();
            $icone = '/assets/icone/diagram-3-fill.svg';
            if ($dt)
            {
                $icone = $dt['lds_icone'];
            } else {
                $dd = [];
                $dd['lds_url'] = $domain;
                $dd['lds_name'] = $domain;
                $dd['lds_icone'] = $icone;
                $this->set($dd)->insert();
                $dt = $this->where('lds_url', $domain)->first();
            }
            return $dt;
        }

    function extrairDominio($url)
    {
        $host = parse_url($url, PHP_URL_HOST);
        return $host;
    }
}
