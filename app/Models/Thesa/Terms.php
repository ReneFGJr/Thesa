<?php

namespace App\Models\Thesa;

use CodeIgniter\Model;

class Terms extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_terms';
    protected $primaryKey       = 'term_id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'term_id','term_name','term_lang'
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

    function list($th)
        {
            $dt = $this
                ->join('thesa_terms_th', 'term_th_term = term_id','left')
                ->join('language', 'term_lang = id_lg', 'left')
                ->where('term_th_thesa',$th)
                ->findAll();

            $da = array();
            for ($r=0;$r < count($dt);$r++)
                {
                    $line = $dt[$r];
                    $id = $line['term_id'];
                    $da[$id] = trim($line['term_name']).'@'.trim($line['lg_code']);
                }
            return($da);
        }

    function add_term($data)
        {
            $Language = new \App\Models\Thesa\Language();
            $TermsThesa = new \App\Models\Thesa\TermsThesa();

            $lang = trim($data['lang']);
            $dt = array();
            $lang = $Language->search($lang);
            $dt['term_name'] = $data['term'];
            $dt['term_lang'] = $lang;

            $da = $this->where('term_name', $dt['term_name'])->where('term_lang',$lang)->findAll();
            if (count($da) == 0)
                {
                    $id =  $this->insert($dt);
                } else {
                     $id =  $da[0]['term_id'];
                }

            /********************************** Vinculo */
            $dt = array();
            $dt['term_th_thesa'] = $data['thesa'];
            $dt['term_th_term'] = $id;
            $TermsThesa->add_term_th($dt);

            return $id;
        }
}
