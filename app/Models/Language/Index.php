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
        'id_lg','lg_code','lg_language','lg_order','lg_active','lg_cod_marc', 'lg_cod_short'
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

    function getCode($n)
        {
            $dt = $this->where('lg_code',$n)
            ->OrWhere('lg_language',$n)
            ->OrWhere('lg_cod_short',$n)
            ->first();
            return $dt;
        }

    function langagueCodeShort()
        {
            $dt = $this->findALl();
            $dd = [];
            foreach($dt as $id=>$line)
                {
                    $cod = $line['lg_cod_short'];
                    $dd[$cod] = $line['id_lg'];
                }
            return $dd;
        }

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

    function search($lang)
    {
        $lang = strtolower($lang);
        switch ($lang) {
            case 'en':
                $lang = 'eng';
                break;
            case 'pt_br':
                $lang = 'por';
                break;
            case 'pt-br':
                $lang = 'por';
                break;
            case 'pt':
                $lang = 'por';
                break;
        }

        $dt = $this
            ->where('lg_code', $lang)
            ->orWhere('lg_cod_short',$lang)
            ->findAll();
        if (count($dt) > 0) {
            return ($dt[0]['id_lg']);
        } else {
            echo "ERRO '- " . $lang;
            exit;
        }
    }
}
