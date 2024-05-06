<?php

namespace App\Models\Term;

use CodeIgniter\Model;

class Index extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_terms';
    protected $primaryKey       = 'id_term';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_term', 'term_name', 'term_lang'
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

    function register($term,$lang)
        {
            $dt = $this
                ->where('term_name',$term)
                ->where('term_lang', $lang)
                ->first();
            if ($dt == [])
                {
                    $dd['term_name'] = $term;
                    $dd['term_lang'] = $lang;
                    $id = $this->set($dd)->insert();
                } else {
                    $id = $dt['id_term'];
                }
            return $id;
        }

    function le($id,$type='')
        {
            $TermConcept = new \App\Models\Term\TermConcept();
            $dt = $TermConcept->le($id,$type);
            $dd = [];
            foreach($dt as $idx=>$line)
                {
                    $class = $line['Prop'];
                    $Term = $line['Term'];
                    if (($class == $type) or ($type == ''))
                        {
                            $dx = [];
                            $dx['Term'] = $Term;
                            $dx['Lang'] = $line['Lang'];
                            $dx['Language'] = $line['Language'];
                            $dx['LangCode'] = $line['LangCode'];
                            array_push($dd,$dx);
                        }
                }
                return $dd;
        }
}
