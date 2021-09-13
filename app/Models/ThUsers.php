<?php

namespace App\Models;

use CodeIgniter\Model;

class ThUsers extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'th_users';
    protected $primaryKey           = 'id_ust';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = [];

    // Dates
    protected $useTimestamps        = false;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks       = true;
    protected $beforeInsert         = [];
    protected $afterInsert          = [];
    protected $beforeUpdate         = [];
    protected $afterUpdate          = [];
    protected $beforeFind           = [];
    protected $afterFind            = [];
    protected $beforeDelete         = [];
    protected $afterDelete          = [];

    function authors($id)
        {
            $sx = '';
            $this->select('us_nome, up_tipo, up_order');
            $this->join('users','ust_user_id = users.id_us');
            $this->join('th_users_perfil','id_up = ust_user_role');
            $this->where('ust_th',$id);
            $this->where('ust_status',1);
            $this->orderBy('ust_status, up_order, id_ust',1);
            $dt = $this->FindAll();
            $tpx = '';
            foreach($dt as $id=>$line)
                {
                    $tp = $line['up_tipo'];
                    if ($tp != $tpx)
                        {
                            $tpx = $tp;
                            $sx .= '<b>'.lang('thesa.'.$line['up_tipo']).'</b>: ';
                        }
                    $sx .= '<a href="#"><i>'.$line['us_nome'].'</i></a>. ';
                }
            $sx .= '';
            return $sx;
        }
}
