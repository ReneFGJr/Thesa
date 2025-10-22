<?php

namespace App\Models\Thesa\Relations;

use CodeIgniter\Model;

class Related extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_related';
    protected $primaryKey       = 'id_r';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_r', 'r_th', 'r_c1',
        'r_c2', 'r_property', 'updated_at'
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

    function getRelations($th)
        {
        $RelationsGroup = new \App\Models\Thesa\Relations\RelationsGroup();
        $dt = $RelationsGroup->getGroup($th);
        return $dt;
        }

    function relateConcept()
    {
        $ThProprity = new \App\Models\RDF\ThProprity();

        $prop = get("property");
        $prop = $ThProprity->getProperty($prop);

        $RSP = [];
        $dd['b_th'] = get("thesaurus");
        $dd['b_concept_narrow'] = get("c1");
        $dd['b_concept_boader'] = get("c2");
        $dd['b_property'] = $prop;
        $th = $dd['b_th'];
        $c1 = $dd['b_concept_boader'];
        $c2 = $dd['b_concept_narrow'];
        $master = 0;

        $RSP = $this->register($th, $c1, $c2, $master);
        return $RSP;
    }

    function show($id,$edit=false)
    {
        $sx = '';
        $sx .= $this->broader($id,$edit);
        $sx .= $this->narrow($id, $edit);
        return $sx;
    }

    function le_related($id)
        {
            $prop = 'prefLabel';
            $dt = $this
                ->join('thesa_concept_term', 'b_concept_boader = ct_concept')
                ->join('thesa_terms_th', 'b_concept_boader = term_th_concept')
                ->join('thesa_terms', 'term_th_term = id_term')
                ->join('language', 'id_lg = term_lang')
                ->where('ct_concept <> '.$id)
                ->findAll();
                echo $this->getLastQuery();
                exit;

            $dd = [];
            $di = [];
            foreach($dt as $id=>$line)
                {
                    $idN = $line['b_concept_boader'];
                    $da = [];
                    if (!isset($di[$idN]))
                        {
                        $da['id'] = $idN;
                        $da['Term'] = $line['term_name'];
                        $da['Lang'] = $line['lg_code'];
                        $da['Prop'] = $line['term_lang'];
                        $da['idReg'] = $line['id_b'];
                        array_push($dd,$da);
                        $di[$idN] = 1;
                        }
                    }
           return $dd;
        }



    function register($th, $c1, $c2, $type)
    {
        $sx = '';

        $data['r_th'] = $th;
        $data['r_c1'] = $c1;
        $data['r_c2'] = $c2;
        $data['r_property'] = $type;
        $data['updated_at'] = date("Y-m-d H:i:s");

        $dt = $this
            ->where('r_c1', $c2)
            ->where('r_c2', $c1)
            ->findAll();

        if (count($dt) == 0) {
            pre($data);
            $this->set($data)->insert();
            $RSP = [];
            $RSP['status'] = '200';
            $RSP['message'] = 'OK';

            $Logs = new \App\Models\LogsModel();
            $Description = "Related (Broader): ".$c1." with ".$c2;
            $Logs->registerLogs($th, $c1, 'add_broader', $Description);
            $Description = "Related (Narrow): ".$c1." with ".$c2;
            $Logs->registerLogs($th, $c2, 'add_narrow', $Description);
        } else {
            $sx .= bsmessage("Já existe um TR", 3);
            $RSP['status'] = '400';
            $RSP['message'] = 'Related already exists';
            $RSP['id'] = $dt[0]['id_r'];
        }
        return $RSP;
    }



    function related_candidate($th, $c)
    {
        $Relations = new \App\Models\Thesa\Relations\Relations();
        $dc = $Relations
            ->select('*')
            ->join('thesa_terms', 'ct_literal = id_term')
            ->join('language', 'id_lg = term_lang')
            ->where('ct_th', $th)
            ->where('ct_concept <> '.$c)
            ->orderBy('term_name')
            ->findAll();
        $concepts = [];
        foreach ($dc as $id => $line) {
            $dd = [];
            $dd['id'] = $line['ct_concept'];
            $dd['Term'] = $line['term_name'].' ('.$line['lg_code'].')';
            $dd['Lang'] = '';
            array_push($concepts, $dd);
        }

        $RSP = [];
        $RSP['status'] = '200';
        $RSP['message'] = 'OK';
        $RSP['Terms'] = $concepts;
        $RSP['th'] = $th;
        $RSP['concept'] = $c;
        $RSP['time'] = date("Y-m-dTH:i:s");
        return $RSP;
    }

}
