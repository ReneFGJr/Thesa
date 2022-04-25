<?php

namespace App\Models\Thesaurus;

use CodeIgniter\Model;

class ThImagesType extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'th_concept_image_type';
    protected $primaryKey       = 'id_tcit';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_tcit','tcit_description	','tcit_contenttype','tcit_player','tcit_th'
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

    function contentType($n)
        {
            $dt = $this->where('tcit_contenttype',$n)->findAll();
            if (count($dt) == 0)
                {
                    $dt['tcit_description'] = $n;
                    $dt['tcit_contenttype'] = $n;
                    $dt['tcit_player'] = '';
                    $dt['tcit_th'] = 0;
                    $ID=$this->save($dt);
                } else {
                    $ID = $dt[0]['id_tcit'];
                }
            return $ID;
        }
}
