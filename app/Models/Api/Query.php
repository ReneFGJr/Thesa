<?php

namespace App\Models\Api;

use CodeIgniter\Model;

class Query extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'queries';
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

    function rest($thName='')
        {
            $ThLiteralTh = new \App\Models\Thesaurus\ThLiteralTh();
            $ThThesaurus = new \App\Models\Thesaurus\ThThesaurus();

            $th = $ThThesaurus->getAchronic($thName);
            $q = get("query");
            if (($q != '') and ($th > 0))
                {
                   $dt = $ThLiteralTh->search($q,$th); 
                   pre($dt);
                }

        }
}
