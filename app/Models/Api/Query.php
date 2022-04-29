<?php

namespace App\Models\Api;

use CodeIgniter\Model;

class Query extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'queries';
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

    function uri($d1,$d2,$d3,$uri)
        {
            $ThConcept = new \App\Models\Thesaurus\ThConcept();

            $id = '';
            $id = substr($uri, strpos($uri, 'v/') + 2,strlen($uri));
            $dt = $ThConcept->le($id);
            $prefLabel = $dt['n_name'];
            $th = $dt['c_th'];
            header("Content-type: text/xml");            
            $sx = '<'.'?xml version="1.0" encoding="utf-8" ?'.'>';
            $sx .= '
            <rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
                     xmlns:skos="http://www.w3.org/2004/02/skos/core#"
                     xmlns:ns0="http://purl.org/umu/uneskos#"
                     xmlns:isothes="http://purl.org/iso25964/skos-thes#"
                     xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#">
            
              <skos:ConceptScheme rdf:about="'.PATH.MODULE.'th/'.$th.'">
                <skos:prefLabel xml:lang="br">'.$prefLabel.'</skos:prefLabel>
                <ns0:hasMicroThesaurus rdf:resource="'.PATH.MODULE.'v/'.$id.'"/>
              </skos:ConceptScheme>
            </rdf:RDF>';
            echo $sx;
            exit;
        }

    function rest($thName='',$act,$ver)
        {
            $ThLiteralTh = new \App\Models\Thesaurus\ThLiteralTh();
            $ThThesaurus = new \App\Models\Thesaurus\ThThesaurus();
            $API = new \App\Models\Api\Json();
            $th = $ThThesaurus->getAchronic($thName);
            
            if ($th == 0)
                {
                    $dt['status'] = 'error';
                    $dt['erro'] = '500';
                    $dt['message'] = lang('thesa.error500');
                    $dt['stamp'] = date('Y-m-d H:i:s');
                    $dt['act'] = $act;
                    $dt['ver'] = $ver;
                    $dt['thName'] = $thName;
                    $dt['th'] = $th;
                    echo json_encode($dt);
                    exit;
                } else {                
                    $q = get("query");
                    $q = troca($q,'*','');
                    if (($q != '') and ($th > 0))
                        {
                        $dt = $ThLiteralTh->search($q,$th); 
                        $API->index('search',$dt,$th,$thName,$q);
                        }
                }

        }
}
