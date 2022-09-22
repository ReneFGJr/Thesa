<?php

namespace App\Controllers;

use App\Controllers\BaseController;

helper(['boostrap', 'url', 'sisdoc_forms', 'form', 'nbr', 'sessions', 'cookie']);
$session = \Config\Services::session();
$language = \Config\Services::language();

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
        $data = array();

        $sx = $this->cab();
        $sx .= view('header/navbar');

        switch ($act) {
            case 'v':
                $Thesa = new \App\Models\Thesa\Thesa();
                $dt = $Thesa->le($id);
                $sx .= $Thesa->header($dt);
                break;
            case 'thopen':
                $sx .= bs(bsc(h(lang('thesa.ThOpen'))));

                break;
            case 'a':
                $Thesa = new \App\Models\Thesa\Thesa();
                $ThTerm = new \App\Models\RDF\ThTerm();
                $ThConcept = new \App\Models\RDF\ThConcept();

                /******************* Thesaurus Header */
                $dt = $ThConcept->find($id);
                $th = $dt['c_th'];
                $dh = $Thesa->le($th);
                $sx .= $Thesa->header($dh);

                $dc = $ThConcept->le($id);
                $sx .= $ThConcept->header($dc);

                $sx .= $ThConcept->edit($id);
                break;
            case 't':
                $Thesa = new \App\Models\Thesa\Thesa();
                echo $Thesa->t($id);
                exit;
                break;
            case 'th':
                $data = array();
                //$sx .= view('header/menu_left');
                $id = $Thesa->setThesa($id);

                $dt = $Thesa->le($id);
                $sx .= $Thesa->header($dt);

                $sa = '<div id="terms">'.$Thesa->terms($id). '</div>';
                $sb = '<div id="desc">';
                $sb .= '<h1>'.'Description'.'</h1>';
                $sb .= '</div>';

                $sx .= bs(bsc($sa,4).bsc($sb,8));

                $data['body'] = 'Corpo do texto';
                break;

            default:
                $sx .= view('Thesa/Welcome');
                break;
        }
        $sx .= $this->footer($data);
        return $sx;
    }
}
