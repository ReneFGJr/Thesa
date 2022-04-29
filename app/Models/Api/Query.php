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

    function uri($d1,$d2,$d3,$uri)
        {
            $ThConcept = new \App\Models\Thesaurus\ThConcept();

            $id = '';
            $id = substr($uri, strpos($uri, 'v/') + 2,strlen($uri));
            $dt = $ThConcept->le($id);
            echo '==id==>'.$id.'<br>';
            echo '==d2==>'.$d2.'<br>';
            echo '==d3==>'.$d3.'<br>';
            echo '==uri==>'.$uri.'<br>';
            exit;
        }

    function rest($thName='',$act,$ver)
        {
            $ThLiteralTh = new \App\Models\Thesaurus\ThLiteralTh();
            $ThThesaurus = new \App\Models\Thesaurus\ThThesaurus();
            $API = new \App\Models\Api\Json();
            $th = $ThThesaurus->getAchronic($thName);
            
            if ($th == 0)
                {
                    $dt['status'] = 'error';
                    $dt['erro'] = '500';
                    $dt['message'] = lang('thesa.error500');
                    $dt['stamp'] = date('Y-m-d H:i:s');
                    $dt['act'] = $act;
                    $dt['ver'] = $ver;
                    $dt['thName'] = $thName;
                    $dt['th'] = $th;
                    echo json_encode($dt);
                    exit;
                } else {                
                    $q = get("query");
                    $q = troca($q,'*','');
                    if (($q != '') and ($th > 0))
                        {
                        $dt = $ThLiteralTh->search($q,$th); 
                        $API->index('search',$dt,$th,$thName,$q);
                        }
                }

        }
}
