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

    function net($dt)
        {
            $pref = $dt['concept'];
            $data = (array)$dt['data'];
            
            $idn = $pref['id_c'];
            $viz = array();
            $tot = count($data);
            for($r=0;$r < $tot;$r++)
                {                    
                    $line = (array)$data[$r];                    
                    $prop = trim((string)$line['rs_propriety']);
                    $gr = $line['rs_group'];

                    switch($prop)
                        {
                            case 'prefLabel':
                                $viz['pref'][$line['n_lang']] = array($line['n_name'],$line['n_lang'],$line['ct_th'],$line['id_ct']);
                                break;
                        }
                }
            /************************ NODES */
            $nodes = '';

            /************************ Pref Languages */
            $lg = array('por','eng','esp','fra');

            /***************************************** PrefTerm */
            $idm = 0;
            $title = 'uri:'.$idn.cr();
            for ($r=0;$r < count($lg);$r++)
                {
                    if (isset($viz['pref'][$lg[$r]]))
                        {
                            if ($idm == 0)
                            {
                                $idm = $viz['pref'][$lg[$r]][3];
                                $nodes = '{id: '.$idm.', label: "'. $viz['pref']['por'][0].'", $title },';
                            } else {
                                $title .= $viz['pref'][$lg[$r]][0].'-'.$lg[$r].cr();
                            }
                        } else {
                            
                        }
                }
            if ($title != '')
                {
                    $title = 'title: "'.trim($title).'"';                    
                }
            $nodes = troca($nodes,'$title',$title);

            if ($idm==0)
                {
                    echo "OPENING: ";
                    pre($viz);
                }   

            /************************ EDGES */
            $edges = '';

            /********************************************** Relations */
            for ($r=0;$r < count($data);$r++)
                {
                    $line = (array)$data[$r];
                    $title = '';
                    if ($line['ct_use'] > 0)
                        {
                            $title = ', title:"URI:'.$line['ct_use'].'"';
                        }
                    
                    $gr = $line['rs_group'];
                    switch($gr)
                        {
                            case 'FE':
                                $nodes .= '{id: '.$line['id_ct'].', label: "'.trim($line['n_name']).'" '.$title.'},';
                                $edges .= '{from: '.$idm.', to: '.$line['id_ct'].', label: "'.$line['rs_propriety'].'"},';
                                break;
                            case 'TG':
                                $nodes .= '{id: '.$line['id_ct'].', label: "'.trim($line['n_name']).'" '.$title.'},';
                                $edges .= '{from: '.$idm.', to: '.$line['id_ct'].', label: "'.$line['rs_propriety'].'"},';
                                break;   
                            case 'TE':
                                $nodes .= '{id: '.$line['id_ct'].', label: "'.trim($line['n_name']).'" '.$title.'},';
                                $edges .= '{from: '.$idm.', to: '.$line['id_ct'].', label: "'.$line['rs_propriety'].'"},';
                                break;                                                             
                            case 'LABEL':
                                break;
                            default:
                            pre($data);
                                echo '=IZ=>'.$gr;
                        }
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
