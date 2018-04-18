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
        $this -> load -> model('socials');
        /* Security */
        //      $this -> security();
    }

    function index() {
        $this -> load -> model('skoses');

        $this -> cab(1);
        $data = $this -> skoses -> welcome_resumo();
        $this -> load -> view("thesa/home/welcome", $data);
        $this -> load -> view("thesa/home/spots", $data);
        $this -> footer();
    }

    private function cab($navbar = 1) {
        $this -> load -> model("socials");
        $data['title'] = 'Thesa - Library ::::';
        $this -> load -> view('thesa/header/header', $data);
        if ($navbar == 1) {
            $this -> load -> view('thesa/header/navbar', null);
        }
    }

    private function footer() {
        $data = array();
        $this -> load -> view('thesa/header/footer', $data);
    }

    function thesaurus_open() {
        $this -> load -> model('skoses');
        $this -> cab(1);

        $data['content'] = '<h1 style="color: white;">' . msg('open_thesaurus') . '</h1>';
        $this -> load -> view('thesa/home/parallax', $data);

        $tela = $this -> skoses -> myskoses(0);
        $data['content'] = $tela;

        $total = $this -> skoses -> myskoses_total(0);

        if (isset($_SESSION['nivel'])) {
            if ((($_SESSION['nivel'] >= 0) and ($total == 0)) or ($_SESSION['nivel']) == 9) {
                $data['content'] .= $this -> load -> view('skos/thesa_btn_new', $data, true);
            }
        }

        $data['title'] = '';
        $this -> load -> view('content', $data);

        $this -> footer();
    }

    function thesaurus_my() {
        $this -> load -> model('skoses');
        $this -> cab(1);
        $data['content'] = '<h1 style="color: white;">' . msg('my_thesaurus') . '</h1>';
        $this -> load -> view('thesa/home/parallax', $data);

        /****** user id **********/
        $us = $this -> socials -> user_id();

        $tela = $this -> skoses -> myskoses($us);
        $data['content'] = $tela;

        $total = $this -> skoses -> myskoses_total($us);

        if (isset($_SESSION['nivel'])) {
            if ((($_SESSION['nivel'] >= 0) and ($total == 0)) or ($_SESSION['nivel']) == 9) {
                $data['content'] .= $this -> load -> view('skos/thesa_btn_new', $data, true);
            }
        }

        $data['title'] = '';
        $this -> load -> view('content', $data);

        $this -> footer();
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

        /*********************************************************************** PART 1 **/
        $data = $this -> skoses -> le_skos($id);
        $this -> load -> view('thesa/view/thesaurus', $data);

        /*********************************************************************** PART 2 **/
        $tela = '';
        //$tela .= $this -> load -> view('thesa/home/thesa_admin_menu', null, true);

        /*********************************************************************** PART 3 **/
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

    /* Terms */
    function term($th = '', $idt = '') {

        $this -> load -> model("skoses");

        /* CAB */
        $this -> cab();

        $data = $this -> skoses -> le_th($th);

        $data2 = $this -> skoses -> le_term($idt, $th);
        $data = array_merge($data, $data2);

        $this -> load -> view('thesa/view/thesaurus', $data);
        /******************************/

        if (strlen(trim(trim($data['ct_concept']))) == 0) {
            $data['action'] = '<a href="' . base_url('index.php/thesa/concept_create/' . $data['lt_thesauros'] . '/' . checkpost_link($data['lt_thesauros']) . '/' . $data['id_rl']) . '" class="btn btn-primary" style="width: 100%;">' . msg('Term_create_concept') . '</a></li>';
            $data['action'] .= '<br/><br/><a href="' . base_url('index.php/thesa/term_edit/' . $data['lt_thesauros'] . '/' . checkpost_link($data['lt_thesauros']) . '/' . $data['id_rl']) . '" class="btn btn-warning" style="width: 100%;">' . msg('Term_edit_concept') . '</a></li>';
            $data['action'] .= '<br/><br/><a href="' . base_url('index.php/thesa/term_delete/' . $data['lt_thesauros'] . '/' . checkpost_link($data['lt_thesauros']) . '/' . $data['id_rl']) . '" class="btn btn-danger" style="width: 100%;">' . msg('Term_delete_concept') . '</a></li>';
        }
        $this -> load -> view('thesa/header/navbar_tools', null);
        $this -> load -> view('thesa/view/thesaurus_term', $data);

        $tela = '';
        $data['content'] = $tela;
        $this -> load -> view('content', $data);

    }

    /***************************************************************************** Collaborators */
    function collaborators() {
        $this -> load -> model('skoses');
        $this -> cab();

        $id = $_SESSION['skos'];
        $data = $this -> skoses -> le_skos($id);
        $this -> load -> view('thesa/view/thesaurus', $data);
        $data['pg'] = 2;
        //$this -> load -> view('skos/skos_nav', $data);
        $this -> load -> view('thesa/header/navbar_tools', null);

        $tela = $this -> skoses -> th_users();

        $data['title'] = msg('collaborators');
        $data['content'] = $tela;
        $this -> load -> view('content', $data);

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
                /* menu */
                $this -> load -> view('thesa/header/navbar_tools', null);

                /* user allow ****************************************************/
                $user_id = $this -> socials -> user_id();
                $data['edit'] = '';
                if ($user_id > 0) {
                    $user_id = $this -> socials -> user_id();
                    $data['edit'] = isset($data['allow'][$user_id]);
                }

                $this -> load -> view("thesa/view/schema", $data);
                $this -> load -> view("thesa/view/concept", $data);

                $this -> footer();
                break;
        }

        //redirect(base_url('index.php/thesa/myskos'));
    }

    function cedit($c = '', $th = '') {

        /* Load model */
        $edit = true;
        $this -> load -> model("skoses");
        $this -> cab();

        /* RECUPERA SCHEMA */
        if (strlen($th) == 0) {
            $th = $_SESSION['skos'];
        }

        /* LE DADOS SOBRE O SCHEMA */
        $data = $this -> skoses -> le_skos($th);
        $this -> load -> view('thesa/view/thesaurus', $data);
        $data['pg'] = 4;
        
        /* user allow ****************************************************/
        $user_id = $this -> socials -> user_id();
        $data['edit'] = '';
        if ($user_id > 0) {
            $user_id = $this -> socials -> user_id();
            $data['edit'] = isset($data['allow'][$user_id]);
        }

        /* menu */
        $this -> load -> view('thesa/header/navbar_tools', null);

        /* Recupera informações sobre o Concecpt */
        $data2 = $this -> skoses -> le_c($c, $th);
        $data2['editar'] = $data['edit'];
        $data2['edit'] = 0;

        $this -> load -> view("thesa/view/schema", $data2);
        $this -> load -> view("thesa/edit/concept", $data2);
        
        //$this -> load -> view('thesa/view/thesaurus_concept', $data2);

        $this -> load -> view('skos/thesa_concept_tools', $data2);

        $data['content'] = $data2['logs'];
        $this -> load -> view('content', $data);

        $this -> footer();

    }

    /* LOGIN */
    function social($act = '') {
        switch($act) {
            case 'logout' :
                $this -> socials -> logout();
                break;
            case 'login' :
                $this -> cab();
                $this -> socials -> login();
                break;
            case 'login_local' :
                $ok = $this -> socials -> login_local();
                if ($ok == 1) {
                    redirect(base_url('index.php/thesa'));
                } else {
                    redirect(base_url('index.php/thesa/social/login/') . '?erro=ERRO_DE_LOGIN');
                }
                break;
            default :
                echo "Function not found";
                break;
        }
    }

    /***************************************************************************** TG Geral */
    function tg($c = '') {
        $c = round($c);
        $th = $_SESSION['skos'];

        /* Load model */
        $this -> load -> model("skoses");
        $this -> cab(0);

        $data = $this -> skoses -> le($c);
        $data['form'] = $this -> skoses -> form_concept_tg($th, $c);
        $this -> load -> view('thesa/view/thesaurus_concept_mini', $data);

        $action = get("action");
        $tg = get("tg");
        if ((strlen($action) > 0) and (strlen($tg) > 0)) {
            echo $this -> TG;
            $this -> skoses -> assign_as_narrower($tg, $c, $th, 0);
            $this -> load -> view('header/close', null);
            //$this->skoes->
        }

    }

}
?>    