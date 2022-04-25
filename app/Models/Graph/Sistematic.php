<?php

namespace App\Models\Graph;

use CodeIgniter\Model;

class Sistematic extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'sistematics';
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

    function GraphNode($dt,$cp,$n=0)
        {
            $sx = '';
            $nr = 0;
            foreach($dt as $key => $value)
                {                    
                    if (is_array($value))
                        {
                            $sx .= '<li>'.'<a href="'.PATH.MODULE.'v/'.$key.'">'.$this->GraphName($cp[$key]).'</a>';
                            $sx .= '<ul class="t2">';
                            $sx .= $this->GraphNode($value,$cp,$n);                            
                            $sx .= '</ul>';
                            $sx .= '</li>';
                            
                        } else {                            
                            /*************************************************** */
                            $sx .= '<li><a href="'.PATH.MODULE.'v/'.$key.'">'.$this->GraphName($cp[$key]).'</a></li>';
                        } 
                }
            return $sx;
        }

    function GraphName($n)
        {
            if ($pos = strpos($n,'@'))
                {
                    $n = substr($n,0,$pos);
                }
            return $n;
        }


    function Graph($dt,$cp)
        {
            $sx = '<h1>Sistematic</h1>';
            $sx .= '<link href="https://www.cssscript.com/demo/minimalist-tree-view-in-pure-css/tree.css" rel="stylesheet" type="text/css" />';

            foreach($dt as $var=>$txt)
                {
                    $sx .= '<ul class="tree">';
                    $sx .= '<li><a>'.$this->GraphName($cp[$var]).'</a>';
                    $sx .= '</li>';
                    
                    $sx .= '<li>';
                    $sx .= '<ul>';
                    $sx .= $this->GraphNode($txt,$cp,1);
                    $sx .= '</ul>';
                    $sx .= '</li>';
                    
                    $sx .= '</ul>';
                }
            return $sx;
        }

        /*
        data
        ├─── Semente
        │    ├─── data
        │    └─── data2
        ├─── Casa
        └─── Sala
        */
}
