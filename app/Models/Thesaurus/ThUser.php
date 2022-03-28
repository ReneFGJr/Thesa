<?php

namespace App\Models\Thesaurus;

use CodeIgniter\Model;

class ThUser extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'users';
    protected $primaryKey       = 'id_us';
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

    function findEmail($email)
        {
            $dt = $this->like('us_email','%'.$email.'%')->findAll();
            return $dt;
        }
    function radiobox($dt,$name)
        {
            $sx = '';
            for ($r=0;$r < count($dt);$r++)
                {
                    $check = '';
                    if (get($name) == $dt[$r]['id_us'])
                        { $check = 'checked'; }

                    $sx .= '<div>';
                    $sx .= '<input type="radio" name="'.$name.'" value="'.$dt[$r]['id_us'].'" '. $check.'>';
                    $sx .= '&nbsp;';
                    $sx .= $dt[$r]['us_nome'].' ('.$dt[$r]['us_email'].')';
                    $sx .= '</div>';
                }
            return $sx;            
        }
}
