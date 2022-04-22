<?php

namespace App\Models\Thesaurus;

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
    protected $allowedFields        = [
        'id_ust','ust_user_id','ust_user_role','ust_th','ust_status'
    ];
    protected $typeFields        = [
        'hidden','hidden','hidden','hidden','hidden'
    ];    

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

    function add_user($th,$user,$perfil)
        {
            $this->where('ust_th',$th);
            $this->where('ust_user_id',$user);
            $dt = $this->findAll();
            if (count($dt) == 0)
            {
                $dt['ust_user_id'] = $user;
                $dt['ust_user_role'] = $perfil;
                $dt['ust_th'] = $th;
                $dt['ust_status'] = 1;
                $id = $this->save($dt);
                return $id;
            } else {
                return false;
            }
        }

    function autorized($th)
        {          
            $Socials = new \App\Models\Socials();
            $ID = $Socials->getID();
            if ($ID > 0)
                {
                    $this->where('ust_th',$th);
                    $this->where('ust_user_id',$ID);
                    $this->where('ust_status',1);
                    $dt = $this->findAll();
                    if (count($dt) > 0)
                    {
                        return true;
                    } else {
                        return false;
                    }
                    return true;
                }
            return false;            
        }        

    function authors($id)
        {
            $sx = '';
            $this->join('users2','id_us = ust_user_id','inner');
            $this->where('ust_th',$id);
            $dt = $this->FindAll();
            $tpx = '';
            foreach($dt as $id=>$line)
                {
                    $tp = $line['ust_user_role'];
                    if ($tp != $tpx)
                        {
                            $tpx = $tp;
                            $sx .= '<b>'.lang('thesa.'.$line['ust_user_role']).'</b>: ';
                        }
                    $sx .= '<a href="#"><i>'.$line['us_nome'].'</i></a>. ';
                }
            $sx .= '';
            return $sx;
        }
}
