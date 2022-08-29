<?php

namespace App\Models\Thesa;

use CodeIgniter\Model;

class User extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'users';
    protected $primaryKey       = 'id_us';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_us','us_nome','us_email',
        'us_image','us_genero','us_verificado',
        'us_login','us_password','us_lastaccess',
        'us_apikey','us_apikey_active','us_apikey_date',
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

    function user_add($name,$email,$login,$password)
        {
            $dd = array();
            $dd['us_nome'] = $name;
            $dd['us_email'] = $email;
            $dd['us_login'] = $login;
            $dd['us_password'] = md5($password);
            $dd['us_apikey'] = $this->create_apikey($name);

            $dt = $this->where('us_email',$email)->findAll();
            if (count($dt) == 0)
                {
                    $id = $this->insert($dd);
                    return $id;
                } else {
                    $id = $dt[0]['id_us'];
                    return $id;
                }
        }
    function create_apikey($key)
        {
            $key = md5($key.date("YmdHis"));
            $key = substr($key,0,6).'-'.substr($key,6,6).'-'.substr($key,12,6).'-'.substr($key,18,6);
            return $key;
        }
}
