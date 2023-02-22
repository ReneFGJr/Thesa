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

class Thesa extends BaseController
{
    public function cab($data = array())
    {
        $data['title'] = 'Thesa';
        $data['bg_color'] = '#4B0082;';
        $data['css'] = '';
        return view('header/header', $data);
    }

    public function navbar($data = array())
    {
        return view('header/navbar');
    }

    public function footer($data = array())
    {
        $thema = 'Theme/Standard/footer';
        return view($thema, $data);
    }

    function social($d1 = '', $d2 = '', $d3 = '', $d4 = '')
    {
        $dt = array();
        $sx = $this->cab();
        if ($d1 == 'login')
            {
                $sx .= $this->navbar();
            }

        $Socials = new \App\Models\Socials();
        $sx .= $Socials->index($d1, $d2, $d3, $d4);
        return $sx;
    }

    function pdf($d1 = '', $d2 = '', $d3 = '', $d4 = '')
    {
        $PDF = new \App\Models\PDF\Index();
        $PDF->index();
    }

    public function index($act = '', $id = '', $tp = '',$id2='')
    {
        $sx = '';
        $sx .= $this->cab();
        $sx .= $this->navbar();
        $footer = view("Thesa/Foot");
        switch($act)
            {
            case 'a':
                $ConceptForm = new \App\Models\Thesa\Concepts\Form();
                $sx .= $ConceptForm->form($id);
                return $sx;
                break;
            case 'v':
                $ConceptForm = new \App\Models\Thesa\Concepts\Index();
                $Language = new \App\Models\Thesa\Language();
                $Terms = new \App\Models\Thesa\Terms\Index();
                $dt = $ConceptForm->find($id);
                $th = $dt['c_th'];

                $Thesa = new \App\Models\Thesa\Index();
                $Thesa->setThesa($th);

                $dt = $Thesa->le($th);
                $sx .= view('Theme/Standard/headerTh', $dt);

                $lang = $Language->getLang();

                $ConceptList = new \App\Models\Thesa\Concepts\Lists();
                $Other = $Terms->show($id,false);
                $sx .= $ConceptList->terms_alphabetic($th,$lang,$Other);
                break;
            case 't':
                $sx = '';
                $Terms = new \App\Models\Thesa\Terms\Index();
                $sx .= $Terms->show($id);
                return $sx;
                break;
            case 'th':
                $Thesa = new \App\Models\Thesa\Index();
                $Language = new \App\Models\Thesa\Language();
                $Terms = new \App\Models\Thesa\Terms\Index();
                $Description = new \App\Models\Thesa\Descriptions();

                $Thesa->setThesa($id);

                $dt = $Thesa->le($id);
                $sx .= view('Theme/Standard/headerTh',$dt);

                $Other = $Description->resume($id, false);
                $Other .= $Description->show($id);
                $lang = $Language->getLang();

                $ConceptList = new \App\Models\Thesa\Concepts\Lists();
                $sx .= $ConceptList->terms_alphabetic($id, $lang, $Other);
                break;

            /******* TH OPEN */
            case 'thopen':
                $sx .= bs(bsc(h(lang('thesa.ThOpen'),1)));
                $Thesa = new \App\Models\Thesa\Thesa();
                $sx .= $Thesa->index($id, $id, $id);
                break;
            default:
                $ThesaHome = new \App\Models\Thesa\Content\Resume();
                $bg = (date("s") % 4) + 1;
                $data['bg'] = $bg;
                $data['content'] = $ThesaHome->resume();
                $sx .= view('Thesa/Homepage', $data);
                break;
            }
        $sx .= $footer;
        return $sx;
    }
}
