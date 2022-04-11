<?php

namespace App\Models\Language;

use CodeIgniter\Model;

class Index extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'language';
    protected $primaryKey       = 'id_lg';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_lg','lg_code','lg_language','lg_order','lg_active','lg_cod_marc'
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

    function radio($th,$name)
        {
            $dt = $this
                ->join('language_th','lgt_language = id_lg')
                ->orderBy('lgt_order, lg_order, lg_language')
                ->where('lgt_th',$th)
                ->findAll();
            $sx = '';
            $vlr = get($name);
            
            for ($r=0;$r < count($dt);$r++)
                {
                    $line = $dt[$r];
                    if ($line['lg_cod_marc'] == $vlr) { $sel = ' checked '; } else { $sel = ''; }
                    if (count($dt) == 1)
                        {
                            $sel = ' checked';
                        }
                                            
                    $sx .= '<input name="'.$name.'" type="radio" value="'.$line['lg_cod_marc'].'" '.$sel.'> ';
                    $sx .= $line['lg_language']; 
                    $sx .= '<br>';
              }
            return $sx;
        }

    function select($id)
        {
            $dt = $this->orderBy('lg_order, lg_language')->findAll();
            pre($dt);
        }
}
