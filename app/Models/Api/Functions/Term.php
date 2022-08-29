<?php

namespace App\Models\Api\Functions;

use CodeIgniter\Model;

class Term extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'skos';
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

    function index($d1 = '', $d2 = '', $d3 = '', $d4 = '', $d5 = '')
    {

        header("Content-type: application/json; charset=utf-8");
        $data['verb'] = $d1;
        $data['method'] = $d2;
        $data['date'] = date("Y-m-d H:i:s");
        $err_nr = 200;
        $dt = $_GET;
        $data['data'] = array();
        foreach ($dt as $id => $value) {
            $data['data'][$id] = $value;
        }

        /************************************** APIKEY */
        $err = '';
        if ((!isset($data['data']['apikey'])) or ($data['data']['apikey'] == '')) {
            $err = lang('thesa.apikey_not_defined');
        }

        /*************************************** PARAMS */
        if ($d1 == 'add') {
            if (!isset($data['data']['lang'])) {
                $err = lang('thesa.lang_not_defined');
            }
            if (!isset($data['data']['term'])) {
                $err = lang('thesa.term_not_defined');
            }
            if (!isset($data['data']['thesa'])) {
                $err = lang('thesa.thesa_not_defined');
            }
        }

        /************************************* Check */
        if (strlen($err) > 0) {
            $err_nr = 403;
            $data['error'] = $err_nr;
            $data['status'] = 'error';
            $data['message'] = $err;
        } else {
            $Term = new \App\Models\Thesa\Terms();

            switch ($d2) {
                case 'add':
                    $data['id_term'] = $Term->add_term($data['data']);
                    $data['thesa'] = $data['data']['thesa'];
                    break;

                case 'list':
                    $data['result'] = $Term->list($data['data']['thesa']);
                    break;
            }
        }
        echo json_encode($data);
        exit;
    }
}
