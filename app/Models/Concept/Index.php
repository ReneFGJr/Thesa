<?php

namespace App\Models\Concept;

use CodeIgniter\Model;

class Index extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_concept';
    protected $primaryKey       = 'id_c';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_c','c_concept','c_th','c_ativo','c_agency',
        'c_updated'
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
    public $idC = 0;

    function checkParamets($parms, $dt)
        {
            foreach($parms as $p)
                {
                    if (!isset($dt[$p]))
                        {
                            return false;
                        }
                }
            return true;
        }


    function getConceptByNameV2($dt)
        {
            $parms = ['term','thesaID'];
            if ($this->checkParamets($parms, $dt) == false)
                {
                    $RSP['message'] = 'Parâmetros necessários: '.implode(', ', $parms);
                    $RSP['status'] = '500';
                    return $RSP;
                }
             foreach($parms as $p)
                {
                    if (!isset($dt[$p]))
                        {
                            $RSP['message'] = 'Parâmetro "'.$p.'" não foi informado';
                            $RSP['status'] = '500';
                            return $RSP;
                        }
                }

            $result = $this->db->table('thesa_concept_term')
                ->select('*')
                ->join('thesa_terms', 'thesa_terms.id_term = thesa_concept_term.ct_literal', 'INNER')
                ->where('thesa_concept_term.ct_th', $dt['thesaID'])
                ->where('thesa_terms.term_name', $dt['term'])
                ->get()
                ->getRowArray();
            return $result;
        }

    function createConceptV2($dt)
        {
            $RSP = [];
            $Logs = new \App\Models\LogsModel();
            $TermsTh = new \App\Models\Term\TermsTh();
            $RDFproprity = new \App\Models\RDF\ThProprity();
            $ThTerm = new \App\Models\RDF\ThTerm();

            $parms = ['term', 'thesaID','apiKey','lang'];
            if ($this->checkParamets($parms, $dt) == false) {
                $RSP['message'] = 'Parâmetros necessários: ' . implode(', ', $parms);
                $RSP['status'] = '500';
                return $RSP;
            }

            $isBroader = false;
            if ((isset($dt['broader']) and ($dt['broader'] != '')))
                {
                    $broader = $this->getConceptByNameV2(['term' => $dt['broader'], 'thesaID' => $dt['thesaID']]);
                    if ($broader == [])
                        {
                            $RSP['message'] = 'Termo mais amplo "'.$dt['broader'].'" não encontrado';
                            $RSP['status'] = '500';
                            return $RSP;
                        }
                     $isBroader = true;
                }

            /*********************** Cria ou recupera ID do termo */
            $Term = new \App\Models\Term\Index();
            $idTerm = $Term->registerTermV2($dt);

            /*********************** Registra o conceito */
            $idC = $this->createConcept($idTerm, $dt['thesaID']);
            pre($idC);

            return $RSP;
        }

    function createConcept($id,$th)
        {
            $RSP = [];
            $Logs = new \App\Models\LogsModel();
            $TermsTh = new \App\Models\Term\TermsTh();
            $RDFproprity = new \App\Models\RDF\ThProprity();
            $ThTerm = new \App\Models\RDF\ThTerm();

            $classID = $RDFproprity->getClass('prefLabel');
            $RSP['class'] = $classID;

            $dt = $TermsTh
                    ->where('term_th_thesa',$th)
                    ->where('term_th_term', $id)
                    ->first();

            if ($dt == [])
                {
                    $dd = [];
                    $dd['term_th_term'] = $id;
                    $dd['term_th_thesa'] = $th;
                    $dd['term_th_concept'] = 0;
                    $dd['term_th_status'] = 1;
                    $TermsTh->set($dd)->insert();
                    $dt = $TermsTh
                        ->where('term_th_thesa',$th)
                        ->where('term_th_term', $id)
                        ->first();
                }

            if ($dt['term_th_concept'] == 0)
                {
                    $dc = [];
                    $dc['c_th'] = $th;
                    $dc['c_ativo'] = 1;
                    $dc['c_agency'] = 'thesa2:'.$id;

                    $dd = $this->where('c_agency',$dc['c_agency'])->first();
                    if ($dd == [])
                        {
                            $idC = $this->set($dc)->insert();
                            $dd = $this->where('c_agency', $dc['c_agency'])->first();
                            $this->set('c_concept',$idC)->where('id_c',$idC)->update();
                        } else {
                            $idC = $dd['id_c'];
                        }

                    $RSP['concept'] = $idC;
                    $this->idC = $idC;


                    /*********** PrefLabel */
                    $prop = $RDFproprity->getClass('prefLabel');
                    $ThConceptPropriety = new \App\Models\RDF\ThConceptPropriety();
                    $da = $ThConceptPropriety
                        ->where('ct_th',$th)
                        ->where('ct_concept',$idC)
                        ->where('ct_propriety',$prop)
                        ->first();


                    if ($da == [])
                        {
                            $dd = [];
                            $dd['ct_th'] = $th;
                            $dd['ct_concept'] = $idC;
                            $dd['ct_concept_2'] = 0;
                            $dd['ct_propriety'] = $prop;
                            $dd['ct_use'] = 0;
                            $dd['ct_literal'] = $id;
                            $dd['ct_resource'] = 0;
                            $ThConceptPropriety->set($dd)->insert();


                            /******************* LOGS */
                            $term = $ThTerm->find($id);
                            $description = 'Create concept "'.$term['term_name'].'" ('.$idC.')';
                            $Logs->registerLogs($th, $this->idC, 'create_concept', $description);

                        } else {
                            if ($da['ct_literal'] == 0)
                                {
                                    $ThConceptPropriety->set('ct_literal',$id)->where('id_ct',$da['id_ct'])->update();
                                }
                        }

                    $dd = [];
                    $dd['term_th_concept'] = $idC;
                    $TermsTh->set($dd)->where('term_th_id',$dt['term_th_id'])->update();
                    return("Termo criado com sucesso: ".$idC);
                } else {
                    return('Termo já existe: ' . $id.' - '.$th);
                }
        }

    function createConceptAPI($dt)
        {
            $RSP['result'] = '';
            if (!isset($dt['terms']))
                {
                    $RSP['message'] = 'Termos não foram informados';
                    $RSP['status'] = '500';
                    return $RSP;
                }
            if (!isset($dt['thesaID']))
                {
                    $RSP['message'] = 'Thesaurus não foi informado';
                    $RSP['status'] = '500';
                    return $RSP;
                }
            $itens = $dt['terms'];
            $RSP['xterms'] = $itens;
            $th = $dt['thesaID'];
            $RSP['terms'] = explode(',',$itens);

            foreach($RSP['terms'] as $id=>$item)
                {
                    foreach($RSP['terms'] as $id=>$item)
                        {
                            $RSP['result'] .= $this->createConcept($item, $th);
                            $RSP['status'] = '200';
                        }
                }
            return $RSP;
        }

    function updateConcept($IDc)
        {
            $dt['c_updated'] = date('Y-m-d');
            $this->set($dt)->where('id_c',$IDc)->update();
        }

    function findAgent($agent,$th)
        {
            $this->where('c_agency',$agent);
            $this->where('c_th', $th);
            $dt = $this->first();
            return($dt['id_c']);
        }

    function registerInport($ag,$th)
        {
            $ag = troca($ag,'#','');
            $ag = mb_strtolower($ag);

            $dt = $this
            ->where('c_agency',$ag)
            ->where('c_th', $th)
            ->first();
            if ($dt == [])
                {
                    $dd = [];
                    $dd['c_th'] = $th;
                    $dd['c_agency'] = $ag;
                    $dd['c_concept'] = 0;
                    $dd['c_ativo'] = 1;
                    $id  = $this->set($dd)->insert();
                    $dd['c_concept'] = $id;
                    $this->set($dd)->where('id_c',$id)->update();
                    $this->updateConcept($id);
                } else {
                    $id = $dt['id_c'];
                }
            return $id;
        }
}
