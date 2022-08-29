<?php

namespace App\Models\Api;

use CodeIgniter\Model;

class Index extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'indices';
    protected $primaryKey       = 'id';
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

    ///http://api.finto.fi/rest/v1/search?query=category:kunta&lang=fi&max=100&offset=0&sort=name

    function docummentation($d1,$d2)
        {
            $sx = view('header/head_api');
            $sx .= bs(
                bsc(h('API - Documentation',1),10, 'mt-4').
                bsc('v0.22.02.28',2,'text-end mt-4')
               );

            $menu[PATH.'/api/help/'] = 'API';
            $menu[PATH . '/api/help/term'] = 'Terms';
            $menu[PATH . '/api/help/term/v'] = 'Terms View';
            $menu[PATH . '/api/help/term/add'] = 'Terms Add';
            $menu[PATH . '/api/help/term/del'] = 'Terms Del';

            $sa = menu($menu);
            $sb = $this->help($d1,$d2);

            $sx .= bs(bsc($sa,3).bsc($sb,9));

            return $sx;
        }

    function help($d1,$d2)
        {
            $sx = h($d1,2);
            switch($d1)
                {
                    case 'term':
                        $sx .= $this->help_term($d2);
                        break;
                    break;

                    case 'user':
                        $sx .= $this->help_user($d2);
                        break;

                    default:
                        $sx .= '<div class="text-end border border-secundary" with="100%;">Version 1.0.1</div>';
                    break;
                }
            return $sx;
        }

    function URL()
    {
        $sx = URL.'/'.'api/';
        return $sx;
    }

    function required()
        {
            $sx = '<div style="width: 100%; border-top: 1px solid #000;">';
            $sx .= '<sup>(*)</sup> '.lang('thesa.required');
            return $sx;
        }

    /************************************************************** TERM API *************/
    function help_term($d2)
        {
            $sx = '';
            switch($d2)
                {
                    case 'add':
                    $sx .= h(lang('thesa.method').': '.$d2,5,'" style="border-bottom: 1px solid #000000;');
                    $sx .= '<p>'.lang('thesa.method_term_add').'</p>';

                    $endpoint = '';
                    $endpoint .= $this->URL();
                    $endpoint .= 'term/add/';
                    $endpoint .= '?';
                    $endpoint .= $this->APIKEY();

                    $sx .= 'Endpoint: '.anchor($endpoint);

                    $sx .= '<pre>';
                    $sx .= '&nbsp;'.cr();
                    $sx .= 'POST: '.cr();
                    $sx .= '<b>apikey</b>: API to Access <sup>(*)</sup>' . cr();
                    $sx .= '<b>term</b>: term to be added <sup>(*)</sup>'.cr();
                    $sx .= '<b>lang</b>: language of term <sup>(*)</sup>' . cr();
                    $sx .= '<b>thesa</b>: id of thesa <sup>(*)</sup>' . cr();
                    $sx .= '</pre>';

                    $sx .= $this->required();
                    break;

                    default:
                    $sx .= h($d2,5);
                    break;
                }
            return $sx;
        }

    /************************************************************** TERM API *************/
    function help_user($d2)
    {
        $sx = '';
        switch ($d2) {
            case 'add':
                $sx .= h(lang('thesa.method') . ': ' . $d2, 5, '" style="border-bottom: 1px solid #000000;');
                $sx .= '<p>' . lang('thesa.method_term_add') . '</p>';

                $endpoint = '';
                $endpoint .= $this->URL();
                $endpoint .= 'term/add/';
                $endpoint .= '?';
                $endpoint .= $this->APIKEY();

                $sx .= 'Endpoint: ' . anchor($endpoint);

                $sx .= '<pre>';
                $sx .= '&nbsp;' . cr();
                $sx .= 'POST: ' . cr();
                $sx .= '<b>apikey</b>: API to Access <sup>(*)</sup>' . cr();
                $sx .= '<b>term</b>: term to be added <sup>(*)</sup>' . cr();
                $sx .= '<b>lang</b>: language of term <sup>(*)</sup>' . cr();
                $sx .= '<b>thesa</b>: id of thesa <sup>(*)</sup>' . cr();
                $sx .= '</pre>';

                $sx .= $this->required();
                break;

            default:
                $sx .= h($d2, 5);
                break;
        }
        return $sx;
    }

    function index($d1='',$d2='',$d3='',$d4='',$d5='')
        {
            pre($d2);
        }

    function rdf($id,$tp='')
        {
            return("RDF");
            $sql = "
                select * from th_concept_term
                    inner join rdf_class ON ct_propriety = id_c
                    left join rdf_prefix ON rdf_class.c_prefix = id_prefix
                    left join rdf_literal ON ct_term = id_rl
                    where ct_concept = '$id'
                        OR ct_use = '$id'";
            $dt = $this->query($sql)->getResult();
            $pref = array();
            $alter = array();
            $hidden = array();
            for ($r=0;$r < count($dt);$r++)
                {
                    $line = (array)$dt[$r];
                    $lang = $line[''];
                    echo '===>'.$line['id_ct'].'==';
                    switch($line['ct_propriety'])
                        {
                            case 25:
                            echo 'Term=>'.$line['rl_value'].'<br>';
                            //array_pref[$lang] = '<skos:Concept rdf:about="http://vocabularies.unesco.org/thesaurus/concept6676">';
                            break;

                            case 34:
                                echo 'Alias=>'.$line['rl_value'].'<br>';
                                break;

                            default:
                            echo '==>'.$line['rl_value'].'=='.$line['ct_use'].'<br>';
                            pre($line);
                        }
                }
                exit;



            $rdf = '<rdf>';
            //$rdf .= $Skos->skos_concept($id);
            $rdf .= '</rdf>';
            pre($rdf);
        }
}
