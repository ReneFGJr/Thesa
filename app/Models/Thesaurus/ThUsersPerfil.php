<?php

namespace App\Models\Thesaurus;;

use CodeIgniter\Model;

class ThUsersPerfil extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'th_users_perfil';
    protected $primaryKey           = 'id_up';
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

    function perfil_radiobox($name)
        {
            $dt = $this->orderBy('up_order','ASC')->findAll();
            $sx = '';
            for ($r=0;$r < count($dt);$r++)
                {
                    $check = '';
                    if (get($name) == $dt[$r]['id_up'])
                        { $check = 'checked'; }
                    $sx .= '<div>';
                    $sx .= '<input type="radio" name="'.$name.'" value="'.$dt[$r]['id_up'].'" '.$check.'>';
                    $sx .= '&nbsp;';
                    $sx .= $dt[$r]['up_tipo'];
                    $sx .= '</div>';
                }
            return $sx;
        }
}
