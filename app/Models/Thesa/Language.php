<?php

namespace App\Models\Thesa;

use CodeIgniter\Model;

class Language extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_language';
    protected $primaryKey       = 'id_lgt';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_lgt', 'lgt_th', 'lgt_language', 'lgt_order'
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

    function getLanguage($th)
        {
            $dt = $this
                ->select('lg_code,lg_language')
                ->join('language', 'id_lg = lgt_language')
                ->where('lgt_th',$th)
                ->orderby('lgt_order')
                ->findAll();
            return $dt;
        }

    function extract_languages($dt, $nclass = 'prefLabel')
    {
        $langs = [];
        foreach ($dt as $id => $line) {
            $class = $line['property'];
            if ($class == $nclass) {
                $langs[$line['lg_code']] = 1;
            }
        }
        return $langs;
    }

    function getLang($pref='')
        {
            if (isset($_SESSION['lang']))
                {
                    $lang = $_SESSION['lang'];
                }
            else
                {
                    $lang = $pref;
                    $this->setting($lang);
                }
            return $lang;
        }

    function setting($lang)
        {
            $_SESSION['lang'] = $lang;
        }

    function register($th,$lang)
        {
            $dt = $this->where('lgt_th',$th)->where('lgt_language',$lang)->findAll();
            if (count($dt) == 0)
                {
                    $dt = $this->where('lgt_th', $th)->findAll();
                    $data['lgt_th'] = $th;
                    $data['lgt_language'] = $lang;
                    $data['lgt_order'] = (count($dt) + 1);
                    $this->insert($data);
                }
            return true;
        }

    function remove($th, $lang)
    {
        $dt = $this->where('lgt_th', $th)->where('lgt_language', $lang)->findAll();
        if (count($dt) > 0) {
            $this->where('lgt_th', $th)->where('lgt_language', $lang)->delete();
        }
        return true;
    }

    function lang_form($th = 0, $vlr = 0)
    {
        $this->check($th);
        $dt = $this
            ->join('language', 'lgt_language = id_lg', 'left')
            ->where('lgt_th', $th)
            ->orderBy('lg_order')->findAll();
        $sx = '';
        $check = '';
        if (count($dt) == 1) {
            $check = 'checked';
        }

        foreach($dt as $id=>$line)
            {
            if ($line['id_lg'] == $vlr) { $check = 'checked'; }
            $sx .= '<br>';
            $sx .= '<input class="form-check-input" type="radio" name="lg" id="lg" value="' . $line['id_lg'] . '" ' . $check . '>';
            $sx .= '&nbsp;';
            $sx .= $line['lg_language'];
            $sx .= '</input>';
            $check = '';
        }
        return $sx;
    }
    function check($th)
    {
        $Language = new \App\Models\Thesa\Language();
        $dt = $Language->where('lgt_th', $th)->findAll();
        if (count($dt) == 0) {
            $data['lgt_th'] = $th;
            $data['lgt_language'] = 3;
            $data['lgt_order'] = 1;
            $Language->insert($data);
        }
        return true;
    }
}
