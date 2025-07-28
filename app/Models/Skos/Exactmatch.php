<?php

namespace App\Models\Skos;

use CodeIgniter\Model;

class Exactmatch extends Model
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

    function index()
    {
        $SourceLinkedData = new \App\Models\Linkeddata\Source_rdf();
        $link = get('URI');
        $thesaID = get('thesaID');
        if ($thesaID == '') {
            $RSP['status'] = '500';
            $RSP['message'] = 'Thesaurus ID not provided.';
            return $RSP;
        }
        if (strpos($link, '#') > 0) {
            $link = substr($link, 0, strpos($link, '#'));
        }
        $RSP = [];
        $RSP['data'] = $_POST;
        $RSP['link'] = $link;
        if ($link != '') {
            $RSP['link'] = $link;
            $dt = $this
                ->join('thesa_concept', 'em_concept = id_c')
                ->where('em_link', $link)
                ->where('c_th',$thesaID)
                ->first();
            if ($dt) {
                $RSP['linkedata'] = $dt;
                $RSP['status'] = '500';
                $RSP['message'] = 'Linked Data already exists.';
            } else {
                $ds = $SourceLinkedData->source($link);
                $dd = [];
                $dd['em_link'] = $link;
                $dd['em_concept'] = get("conceptID");
                $dd['em_visible'] = 1;
                $dd['em_source'] = $ds['id_lds']; // Default source, can be changed later
                $this->set($dd)->insert();
                $RSP['status'] = '200';
                $RSP['message'] = 'Linked Data added successfully.';
            }
        }
        return $RSP;
    }

    function deleteExactMatch()
    {
        $id = get('id_em');
        if ($id) {
            $this->where('id_em', $id)->delete();
            return ['status' => 'success', 'message' => 'Linked Data deleted successfully.'];
        } else {
            return ['status' => 'error', 'message' => 'ID not provided.'];
        }
    }

    function le($id)
    {
        $cp = 'lds_icone, lds_name, em_link, id_em, em_concept, em_visible';

        $link = [];
        $dt = $this
            ->select($cp)
            ->join('thesa_linked_data_source', 'em_source = id_lds')
            ->where('em_concept', $id)
            ->findAll();

        foreach ($dt as $id => $d) {
            $Term = troca($d['em_link'], 'http://', '');
            $Term = troca($Term, 'https://', '');
            $dd = [];
            $dd['Prop'] = 'Exact Match';
            $dd['id'] = $d['em_concept'];
            $dd['Term'] = $Term;
            $dd['Icone'] = $d['lds_icone'];
            $dd['Url'] = $d['em_link'];
            $dd['Lang'] = 'nn';
            $dd['idReg'] = $d['id_em'];
            array_push($link, $dd);
        }

        return $link;
    }
}
