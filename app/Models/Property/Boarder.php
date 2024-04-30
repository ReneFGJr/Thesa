<?php

namespace App\Models\Property;

use CodeIgniter\Model;

class Boarder extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_broader';
    protected $primaryKey       = 'id_b';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_b', 'b_th', 'b_concept_boader',
        'b_concept_narrow', 'b_concept_master', 'b_property',
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

    function register($broader,$narrow,$th,$qualis=0)
        {
            $this->where('b_concept_boader',$broader);
            $this->where('b_concept_narrow', $narrow);
            $this->where('b_th', $th);
            $dt = $this->first();

            if ($dt == [])
                {
                    $this->where('b_concept_boader', $narrow);
                    $this->where('b_concept_narrow', $broader);
                    $this->where('b_th', $th);
                    $dt = $this->first();
                }

            if ($dt == [])
                {

                }

            pre($dt);

        }
}
