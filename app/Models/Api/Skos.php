<?php

namespace App\Models\Api;

use CodeIgniter\Model;

class Skos extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'skos';
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

    function index($d1='',$d2='',$d3='',$d4='',$d5='')
    {
    }

    function skos_conceptScheme($th=0)
        {
            $sx = '';
            $sx .= '<skos:ConceptScheme rdf:about="http://skos.um.es/unescothes/CS000">';
            $sx .= '<skos:prefLabel xml:lang="en">UNESCO Thesaurus</skos:prefLabel>';
            $sx .= '</skos:ConceptScheme>';

            return $sx;
        }
    function skos_concept($id=0,$th=0)
        {
            $sx = '';
            $sx = '<skos:Concept rdf:about="http://skos.um.es/unescothes/C01792">';
            $sx .= '<skos:prefLabel xml:lang="en">Higher education institutions</skos:prefLabel>';
            $sx .= '<skos:altLabel xml:lang="es">Decanato</skos:altLabel>';
            $sx .= '<skos:related rdf:resource="http://skos.um.es/unescothes/C00009"/>';

            $sx .= $this->skos_related($id,$th);

            $sx .= '</skos:Concept>';
            return $sx;
        }
    function skos_related($id=0,$th=0)
        {
            $sx = '<skos:related rdf:resource="http://skos.um.es/unescothes/C01792"/>';
            returb $sx;
        }
    function skos_narrower($id=0,$th=0)
        {
            $sx = '<skos:narrower rdf:resource="http://skos.um.es/unescothes/C01792"/>';
            returb $sx;
        }  

    function skos_broader($id=0,$th=0)
        {
            $sx = '<skos:broader rdf:resource="http://skos.um.es/unescothes/C01792"/>';
            returb $sx;
        }               
}
