<?php

namespace App\Models\Thesa\Tools;

use CodeIgniter\Model;

class Import extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'imports';
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

    function import($id)
        {
            echo '==>'.$id;
            $url = 'https://www.ufrgs.br/tesauros/index.php/thesa/export_to_thesa2/374';

            $txt = read_link($url);
            $txt = json_decode($txt);

            /********************************** TERMS */
            $terms = $txt->terms;
            foreach($terms as $id=>$content)
                {
                    echo h($id,4);
                }
            pre($txt);

        }
}
