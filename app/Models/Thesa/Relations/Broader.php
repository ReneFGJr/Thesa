<?php

namespace App\Models\Thesa\Relations;

use CodeIgniter\Model;

class Broader extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_broader';
    protected $primaryKey       = 'id_b';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_b', 'b_th', 'b_concept_boader',
        'b_concept_narrow', 'b_concept_master', 'updated_at'
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

        $prop = get("type");
        if ($prop == '') {
            $prop = get("property");
            $prop = $ThProprity->getProperty($prop);
        }

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

    function le_broader($id)
        {
            $prop = 'prefLabel';
            $dt = $this
                ->join('thesa_concept_term', 'b_concept_boader = ct_concept')
                ->join('thesa_terms_th', 'b_concept_boader = term_th_concept')
                ->join('thesa_terms', 'term_th_term = id_term')
                ->join('language', 'id_lg = term_lang')
                //->where('p_name', $prop)
                ->where('b_concept_narrow', $id)
                ->findAll();

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

    function le_narrow($id)
    {
        $prop = 'prefLabel';
        $dt = $this
            ->join('thesa_concept_term', 'b_concept_narrow = ct_concept')
            ->join('thesa_terms_th', 'b_concept_narrow = term_th_concept')
            ->join('thesa_terms', 'term_th_term = id_term')
            ->join('language', 'id_lg = term_lang')
            //->where('p_name', $prop)
            ->where('b_concept_boader', $id)
            ->findAll();

        $dd = [];
        $di = [];
        foreach ($dt as $id => $line) {
            $idN = $line['b_concept_narrow'];
            $da = [];
            if (!isset($di[$idN])) {
                $da['id'] = $idN;
                $da['Term'] = $line['term_name'];
                $da['Lang'] = $line['lg_code'];
                $da['Prop'] = $line['term_lang'];
                $da['idReg'] = $line['id_b'];
                array_push($dd, $da);
                $di[$idN] = 1;
            }
        }
        return $dd;
    }

    function broader($id,$edit=false)
    {
        $dt = $this
            ->join('thesa_concept_term', 'ct_concept = b_concept_boader and ct_literal <> 0')
            ->join('thesa_terms', 'id_term = ct_literal')
            ->join('owl_vocabulary_vc', 'id_vc = ct_propriety')
            ->where('b_concept_narrow', $id)
            ->findAll();
        $sx = '';
        $c = [];
        foreach ($dt as $id => $line) {
            $icone = '';
            if (trim($line['vc_label']) == 'prefLabel')
            {
                $idcc = $line['b_concept_boader'];
                if (!isset($c[$idcc]))
                {

                    if ($edit == true) {
                        $icone = '<span class="text-danger me-2">' . bsicone('trash', 18) . '</span>';
                    }
                    $c[$idcc] = 1;
                $sx .= '<span class="ms-3 prefLabel">' . $icone . anchor(PATH . '/v/' . $line['ct_concept'], $line['term_name']) . '</span>';
                $sx .= '<br>';        }
            }
        }
        return $sx;
    }

    function narrow($id,$edit=false)
    {
        $dt = $this
            ->join('thesa_concept_term', 'ct_concept = b_concept_narrow and ct_literal <> 0')
            ->join('thesa_terms', 'id_term = ct_literal')
            ->join('owl_vocabulary_vc', 'id_vc = ct_propriety')
            ->where('b_concept_boader', $id)
            ->findAll();
        $sx = '';
        $c = [];
        foreach ($dt as $id => $line) {
            $icone = '';
            if (trim($line['vc_label']) == 'prefLabel') {
                $idcc = $line['b_concept_narrow'];
                //pre($line,false);
                if (!isset($c[$idcc])) {

                    if ($edit == true) {
                        $icone = '<span class="text-danger me-2">' . bsicone('trash', 18) . '</span>';
                    }
                    $c[$idcc] = 1;
                    $sx .= '<span class="ms-3 prefLabel">' . $icone . anchor(PATH . '/v/' . $line['ct_concept'], $line['term_name']) . '</span>';
                    $sx .= '<br>';
                }
            }
        }
        return $sx;
    }

    function register($th, $c1, $c2, $master)
    {
        $sx = '';

        $data['b_th'] = $th;
        $data['b_concept_boader'] = $c1;
        $data['b_concept_narrow'] = $c2;
        $data['b_concept_master'] = $master;
        $data['updated_at'] = date("Y-m-d H:i:s");

        $dt = $this
            ->where('b_concept_narrow', $c2)
            ->findAll();

        if (count($dt) == 0) {
            
            $this->set($data)->insert();
            $RSP = [];
            $RSP['status'] = '200';
            $RSP['message'] = 'OK';

            $Logs = new \App\Models\LogsModel();
            $description = 'Create Broader between concept '.$c1.' and '.$c2.' with property '.$c1;
            $Logs->registerLogs($data['b_th'], $data['b_concept_boader'], 'create_relation', $description);
        } else {
            $sx .= bsmessage("Já existe um TG", 3);
            $RSP['status'] = '400';
            $RSP['message'] = 'Broader Already exists';
            $RSP['id'] = $dt[0]['id_b'];
        }
        return $RSP;
    }

    function exist_broader($id,$th)
        {
        $dt = $this
            ->where('b_concept_narrow',$id)
            ->where('b_th',$th)
            ->findAll();
        if (count($dt) == 0)
            {
                return false;
            }
        return true;
        }

    function broader_candidate($th, $c)
    {
            if ($this->exist_broader($c,$th))
                {
                    $sx = bsmessage(lang('thesa.already_broader'),3);
                    return $sx;
                }

            $dc = $this->canditates_broader($th, $c);
            $concepts = [];
            foreach($dc as $id => $line)
                {
                    $dd = [];
                    $dd['id'] = $line['c_concept'];
                    $dd['Term'] = $line['term_name'];
                    $dd['Lang'] = '';
                    array_push($concepts,$dd);

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

    function canditates_broader($th, $id)
    {
        $tl1 = $this
            ->select('b_concept_narrow as idc')
            ->join('thesa_concept', 'c_concept = b_concept_boader', 'right')
            ->where('b_concept_boader',$id)
            ->where('c_th', $th)
            ->findAll();

        $tl2 = $this
            ->select('b_concept_boader as idc')
            ->join('thesa_concept', 'c_concept = b_concept_narrow', 'right')
            ->where('b_concept_narrow', $id)
            ->where('c_th', $th)
            ->findAll();
        /********************************/
        $Concept = new \App\Models\Thesa\Concepts\Index();
        $Concept->select('term_name, c_concept, ct_th');
        $Concept->join('thesa_concept_term', 'ct_concept = c_concept and ct_literal <> 0');
        $Concept->join('thesa_terms','id_term = ct_literal');
        foreach ($tl1 as $idx => $xline) {
            $Concept->where('c_concept <> ' . $xline['idc']);
        }
        foreach ($tl2 as $idx => $xline) {
            $Concept->where('c_concept <> ' . $xline['idc']);
        }
        $Concept->where('c_th', $th);
        $Concept->where('c_concept <> ' . $id);

        $Concept->orderby('term_name');
        $dt = $Concept->findAll();
        return $dt;
    }
}
