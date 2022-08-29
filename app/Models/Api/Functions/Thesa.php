<?php

namespace App\Models\Api\Functions;

use CodeIgniter\Model;

class Thesa extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesas';
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
        if (!isset($data['data']['name'])) {
            $err .= lang('thesa.name_not_defined') . cr();
        }
        if (!isset($data['data']['achronic'])) {
            $err .= lang('thesa.achronic_not_defined') . cr();
        }
        if (!isset($data['data']['description'])) {
            $err .= lang('thesa.description_not_defined') . cr();
        }
        if (!isset($data['data']['type'])) {
            $err .= lang('thesa.type_not_defined') . cr();
        }
        if (!isset($data['data']['own'])) {
            $err .= lang('thesa.own_not_defined') . cr();
        }

        /***************************************************** ERRO ******/
        if (strlen($err) > 0) {
            $err_nr = 403;
            $data['error'] = $err_nr;
            $data['status'] = 'error';
            $data['message'] = $err;
        } else {
            /***************************************************** RETURN */
            $Thesa = new \App\Models\Thesa\Thesa();
            $data['id'] = $Thesa->thesa_add($data['data']);
            $data['status'] = 'ok';
            $data['error'] = 200;
        }
        echo json_encode($data);
        exit;
    }
}
