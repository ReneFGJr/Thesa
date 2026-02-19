<?php

namespace App\Models\Thesa;

use CodeIgniter\Model;

class Pajek extends Model
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

    function index($th,$type= 'net')
    {
        $mtx = [];
        $netContent = $this->exportToPajek($th, $mtx);
        switch($type)
            {
                case 'net':
                    echo $netContent;
                    exit;
                default:
                    echo view('Grapho/network3d', [
                        'netContent' => $netContent
                    ]);
                    exit;
        }
    }

    function exportToPajek(int $th)
    {

        $Broader = new \App\Models\Thesa\Relations\Broader();
        $relations =
            $Broader
            ->where('b_th',$th)
            ->findALl();

        $nodes = [];
        $edges = [];

        // Coleta todos os nós
        foreach ($relations as $rel) {
            $p = (int)$rel['b_concept_boader'];
            $c = (int)$rel['b_concept_narrow'];

            $nodes[$p] = true;
            $nodes[$c] = true;
        }

        $nodes = array_keys($nodes);
        sort($nodes);

        // Mapear ID real -> ID Pajek sequencial
        $map = [];
        $i = 1;
        foreach ($nodes as $nodeId) {
            $map[$nodeId] = $i++;
        }

        // Começa montar texto
        $txt  = "*Vertices " . count($nodes) . PHP_EOL;

        foreach ($nodes as $nodeId) {
            $name = $terms[$nodeId]['term_name'] ?? "Termo $nodeId";
            $name = str_replace('"', '', $name); // evita erro de aspas

            $txt .= $map[$nodeId] . ' "' . $name . '"' . PHP_EOL;
        }

        $txt .= PHP_EOL . "*Arcs" . PHP_EOL;

        foreach ($relations as $rel) {
            $p = (int)$rel['b_concept_boader'];
            $c = (int)$rel['b_concept_narrow'];

            if (isset($map[$p]) && isset($map[$c])) {
                $txt .= $map[$p] . " " . $map[$c] . PHP_EOL;
            }
        }

        return $txt;
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
}
