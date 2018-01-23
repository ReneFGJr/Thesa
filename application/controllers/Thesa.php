<?php
class Thesa extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this -> lang -> load("skos", "portuguese");
        $this -> lang -> load("about", "portuguese");
        $this -> load -> library('form_validation');
        $this -> load -> database();
        $this -> load -> helper('form');
        $this -> load -> helper('form_sisdoc');
        $this -> load -> helper('url');
        $this -> load -> library('session');
        $this -> load -> helper('xml');
        $this -> load -> helper('email');
        $this -> load -> library('email');

        date_default_timezone_set('America/Sao_Paulo');
        /* Security */
        //      $this -> security();
    }

    function index() {
        $this -> load -> model('skoses');

        $this -> cab(1);

        $data = $this -> skoses -> welcome_resumo();

        $this -> load -> view("skos/welcome", $data);

        $this -> load -> view("skos/thesa_home_pt", null);
        //redirect(base_url('index.php/skos/myskos'));
    }

    private function cab($navbar = 1) {
        $this -> load -> model("socials");
        $data['title'] = 'Thesa - Library ::::';
        $this -> load -> view('thesa/header/header', $data);
        if ($navbar == 1) {
            $this -> load -> view('thesa/header/navbar', null);
        }
        $_SESSION['id'] = 1;
    }

    function myth() {
        $this -> load -> model('skoses');
        $this -> cab(1);
        if (!isset($_SESSION['id'])) {
            redirect(base_url('index.php/skos/login'));
        }
        $us = $_SESSION['id'];

        $tela = $this -> skoses -> myskoses($us);
        $data['content'] = $tela;

        $total = $this -> skoses -> myskoses_total($us);

        if (isset($_SESSION['nivel'])) {
            if ((($_SESSION['nivel'] >= 0) and ($total == 0)) or ($_SESSION['nivel']) == 9) {
                $data['content'] .= $this -> load -> view('skos/thesa_btn_new', $data, true);
            }
        }

        $data['title'] = msg('my_thesauros');
        $this -> load -> view('content', $data);
    }

    function show_404() {
        $this -> cab(0);
        $this -> load -> view('skos/404', null);
    }

    function about() {
        $this -> cab();
    }

    function select($id = 0, $chk = '') {
        $this -> load -> model('skoses');
        if (checkpost_link($id) == $chk) {
            $this -> skoses -> skoses_select($id);
        }
        redirect(base_url('index.php/thesa/terms/' . $id));
    }

    function terms($id = '', $ltr = '') {
        $this -> load -> model('skoses');
        $this -> cab();

        if (strlen($id) == 0) {
            $id = $_SESSION['skos'];
        }

        $data = $this -> skoses -> le_skos($id);
        $this -> load -> view('thesa/view/thesaurus', $data);

        $tela = '';
        $tela .= $this -> load -> view('skos/thesa_admin_menu', null, true);

        $tela .= $this -> skoses -> termos_pg($id);
        $tela .= '<div class="row">';

        $tela .= '  <div class="col-md-9">';
        $tela .= $this -> skoses -> termos_show_letter($id, $ltr);
        $tela .= '  </div>';

        $tela .= '  <div class="col-md-3">';
        $tela .= $this -> skoses -> thesaurus_resume($id);
        if ($this -> skoses -> autho('', $id) == 1) {
            /* TERMO PERDIDOS */
            $tela .= $this -> skoses -> termos_sem_conceito($id, $ltr);
        }
        $tela .= '  </div>';
        $tela .= '</div>';
        $data['content'] = $tela;
        $data['title'] = '';
        $this -> load -> view('content', $data);

        $this -> load -> view('header/footer', null);

    }

    /***************************************************************************** Conecpt */
    function c($c = '', $proto = '') {
        $this -> load -> model("skoses");

        $data = $this -> skoses -> le($c);
        if (count($data) == 0) { redirect(base_url('index.php/thesa/error/c'));
        }
        $data = $this -> skoses -> le_c($data['id_c'], $data['ct_th']);
        if (count($data) == 0) { redirect(base_url('index.php/thesa/error/c'));
        }

        switch ($proto) {
            case 'xml' :
                header('Content-type: text/xml');
                $this -> skoses -> xml($data);
                break;
            default :
                /* CAB */
                $this -> cab();
                $datask = $this -> skoses -> le_skos($data['c_th']);
                $this -> load -> view('thesa/view/thesaurus', $datask);
                $this -> load -> view('skos/thesa_admin_menu', $datask);

                $user_id = 0;
                $data['edit'] = '';
                if (isset($_SESSION['id'])) {
                    $user_id = $_SESSION['id'];
                    $data['edit'] = isset($data['allow'][$user_id]);
                }

                $this -> load -> view("skos/thesa_schema", $data);
                $this -> load -> view("skos/thesa_concept", $data);
                break;
        }

        //redirect(base_url('index.php/skos/myskos'));
    }

}
?>    