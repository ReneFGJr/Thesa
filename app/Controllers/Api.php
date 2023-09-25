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
        switch($arg1)
            {
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

    function c($id,$RSP)
        {
            $Concept = new \App\Models\Thesa\Concepts\Index();
            $Broader = new \App\Models\Thesa\Relations\Broader();
            $Relations = new \App\Models\Thesa\Relations\Relations();
            $Notes = new \App\Models\Thesa\Notes\Index();
            $dt = $Concept->le($id);
            $RSP = $dt[0];

            $RSP['broader'] = $Broader->le_broader($id);
            $RSP['narrow'] = $Broader->le_narrow($id);

            $RSP['relations'] = $Relations->le_relations($id);

            $RSP['notes'] = $Notes->le($id);

            return $RSP;
        }
}