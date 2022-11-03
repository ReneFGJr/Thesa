<?php

namespace App\Models\Thesa;

use CodeIgniter\Model;

class ReferenceConcept extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_references_concepts';
    protected $primaryKey       = 'id_rfc';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_rfc', 'rfc_concept', 'rfc_ref'
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

    function register($id,$ref,$set)
        {
            $dt = $this
                ->where('rfc_concept',$id)
                ->where('rfc_ref',$ref)
                ->findAll();

            if ($set == 1)
                {
                    if (count($dt) == 0)
                        {
                            $data['rfc_concept'] = $id;
                            $data['rfc_ref'] = $ref;
                            $data['updated_at'] = date("Y-m-d H:i:s");
                            $this->set($data)->insert();
                        }
                } else {
                    $this
                        ->where('rfc_concept',$id)
                        ->where('rfc_ref',$ref)
                        ->delete();
                }
            return True;
        }
}
