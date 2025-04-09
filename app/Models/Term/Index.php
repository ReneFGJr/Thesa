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

    function registerTerm($user)
        {
            $RSP = [];
            $RSP = $_POST;

            $Term = new \App\Models\Term\Index();
            $TermsTh = new \App\Models\Term\TermsTh();
            $TermConcept = new \App\Models\Term\TermConcept();

            $thesaID = get("thesaID");
            if ($thesaID == '') {
                $RSP['status'] = '500';
                $RSP['message'] = 'Thesaurus ID not informed';
                return $RSP;
            }
            $terms = get("terms");
            $terms = explode(',', $terms);
            $conceptID = get("conceptID");
            $prop = get("verb");

            foreach($terms as $id=>$idTerm)
                {
                /********************************* Localiza Termo */
                $dt = $TermsTh->where('term_th_thesa', $thesaID)
                ->where('term_th_term', $idTerm)
                ->first();
                if ($dt == []) {
                    $RSP['status'] = '500';
                    $RSP['message'] = 'Term not found in Thesaurus';
                    return $RSP;
                } else {
                /* Cria o vinculo */
                    $RSP['result'] = $TermConcept->register_term_label($thesaID, $conceptID, $idTerm, $prop);
                    /* Vincula termo com o conceito */
                    if ($dt['term_th_concept'] == 0) {
                        $dd['term_th_concept'] = $conceptID;
                    $TermsTh->set($dd)->where('term_th_id', $dt['term_th_id'])->update();
                } else {
                        $RSP['status'] = '500';
                        $RSP['message'] = 'Term already related to a concept';
                        return $RSP;
                    }
                }
            }

            $RSP['status'] = '200';
            $RSP['message'] = 'Processado';
            $RSP['terms'] = get("terms");
            return $RSP;
        }

    function listPrefTerm($termID)
        {
            $RSP = [];
            $RSP['status'] = '200';
            $RSP['message'] = 'OK';
            $RSP['terms'] = [];
            $RSP['termID'] = $termID;
            return $RSP;
        }

    function register($term, $lang)
    {
        $dt = $this
            ->where('term_name', $term)
            ->where('term_lang', $lang)
            ->first();

        if ($dt == []) {
            $dd['term_name'] = $term;
            $dd['term_lang'] = $lang;
            $id = $this->set($dd)->insert();
        } else {
            $id = $dt['id_term'];
        }
        return $id;
    }

    function listTerm($th)
    {
        $TermsTh = new \App\Models\Term\TermsTh();
        $RSP = [];

        $th .= get("th");
        if ($th == '') {
            $RSP['status'] = '500';
            $RSP['message'] = 'Thesaurus ID not informed';
            return $RSP;
        }
        $RSP['status'] = '200';
        $RSP['Terms'] = $TermsTh->getTerms($th);
        return $RSP;
    }

    function appendTerm($user)
    {
        $RSP = [];
        $RSP['status'] = '200';
        $Language = new \App\Models\Language\Index();
        $TermsTh = new \App\Models\Term\TermsTh();

        /*********************** Thesauro */
        $th = get("thesaID");
        if ($th == '') {
            $RSP['status'] = '500';
            $RSP['message'] = 'Thesaurus ID not informed';
            return $RSP;
        }
        $Thesa = new \App\Models\Thesa\Index();
        $dtTh = $Thesa->le($th);

        if (! isset($dtTh['id_th'])) {
            $RSP['status'] = '500';
            $RSP['message'] = 'Thesaurus ID invalid';
            return $RSP;
        }


        $Collaborators = new \App\Models\Thesa\Collaborators();
        $Auth = $Collaborators->authorizedSave($th,$user);
        if ($Auth <= 0) {
            $RSP['status'] = '500';
            $RSP['message'] = 'Usuer not authorized to save';
            return $RSP;
        }

        $lang = $Language->getCode(get("lang"));
        if (!isset($lang['id_lg']))
            {
            $RSP['status'] = '500';
            $RSP['message'] = 'Language not informed ('.$lang.')';
            return $RSP;
            }
        $langID = $lang['id_lg'];
        $RSP['language'] = $lang['lg_code'];

        $terms = get("terms");
        $terms = troca($terms, chr(13), ';');
        $terms = troca($terms, chr(10), ';');
        $t = explode(';', $terms);

        if ($terms == '')
            {
                $RSP['status'] = '500';
                $RSP['message'] = 'Terms not informed (terms)';
                return $RSP;
            }
        $terms = [];
        foreach ($t as $name) {
            $name = trim($name);
            if ($name != '') {
                /* Registra */
                $id = $this->register($name, $langID);
                $TermsTh->register($id, $th);
                array_push($terms, [$id,$name]);
            }
        }
        $RSP['terms'] = $terms;

        return $RSP;
    }

    function le($id, $type = '')
    {
        $TermConcept = new \App\Models\Term\TermConcept();
        $dt = $TermConcept->le($id,$type);
        return $dt;
    }
}
