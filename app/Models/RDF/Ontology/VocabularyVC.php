<?php

namespace App\Models\RDF\Ontology;

use CodeIgniter\Model;

class VocabularyVC extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'owl_vocabulary_vc';
    protected $primaryKey       = 'id_vc';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_vc', 'vc_rdf_type', 'vc_notation',
        'vc_label', 'vc_url', 'vc_definition',
        'vc_scopeNote', 'vc_example', 'created_at',
        'updated_at', 'vc_prefix', 'vc_resource'
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

    function view($id)
        {
            $dt = $this
                ->select('vc_label,owl_prefix,endpoint,owl_title, rs_namespace, rs_url')
                ->join('owl_vocabulary', 'owl_vocabulary.id_owl = owl_vocabulary_vc.vc_prefix')
                ->join('owl_resource', 'owl_resource.id_rs = owl_vocabulary_vc.vc_resource')
                ->where('vc_prefix',$id)
                ->groupBy('vc_label,owl_prefix,endpoint,owl_title, rs_namespace, rs_url')
                ->orderBy('rs_namespace, vc_label')
                ->findAll();

            $sx = '<table class="table">';
            $xResourse = '';
            for($r=0;$r < count($dt);$r++)
                {
                    $line = $dt[$r];

                    $Resourse = $line['rs_namespace'];
                    if ($xResourse != $Resourse)
                        {
                            $sx .= '<tr><th colspan=2>'.$Resourse.'</th></tr>';
                            $xResourse = $Resourse;
                            //$sx .= '<td>' . $line['rs_namespace'] . ':' . $line['rs_url'] . '</td>';
                        }

                    $link = '<a href="'. $line['endpoint'].$line['vc_label'].'" target="_blank">'.bsicone('url').'</a>';
                    $sx .= '<tr>';
                    $sx .= '<td width="1%">' . $link . '</td>';
                    $sx .= '<td>'. $line['owl_prefix'].':'.$line['vc_label'].'</td>';
                    $sx .= '</tr>';
                }
            $sx .= '</table>';
            $sx = bs(bsc($sx,12));
            return $sx;
        }
    function find_prop($prop)
        {
            $dt = $this
                ->where('vc_label',$prop)
                ->first();
            $id = $dt['id_vc'];
            return $id;

        }

    function import($dt, $id)
    {
        $RDFClass = new \App\Models\RDF\Ontology\ClassPropryties();
        $Resource = new \App\Models\RDF\Ontology\Resource();
        $Prefix = new \App\Models\RDF\Ontology\Prefix();


        /***************************************************** Class Propriety */
        /******************************* about */
        $data['vc_prefix'] = $id;
        $cp = (array)$dt['@attributes'];
        $data['vc_label'] = troca((string)$cp['about'],'#','');

        /**************************** resource */
        $cp = (array)$dt['isDefinedBy'];
        $cp = (array)$cp['@attributes'];
        $data['vc_url'] = (string)$cp['resource'];

        /***************************** update */
        $data['updated_at'] = date("Y-m-d H:i:s");

        /******************************** TYPE */
        //pre($dt, false);
        $cp = (array)$dt['type'];
        if (!isset($cp[0])) {
            $cp = array($cp);
        }
        for ($qr = 0; $qr < count($cp); $qr++) {
            $cpa = (array)$cp[$qr];
            $cpa = (array)$cpa['@attributes'];
            $data['vc_resource'] = $Resource->identify((string)$cpa['resource']);
            /************* Check */
            $da = $this
                ->where('vc_prefix', $data['vc_prefix'])
                ->where('vc_label', $data['vc_label'])
                ->where('vc_resource', $data['vc_resource'])
                ->findAll();
            echo '<hr>';
            if (count($da) == 0) {
                $this->set($data)->insert();
            }
        }
    }
}
