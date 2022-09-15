<?php

namespace App\Controllers;

use App\Controllers\BaseController;

helper(['boostrap', 'url', 'sisdoc_forms', 'form', 'nbr', 'sessions', 'cookie']);
$session = \Config\Services::session();

define("URL", getenv("app.baseURL"));
define("PATH", getenv("app.baseURL") . '/');
define("MODULE", '');
define("PREFIX", '');
define("COLLECTION", 'admin');

class Admin extends BaseController
{
    public function cab($data = array())
    {
        $data['title'] = 'Thesa - #ADMIN';
        $data['bg_color'] = '#4B0082;';
        $data['css'] = '';
        return view('header/header', $data);
    }

    public function index($d1 = '',$d2 = '',$d3 = '',$d4 = '',$d5='')
    {
        $Thesa = new \App\Models\Thesa\Thesa();

        $sx = $this->cab();
        $sx .= view('header/navbar_admin');

        switch ($d1) {
            case 'terms':
                $ThTerm = new \App\Models\RDF\ThTerm();
                $sx .= $ThTerm->index($d2,$d3,$d4,$d5);
                return $sx;
            default:
                $sx .= bs(bsc("$d1 / $d2 / $d3 / $d4 / $d5",12));
                $sx .= bs(bsc('Admin'),12);
            break;
        }
        return $sx;
    }
}
