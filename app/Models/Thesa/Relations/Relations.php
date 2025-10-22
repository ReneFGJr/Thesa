<?php

namespace App\Models\Thesa\Relations;

use CodeIgniter\Model;

class Relations extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_concept_term';
    protected $primaryKey       = 'id_ct';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_ct',
        'ct_th',
        'ct_concept',
        'ct_propriety',
        'ct_concept_2',
        'ct_resource',
        'ct_concept_2_qualify',
        'ct_literal',
        'ct_use'
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

    function relateConcept()
    {
        $ThProprity = new \App\Models\RDF\ThProprity();

        $prop = get("property");
        $prop = $ThProprity->getProperty($prop);


        $RSP = [];
        $dd['ct_th'] = get("thesaurus");
        $dd['ct_concept'] = get("c1");
        $dd['ct_concept_2'] = get("c2");
        $dd['ct_propriety'] = $prop;
        $dd['ct_concept_2_qualify'] = 0;
        $dd['ct_literal'] = 0;
        $dd['ct_resource'] = 0;
        $dd['ct_use'] = 0;
        $RSP = $this->register($dd);
        return $RSP;
    }

    function register($data)
    {
        $RSP = [];
        $prop = $data['ct_propriety'];
        if (sonumero($prop) != $prop) {
            $RSP['status'] = '400';
            $RSP['message'] = 'Property not valid';
            return $RSP;
        }

        $dt = $this
            ->where('ct_th', $data['ct_th'])
            ->where('ct_concept', $data['ct_concept'])
            ->where('ct_concept_2', $data['ct_concept_2'])
            ->first();

        if ($dt) {
            $RSP['status'] = '400';
            $RSP['message'] = 'Relation already exists';
            return $RSP;
        } else {
            $RSP['status'] = '200';
            $RSP['message'] = 'Relation created';
            $RSP['id'] = $this->set($data)->insert();

            $Logs = new \App\Modules\Logs\Models\LogsModel();
            $description = 'Create relation between concept '.$data['ct_concept'].' and '.$data['ct_concept_2'].' with property '.$data['ct_propriety'];
            $Logs->registerLogs($data['ct_th'], $data['ct_concept'], 'create_relation', $description    );
        }
        return $RSP;
    }

    function le_relations($id)
        {
            $Related = new \App\Models\Thesa\Relations\Related();
            $cp = 'r_c2 as id, term_name as Term, lg_code as Lang, rp_achronic as Prop, id_r as idReg';
            $dt1 = $Related
                ->select($cp)
                ->join('thesa_related_property', 'r_property = id_rp')
                ->join('thesa_concept_term', 'r_c2 = ct_concept AND ct_propriety = 2')
                ->join('thesa_terms', 'ct_literal = id_term')
                ->join('language', 'id_lg = term_lang')
                ->where('r_c1', $id)
                ->findAll();

            $cp = 'r_c1 as id, term_name as Term, lg_code as Lang, rp_achronic_reverse as Prop, id_r as idReg';
            $dt2 = $Related
                ->select($cp)
                ->join('thesa_related_property', 'r_property = id_rp')
                ->join('thesa_concept_term', 'r_c1 = ct_concept AND ct_propriety = 2')
                ->join('thesa_terms', 'ct_literal = id_term')
                ->join('language', 'id_lg = term_lang')
                ->where('r_c2', $id)
                ->findAll();

            $dt = array_merge($dt1, $dt2);
            foreach( $dt as $k => $v ) {
                $dt[$k]['Label'] = lang('app.'. $v['Prop']);
            }


            return $dt;
        }


}
