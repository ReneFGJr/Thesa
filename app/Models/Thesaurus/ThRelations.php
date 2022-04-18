<?php

namespace App\Models\Thesaurus;

use CodeIgniter\Model;

class ThRelations extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'th_concept_term';
    protected $primaryKey       = 'id_ct';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_ct','ct_th','ct_concept','ct_term','ct_use','ct_propriety'
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

    function index($id=0,$ac='')
        {
            $sx = h('thesa.relations',3);


            return $sx;
        }

    function relations($concept1=0,$concept2=0,$literal=0,$prop='',$th='')
        {
            $sx = '';
            $Propriety = new \App\Models\RDF\Proprieties();
            $idP = $Propriety->getPropriety($prop);
            if ($idP == 0) {
                $sx .= bsmessage('Erro #02 - Propriety ' . $prop . ' not found');
                echo $sx;
                exit;
            }

            /*******************************************************************************/
            if ($concept2 > 0)
                {

                } else {
                    $dt = $this
                    ->where('ct_concept',$concept1)
                    ->where('ct_term',$literal)
                    ->where('ct_th',$th)
                    ->findAll();

                    if (count($dt) == 0) 
                    {
                        $dd['ct_th'] = $th;
                        $dd['ct_concept'] = $concept1;
                        $dd['ct_term'] = $literal;
                        $dd['ct_use'] = 0;
                        $dd['ct_propriety'] = $idP;
                        $this->save($dd);
                    }
                }
        }
           
}
