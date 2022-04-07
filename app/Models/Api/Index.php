<?php

namespace App\Models\Api;

use CodeIgniter\Model;

class Index extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'indices';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

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

    ///http://api.finto.fi/rest/v1/search?query=category:kunta&lang=fi&max=100&offset=0&sort=name

    function index($d1='',$d2='',$d3='',$d4='',$d5='')
        {
            
        }

    function rdf($id,$tp='')
        {
            $sql = "
                select * from th_concept_term 
                    inner join rdf_class ON ct_propriety = id_c
                    left join rdf_prefix ON rdf_class.c_prefix = id_prefix
                    left join rdf_literal ON ct_term = id_rl
                    where ct_concept = '$id' 
                        OR ct_concept_2 = '$id'";
            $dt = $this->query($sql)->getResult();
            $pref = array();
            $alter = array();
            $hidden = array();
            for ($r=0;$r < count($dt);$r++)
                {
                    $line = (array)$dt[$r];
                    $lang = $line[''];
                    echo '===>'.$line['id_ct'].'==';
                    switch($line['ct_propriety'])
                        {
                            case 25:
                            echo 'Term=>'.$line['rl_value'].'<br>';
                            array_pref[$lang] = '<skos:Concept rdf:about="http://vocabularies.unesco.org/thesaurus/concept6676">';
                            break;

                            case 34:
                                echo 'Alias=>'.$line['rl_value'].'<br>';
                                break;                            
        
                            default:
                            echo '==>'.$line['rl_value'].'=='.$line['ct_concept_2'].'<br>';
                            pre($line);
                        }
                }
                exit;

            

            $rdf = '<rdf>';
            $rdf .= $Skos->skos_concept($id);
            $rdf .= '</rdf>';
            pre($rdf);
        }
}
