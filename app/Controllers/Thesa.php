<?php

namespace App\Controllers;

use App\Controllers\BaseController;

helper(['boostrap', 'url', 'sisdoc_forms', 'form', 'nbr', 'sessions', 'cookie']);
$session = \Config\Services::session();

define("URL", getenv("app.baseURL"));
define("PATH", getenv("app.baseURL") . '/');
define("MODULE", '');
define("PREFIX", '');
define("COLLECTION", 'thesa');

class Thesa extends BaseController
{
    public function cab($data = array())
    {
        $data['title'] = 'Thesa';
        $data['bg_color'] = '#4B0082;';
        $data['css'] = '';
        return view('header/header', $data);
    }

    public function footer($data = array())
    {
        $thema = 'Theme\Standard\footer';
        return view($thema, $data);
    }

    public function index($act = '', $id = '')
    {
        $Thesa = new \App\Models\Thesa\Thesa();

        $sx = $this->cab();
        $sx .= view('header/navbar');

        switch ($act) {
            default:
                $data = array();
                //$sx .= view('header/menu_left');
                $id = 1;
                $dt = $Thesa->le($id);
                $sx .= $Thesa->header($dt);

                $data['body'] = 'Corpo do texto';

                $sx .= view('Theme/Standard/frame', $data);
        }
        $sx .= $this->footer($data);
        return $sx;
    }
}
