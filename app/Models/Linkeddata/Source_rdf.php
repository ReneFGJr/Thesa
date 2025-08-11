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

    function getType($url)
    {
        $urlN = $url;
        $RSP = [];
        $RSP['status'] = '400';
        $RSP['message'] = 'Invalid URL';
        if (strpos($url, '/c/') !== false ) {
            /************************ Thesa */
            if (strpos($url,':4200') !== false) {
                /******** Local */
                $url = troca($url, 'localhost:4200', 'thesa/api');
            } else {
                /******** Online */
                $url = troca($url, '/c/', '/api/c/');
                $url = troca($url, '/web','');
            }
            $RSP['status'] = '200';
            $RSP['message'] = 'Thesa - Concept found';
            $RSP['type'] = 'thesa';
            $RSP['url'] = $url;
        } else if (strpos($url, 'loterre.fr') !== false) {
            /****************************************** LOTERRE */
            /* http://data.loterre.fr/ark:/67375/9SD-TDN4545C-W */
            /* http://data.loterre.fr/ark:/67375/Q1W-MJMTQSSG-Q */
            /* https://loterre.istex.fr/rest/v1/Q1W/data?uri=http://data.loterre.fr/ark:/67375/Q1W-MJMTQSSG-Q&format=application/json */
            if (strpos($url,'ark:') !== false) {
                $uri = $url;
                $url = 'https://loterre.istex.fr/rest/v1/Q1W/data?uri=' . $uri . '&format=application/json';
                $RSP['status'] = '200';
                $RSP['message'] = 'Loterre - ARK found';
                $RSP['type'] = 'loterre';
                $RSP['url'] = $url;
            }

        } else if (strpos($url, 'vocabularies.unesco.org') !== false) {
                /* UNESCO Thesaurus */
                /* http://vocabularies.unesco.org/thesaurus/concept2 */
                /* https://vocabularies.unesco.org/browser/rest/v1/thesaurus/data?uri=http%3A%2F%2Fvocabularies.unesco.org%2Fthesaurus%2Fconcept2&format=application/ld%2Bjson */
                $uri = $url;
                $url = 'https://vocabularies.unesco.org/browser/rest/v1/thesaurus/data?uri=' . $uri . '&format=application/json';
                $RSP['status'] = '200';
                $RSP['message'] = 'Unesco Thesaurus - Concept found';
                $RSP['type'] = 'unesco';
                $RSP['url'] = $url;
        } else if (strpos($url, 'lod.nal.usda.gov') !== false) {
            /* UNESCO Thesaurus */
            /* https://lod.nal.usda.gov/nalt/en/page/17411 */
            /* https://lod.nal.usda.gov/rest/v1/nalt/data?uri=https%3A%2F%2Flod.nal.usda.gov%2Fnalt%2F17411&format=application/ld%2Bjson */
            $uri = $url;
            $url = 'https://lod.nal.usda.gov/rest/v1/nalt/data?uri=' . $uri . '&format=application/json';
            $RSP['status'] = '200';
            $RSP['message'] = 'Usda Thesaurus - Concept found';
            $RSP['type'] = 'usda.gov';
            $RSP['url'] = $url;
        } else if (strpos($url,'/rest/v1/') !== false) {
            $RSP['status'] = '500';
            $RSP['message'] = 'Invalid URL Linked Data';
        }
        return $RSP;
    }

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
