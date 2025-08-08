<?php

namespace App\Models\Thesa;

use CodeIgniter\Model;

class Systematic extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_descriptions';
    protected $primaryKey       = 'id_ds';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_ds',
        'ds_prop',
        'ds_descrition',
        'ds_th',
        'created_at',
        'updated_at'
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
    public $terms = [];

    function index($th)
    {
        $mtx = [];
        $RSP = [];
        $RSP['thesa'] = $th;
        $RSP['topConcepts'] = $this->topConcepts($th, $mtx);
        $RSP['map'] = $this->getTopConcepts($th, $mtx);
        return $RSP;
    }

    function buildForest(array $relations): array
    {
        $parentKey = 'b_concept_boader'; // <- do seu array
        $childKey  = 'b_concept_narrow';


        $children  = [];
        $hasParent = [];
        $nodes     = [];

        foreach ($relations as $rel) {
            $p = (string)$rel[$parentKey];
            $c = (string)$rel[$childKey];

            $children[$p][] = $c;
            $hasParent[$c]  = true;
            $nodes[$p] = true;
            $nodes[$c] = true;
        }

        // normaliza e ordena a lista de filhos
        foreach ($children as &$list) {
            $list = array_values(array_unique($list));
            sort($list, SORT_NATURAL | SORT_FLAG_CASE);
        }
        unset($list);

        // raízes = nós que nunca foram filhos
        $roots = array_values(array_diff(array_keys($nodes), array_keys($hasParent)));

        // monta recursivamente (com proteção contra ciclos)

        $build = function (string $id, array $stack = []) use (&$build, $children): array {
            if (isset($stack[$id])) {
                return ['id' => $id, 'children' => [], 'cycle' => true]; // se houver ciclo inesperado
            }
            $stack[$id] = true;

            $node = [
                'id' => $id,
                'name'=> $this->terms[$id]['term_name'],
                'lang'=>$this->terms[$id]['lg_cod_marc'],
                'children' => []];
            foreach ($children[$id] ?? [] as $childId) {
                $node['children'][] = $build($childId, $stack);
            }
            return $node;
        };

        $forest = [];
        foreach ($roots as $r) {
            $forest[] = $build($r);
        }
        return $forest;
    }

    function getTopConcepts($th, $mtx)
    {
        $Broader = new \App\Models\Thesa\Relations\Broader();

        /* Terms */
        $Concept = new \App\Models\Thesa\Concepts\Index();
        $cp = 'lg_cod_marc, term_name, id_c as ID';
        $dt = $Concept->select($cp)
            ->join('thesa_concept_term', 'ct_concept = c_concept')
            ->join('thesa_terms', 'ct_literal = id_term')
            ->join('language', 'id_lg = term_lang')
            ->where('c_th', $th)
            ->findAll();
        $terms = [];
        $hq = [];
        foreach ($dt as $row) {
            if (!isset($terms[$row['ID']])) {
                $terms[$row['ID']] = $row;
                $hq[$row['ID']] = [];
            }
        }

        $cc = [];
        $cp = 'b_concept_boader, b_concept_narrow';
        $dtb = $Broader
            ->select($cp)
            ->where('b_th', $th)
            ->orderBy('b_concept_boader')
            ->findAll();
        $this->terms = $terms;
        $RSP = $this->buildForest($dtb);
        return $RSP;
    }

    function topConcepts($th)
    {
        $db = \Config\Database::connect();
        $dt = [];

        return $dt;
    }
}
