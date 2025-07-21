<?php

namespace App\Models\Linkeddata;

use CodeIgniter\Model;

class Index extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_linked_data';
    protected $primaryKey       = 'id_ld';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'ld_url', 'ld_concept', 'ld_visible', 'ld_source'
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

    function index()
    {
        $SourceLinkedData = new \App\Models\Linkeddata\Source_rdf();
        $link = get('URI');
        if (strpos($link, '#') > 0)
        {
            $link = substr($link, 0, strpos($link, '#'));
        }
        $RSP = [];
        $RSP['data'] = $_POST;
        $RSP['link'] = $link;
        if ($link != '')
            {
                $RSP['link'] = $link;
                $dt = $this->where('ld_url', $link)->first();
                if ($dt)
                {
                    $RSP['linkedata'] = $dt;
                } else {
                    $ds = $SourceLinkedData->source($link);
                    $dd = [];
                    $dd['ld_url'] = $link;
                    $dd['ld_concept'] = get("conceptID");
                    $dd['ld_visible'] = 1;
                    $dd['ld_source'] = $ds['id_lds']; // Default source, can be changed later
                    $this->set($dd)->insert();
                }
            }
        return $RSP;
    }

    function le($id)
    {
        $cp = 'lds_icone, lds_name, ld_url, id_ld, ld_concept, ld_visible';

        $link = [];
        $dt = $this
            ->select($cp)
            ->join('thesa_linked_data_source', 'ld_source = id_lds')
            ->where('ld_concept', $id)
            ->findAll();

        foreach($dt as $id=>$d)
            {
                $dd = [];
                $dd['Prop'] = 'Linked Data';
                $dd['id'] = $d['ld_concept'];
                $dd['Term'] = $d['lds_name'];
                $dd['Icone'] = $d['lds_icone'];
                $dd['Url'] = $d['ld_url'];
                $dd['Lang'] = 'nn';
                $dd['idReg'] = $d['id_ld'];
                array_push($link, $dd);
            }

        return $link;
    }
}
