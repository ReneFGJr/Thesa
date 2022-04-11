<?php

namespace App\Models\Graph;

use CodeIgniter\Model;

class Viz extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'vizs';
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

    function net($graph)
        {
            /************************ EDGES */
            $edges = '';
            $nodes = '';
            $data = array();

            for ($r=0;$r < count($graph['nodes']);$r++) {
                $line = $graph['nodes'][$r];
                $nodes .= '{id: '.$line['id_ct'].', label: "'.trim($line['n_name']).'"},';                
            }

            for ($r=0;$r < count($graph['edges']);$r++) {
                $line = $graph['edges'][$r];
                $edges .= '{from: '.$line['source'].', to: '.$line['target'].', label: "'.$line['propriety'].'"},';
            }

            $sx = '<div id="mynetwork" style="border: 1px solid #aaa; height: 400px;"></div>';

            $js = '
            <script type="text/javascript" src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js"></script>
            <script type="text/javascript">
            // create an array with nodes
            var nodes = new vis.DataSet([
                '.$nodes.'
            ]);
            // create an array with edges
            var edges = new vis.DataSet([
                '.$edges.'
            ]);

            // create a network
            var container = document.getElementById("mynetwork");

            // provide the data in the vis format
            var data = {
                nodes: nodes,
                edges: edges
            };
            var options = {};

            // initialize your network!
            var network = new vis.Network(container, data, options);
        </script>';

        return $sx.$js;
        }
}
