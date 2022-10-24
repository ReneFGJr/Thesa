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

    function socials($d1 = '', $d2 = '', $d3 = '', $d4 = '')
    {
        $Thesa = new \App\Models\Thesa\Thesa();
        $sx = '';
        $dt = array();
        $sx .= $this->cab($dt);
        $Socials = new \App\Models\Socials();
        $sx .= $Socials->index($d1, $d2, $d3, $d4);
        return $sx;
    }

    public function index($act = '', $id = '',$tp='')
    {

        $Thesa = new \App\Models\Thesa\Thesa();
        $data = array();

        $sx = $this->cab();
        $sx .= view('header/navbar');
        switch ($act) {
            case 'v':
                $Descriptions = new \App\Models\Thesa\Descriptions();
                $ThConcept = new \App\Models\RDF\ThConcept();

                $dth = $ThConcept->le($id);
                $th = $dth[0]['c_th'];

                $data = array();
                //$sx .= view('header/menu_left');
                $Thesa->setThesa($th);

                $dth = $Thesa->le($th);
                $sx .= $Thesa->header($dth);

                $sa = '<div id="terms">' . $Thesa->terms($th) . '</div>';
                $sb = '<div id="desc">';
                //$sb .= $Descriptions->resume($th);
                $sb .= $Thesa->t($id);
                //$sb .= $Descriptions->show($th);
                $sb .= '</div>';

                $sx .= bs(bsc($sa, 4) . bsc($sb, 8));
                break;
            case 'thopen':
                $sx .= bs(bsc(h(lang('thesa.ThOpen'))));
                $Thesa = new \App\Models\Thesa\Thesa();
                $sx .= $Thesa->index($id, $id, $id);
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
                /******************** Visualizado Ajax Term */
                $Thesa = new \App\Models\Thesa\Thesa();
                echo $Thesa->t($id);
                exit;
                break;

            case 'th':
                $Descriptions = new \App\Models\Thesa\Descriptions();
                $data = array();
                //$sx .= view('header/menu_left');
                $id = $Thesa->setThesa($id);

                $dt = $Thesa->le($id);
                $sx .= $Thesa->header($dt);

                $sa = '<div id="terms">'.$Thesa->terms($id). '</div>';
                $sb = '<div id="desc">';
                $sb .= $Descriptions->resume($id);
                $sb .= $Descriptions->show($id);
                $sb .= '</div>';

                $sx .= bs(bsc($sa,4).bsc($sb,8));

                $data['body'] = 'Corpo do texto';
                break;

            default:
                $bg = (date("s") % 4)+1;
                $data['bg'] = $bg;
                $sx .= view('Thesa/Welcome',$data);
                break;
        }
        $sx .= $this->footer($data);
        return $sx;
    }
}
