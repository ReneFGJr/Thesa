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
                default:
                    $RSP['status'] = '400';
                    $RSP['message'] = 'Verb not informed';
            }
        $RSP['time'] = date("Y-m-dTH:i:s");
        echo json_encode($RSP);
        exit;
    }

    function c($id,$RSP)
        {
            $Concept = new \App\Models\Thesa\Concepts\Index();
            $dt = $Concept->le($id);
            $RSP = $dt[0];
            return $RSP;
        }
}