<?php

namespace App\Controllers;

use App\Controllers\BaseController;

helper(['boostrap', 'url', 'sisdoc_forms', 'form', 'nbr', 'sessions', 'cookie']);
$session = \Config\Services::session();
$language = \Config\Services::language();

define("URL", getenv("app.baseURL"));
define("PATH", getenv("app.baseURL"));
define("MODULE", '');
define("PREFIX", '');
define("COLLECTION", 'thesa');

define('BG_COLOR', 'bg-primary');

class Api extends BaseController
{
    public function index($arg1='',$arg2='',$arg3='')
    {
        $RSP = [];

        if ($arg1 == 'conecpt') { $arg1 = 'c'; }
        if ($arg1 == 'term') { $arg1 = 't'; }
        switch($arg1)
            {
                case 'concept_create_term':
                    $Term = new \App\Models\Concept\Index();
                    $RSP = $Term->createConceptAPI($_POST);
                    break;
                case 'term_list':
                    $Term = new \App\Models\Term\Index();
                    $RSP = $Term->listTerm($arg2);
                    break;
                case 'term_add':
                    $Term = new \App\Models\Term\Index();
                    $RSP = $Term->appendTerm();
                    break;
                case 'import':
                    $tools = new \App\Models\Tools\Import();
                    $RSP = $tools->import();
                case 'status':
                    $RSP['status'] = '200';
                    $RSP['messagem'] = 'The system is healthy';
                    $RSP['situation'] = 'GREEN';
                    break;
                case 'terms':
                    $Thesa = new \App\Models\Thesa\Index();
                    $RSP['terms'] = $Thesa->terms($arg2,$arg3);
                    break;
                case 'thopen':
                    $Thesa = new \App\Models\Thesa\Index();
                    $RSP['th'] = $Thesa->thopen();
                    break;
                case 'resume':
                    $Thesa = new \App\Models\Thesa\Index();
                    $RSP = $Thesa->summary();
                    break;
                case 't':
                    $RSP = $this->t($arg2, $RSP);
                    break;
                case 'c':
                    $RSP = $this->c($arg2,$RSP);
                    break;
                case 'th':
                    $RSP = $this->th($arg2, $RSP);
                    break;
                default:
                    $RSP['status'] = '400';
                    $RSP['message'] = 'Verb not informed';
            }
        $RSP['time'] = date("Y-m-dTH:i:s");
        header('Access-Control-Allow-Origin: *');
        header("Content-Type: application/json");
        echo json_encode($RSP);
        exit;
    }

    function th($id, $RSP)
    {
        $Thesa = new \App\Models\Thesa\Thesa();
        $RSP = $Thesa->le($id);
        return $RSP;
    }

    function t($id,$RSP)
        {
        $Terms = new \App\Models\Thesa\Terms\Index();
            $dt = $Terms->le($id);
            $RSP = array_merge($RSP,$dt);
            return $RSP;
        }

    function c($id,$RSP)
        {
            $Term = new \App\Models\Term\Index();
            $Concept = new \App\Models\Thesa\Concepts\Index();
            $Broader = new \App\Models\Thesa\Relations\Broader();
            $Relations = new \App\Models\Thesa\Relations\Relations();

            $Notes = new \App\Models\Property\Notes();
            $RSP = $Concept->le($id);

            $RSP['uri'] = URL.'/c/'.$id;

            $RSP['prefLabel'] = $Term->le($id,'prefTerm');
            $RSP['altLabel'] = $Term->le($id, 'altLabel');
            $RSP['hiddenLabel'] = $Term->le($id, 'hiddenLabel');

            $RSP['broader'] = $Broader->le_broader($id);
            $RSP['narrow'] = $Broader->le_narrow($id);

            $RSP['relations'] = $Relations->le_relations($id);

            $RSP['notes'] = $Notes->le($id);

            return $RSP;
        }
}