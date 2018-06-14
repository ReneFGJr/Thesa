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
        $this -> load -> library('tcpdf');

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

        $data['title'] = '';
        $this -> load -> view('content', $data);

        $this -> footer();
    }

    function th_new() {
        $us = $_SESSION['id'];

        /* Load model */
        $this -> load -> model("skoses");

        $this -> cab(1);

        $cp = $this -> skoses -> cp_th_new($us);
        $form = new form;
        $form -> table = $this -> skoses -> table_thesaurus;
        $form -> cp = $cp;
        $form -> id = 0;

        $data['content'] = $form -> editar($cp, $this -> skoses -> table_thesaurus);
        $data['title'] = msg('my_thesauros');
        $this -> load -> view('content', $data);

        if ($form -> saved > 0) {

            $this -> skoses -> th_assosiation_users();

            redirect(base_url('index.php/thesa/thesaurus_my/'));
        }

    }

    function th_edit($id = '', $chk = '') {
        /* Load model */
        $this -> load -> model("skoses");
        $this -> skoses -> th_assosiation_users();

        $this -> cab(1);

        if ($id != '') {
            $_SESSION['skos'] = $id;
        }
        $id = $_SESSION['skos'];
        $data = $this -> skoses -> le_skos($id);
        $data['editar'] = 1;

        $this -> load -> view('thesa/view/thesaurus', $data);
        $this -> load -> view('thesa/header/navbar_tools', null);

        if ((count($data) > 0)) {
            if ((isset($_SESSION['id']) and ($_SESSION['id']) == $data['pa_creator']) and ($data['id_pa'] == $id)) {
                $cp = $this -> skoses -> cp_th($id);
                $form = new form;
                $form -> table = $this -> skoses -> table_thesaurus;
                $form -> cp = $cp;
                $form -> id = $id;

                $data['content'] = $form -> editar($cp, $this -> skoses -> table_thesaurus);
                $data['title'] = msg('my_thesauros');
                $this -> load -> view('content', $data);

                if ($form -> saved > 0) {
                    if (strlen($id) > 0) {
                        redirect(base_url('index.php/thesa/select/' . $id . '/' . checkpost_link($id)));
                    } else {
                        redirect(base_url('index.php/thesa/thesaurus_my'));
                    }
                }

                $msg = '';

                /* ICONE */
                $data['title'] = msg('icones');
                $data['content'] = $this -> skoses -> show_icone($data['pa_icone']);
                $data['content'] .= '<br><span onclick="newxy(\'' . base_url('index.php/thesa/icone/') . '\',600,600);" class="link">' . msg('alter') . '</span>';
                $this -> load -> view('content', $data);

                /* Colaboradores */
                $msg = $this -> skoses -> th_collaborators($id) . $msg;
                $data['content'] = $msg;
                $this -> load -> view('skos/thesa_users', $data);
            } else {
                $tela = '	<div class="alert alert-danger" role="alert">
								' . msg('Unauthorized_access') . '
							</div>';
                $data['content'] = $tela;
                $this -> load -> view('content', $data);
            }
        }
        $this -> load -> view('header/footer', null);
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

        if (($total == 0) or (perfil("#ADM"))) {
            $data['content'] .= $this -> load -> view('skos/thesa_btn_new', $data, true);
            $data['content'] .= '<br><br><br>';
        }

        $data['title'] = '';
        $this -> load -> view('content', $data);

        $this -> footer();
    }

    function show_404() {
        $this -> cab(0);
        $this -> load -> view('skos/404', null);
    }

    function icone($id = '', $sel = '', $chk = '') {
        $this -> load -> model('skoses');
        $this -> cab(0);

        $th = $this -> skoses -> th();
        $data = $this -> skoses -> le_th($th);

        if (strlen($sel) > 0) {
            $chk2 = checkpost_link('icone' . $sel);
            if ($chk == $chk2) {
                $this -> skoses -> icone_update($th, $sel);
                $txt = '<script> window.opener.location.reload(); close(); </script>';
                echo $txt;
                exit ;
            }
        }

        /******************/
        $img = $this -> skoses -> show_icone($th);
        $data['content'] = $img;
        $data['title'] = 'THESA: ' . $data['pa_name'];
        $this -> load -> view('content', $data);

        /******************/
        $txt = $this -> skoses -> icones_select($th);
        $data['content'] = $txt;
        $data['title'] = '';
        $this -> load -> view('content', $data);

        $target_dir = "img/icone/custon/";
        $target_file = $target_dir . 'background_icone_' . $th . '.png';
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }

        // Allow certain file formats
        $err = '';
        if (!isset($_FILES["fileToUpload"]["tmp_name"])) {
            $uploadOk = 0;
        }
        if ($imageFileType != "jpg" && $imageFileType != "jpeg") {
            $err = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $err = "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $err = "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
            } else {
                $err = "Sorry, there was an error uploading your file.";
            }
        }
        /**********************/

        $html = '';
        $html .= '<hr>';
        $html .= $err;
        $html .= '<form action="upload.php" method="post" enctype="multipart/form-data">
					    Select image to upload:
					    <input type="file" name="fileToUpload" id="fileToUpload">
					    <input type="submit" value="Upload Image" name="submit">
					</form>';
        $html .= '<hr>';
        $data['content'] = $html;
        $data['title'] = '';
        $this -> load -> view('content', $data);

    }

    function imagem($tp) {
        $this -> load -> model('skoses');
        $th = $this -> skoses -> th();
        $data = $this -> skoses -> le_th($th);

        $this -> cab();

        /******************/
        $target_dir = "img/background/";
        $target_file = $target_dir . 'background_thema_' . $th . '.jpg';
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }

        // Allow certain file formats
        $err = '';
        if (!isset($_FILES["fileToUpload"]["tmp_name"])) {
            $uploadOk = 0;
        }
        if ($imageFileType != "jpg" && $imageFileType != "jpeg") {
            $err = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $err = "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $err = "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
            } else {
                $err = "Sorry, there was an error uploading your file.";
            }
        }
        /**********************/

        $html = '';
        $html .= '<hr>';
        $html .= $err;
        $html .= '<form action="upload.php" method="post" enctype="multipart/form-data">
					    Select image to upload:
					    <input type="file" name="fileToUpload" id="fileToUpload">
					    <input type="submit" value="Upload Image" name="submit">
					</form>';
        $html .= '<hr>';
        $html .= '<img src="' . $data['image_bk'] . '" width="100%">';
        $data['content'] = $html;
        $data['title'] = 'THESA: ' . $data['pa_name'];
        $this -> load -> view('content', $data);

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
        $tela .= $this -> load -> view('thesa/header/navbar_tools', null, true);

        /*********************************************************************** PART 3 **/
        $tela .= $this -> skoses -> termos_pg($id);
        $tela .= msg('Export_to') . ': ' . $this -> skoses -> export_format($id);

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

    function terms_list($pag = 0) {
        $this -> load -> model('skoses');
        $this -> cab();

        $id = $_SESSION['skos'];

        $data = $this -> skoses -> le_skos($id);
        $this -> load -> view('thesa/view/thesaurus', $data);

        $tela = '';
        $tela .= $this -> load -> view('thesa/header/navbar_tools', null, true);

        $tela .= '<div class="row"><div class="col-md-12">';
        /* Lista de comunicacoes anteriores */
        $form = new form;
        $form -> tabela = "
                    (
                    select * from rdf_literal
                    INNER JOIN rdf_literal_th ON lt_term = id_rl
                    WHERE lt_thesauros = $id
                    ) as tabela
                
                ";
        $form -> see = true;
        $form -> edit = False;
        $form -> novo = False;
        $form = $this -> skoses -> term_row($form);

        $form -> row_view = base_url('index.php/thesa/term_edit2');
        $form -> row = base_url('index.php/thesa/terms_list/');

        $tela .= row($form, $pag);
        $tela .= '</div></div>';
        $data['content'] = $tela;
        $this -> load -> view('content', $data);

        $this -> load -> view('header/footer', null);
    }

    function term_edit($id = '', $idt = '') {
        $this -> term_edit2($id, $idt);
    }

    function term_edit2($id = '', $idt = '') {
        $this -> load -> model('skoses');
        $this -> cab();

        if (!isset($th)) {
            $th = $_SESSION['skos'];
        }
        $data = $this -> skoses -> le_th($th);

        $data2 = $this -> skoses -> le_term($id, $th);
        $data = array_merge($data, $data2);

        $this -> load -> view('thesa/view/thesaurus', $data);
        /******************************/

        $this -> load -> view('thesa/view/term', $data);

        $form = new form;
        $form -> id = $id;
        $cp = $this -> skoses -> cp_term();
        $data['content'] = $form -> editar($cp, $this -> skoses -> table_terms);
        $data['title'] = '';
        $this -> load -> view('content', $data);

        $tela = '';
        $data['content'] = $tela;
        $this -> load -> view('content', $data);

        if ($form -> saved) {
            redirect(base_url('index.php/thesa/terms_list/' . $th));
        }
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
            $data['action'] .= '<br/><br/><a href="' . base_url('index.php/thesa/term_edit/' . $data['id_rl'] . '/' . checkpost_link($data['lt_thesauros']) . '/' . $data['id_rl']) . '" class="btn btn-warning" style="width: 100%;">' . msg('Term_edit_concept') . '</a></li>';
            $data['action'] .= '<br/><br/><a href="' . base_url('index.php/thesa/term_delete/' . $data['lt_thesauros'] . '/' . checkpost_link($data['lt_thesauros']) . '/' . $data['id_rl']) . '" class="btn btn-danger" style="width: 100%;">' . msg('Term_delete_concept') . '</a></li>';
        }
        $this -> load -> view('thesa/header/navbar_tools', null);
        $this -> load -> view('thesa/view/term', $data);

        $tela = '';
        $data['title'] = '';
        $data['content'] = $tela . '<br><br><br>';
        $this -> load -> view('content', $data);

        $this -> footer();

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
        $data['content'] = $tela . '<br><br>';
        $this -> load -> view('content', $data);

        $this -> footer();

    }

    /***************************************************************************** Conecpt */
    function c($c = '', $proto = '', $proto2 = '') {
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
            case 'rdf' :
                header('Content-type: text/asc');
                $this -> skoses -> rdf($data);
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

                break;
        }

        //redirect(base_url('index.php/thesa/myskos'));
        $this -> footer();
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
        $data['title'] = '';
        $this -> load -> view('content', $data);

        $this -> footer();

    }

    /* LOGIN */
    function social($act = '') {
        switch($act) {
            case 'pwsend' :
                $this -> cab();
                $this -> socials -> resend();
                break;
                break;
            case 'signup' :
                $this -> cab();
                $this -> socials -> signup();
                break;
            case 'logout' :
                $this -> socials -> logout();
                break;
            case 'npass' :
                $this -> cab();
                $email = get("dd0");
                $chk = get("chk");
                $chk2 = checkpost_link($email . $email);

                if (($chk != $chk2) AND (!isset($_POST['dd1']))) {
                    $data['content'] = 'Erro de Check';
                    $this -> load -> view('content', $data);
                } else {
                    $dt = $this -> socials -> le_email($email);
                    if (count($dt) > 0) {
                        $id = $dt['id_us'];
                        $data['title'] = '';
                        $tela = '<br><br><h1>' . msg('change_password') . '</h1>';
                        $new = 1;
                        // Novo registro
                        $data['content'] = $tela . $this -> socials -> change_password($id, $new);
                        $this -> load -> view('content', $data);
                        //redirect(base_url("index.php/thesa/social/login"));
                    } else {
                        $data['content'] = 'Email não existe!';
                        $this -> load -> view('error', $data);
                    }
                }

                $this -> footer();
                break;
            case 'login' :
                $this -> cab();
                $this -> socials -> login();
                break;
            case 'login_local' :
                $ok = $this -> socials -> login_local();
                if ($ok == 1) {
                    redirect(base_url('index.php/thesa/thesaurus_my'));
                } else {
                    redirect(base_url('index.php/thesa/social/login/') . '?erro=ERRO_DE_LOGIN');
                }
                break;
            default :
                echo "Function not found";
                break;
        }
    }

    /***************************************************************************** Termo equivalente */
    function te($c = '') {
        $c = round($c);
        $th = $_SESSION['skos'];

        /* Load model */
        $this -> load -> model("skoses");
        $this -> cab(0);

        $data = $this -> skoses -> le($c);
        $data['form'] = $this -> skoses -> form_concept($th, $c, 'FE');
        $this -> load -> view('thesa/view/concept_mini', $data);

        $action = get("action");
        $desc = get("tm");
        $tr = get("tr");

        if ((strlen($action) > 0) and (strlen($tr) > 0) and (strlen($desc) > 0)) {

            echo 'SAVED';
            $this -> skoses -> assign_as_propriety($c, $th, $tr, $desc);
            $this -> load -> view('header/close', null);

            $line2 = $this -> skoses -> le_term($desc, $th);
            $line3 = $this -> skoses -> le_propriety($tr);
            $desc = msg('associated') . ' ' . msg($line3['rs_propriety']) . ' - "<b>' . $line2['rl_value'] . '</b>" ' . ' (<i>' . $tr . '</i>)';
            $this -> skoses -> log_insert($c, $th, 'ADDT', $desc);

            //$this->skoes->
        }
    }

    function te_remove($id = '', $chk = '') {
        $this -> load -> model("skoses");
        $this -> cab(2);

        $ok = get("confirm");
        if ($ok == '1') {
            $this -> skoses -> te_remove($id);
            $this -> load -> view('wclose');
        } else {
            $data['content'] = '<br><br><h1>' . msg('remove_propriety') . '</h1>';
            $data['content'] .= msg('content');
            $data['link'] = base_url('index.php/thesa/te_remove/' . $id . '/' . $chk);
            $this -> load -> view('confirm.php', $data);
        }
    }

    function tr_remove($id = '', $chk = '') {
        $this -> load -> model("skoses");
        $this -> cab(2);

        $ok = get("confirm");
        if ($ok == '1') {
            $this -> skoses -> te_remove($id);
            $this -> load -> view('wclose');
        } else {
            $data['content'] = '<br><br><h1>' . msg('remove_propriety') . '</h1>';
            $data['content'] .= msg('content');
            $data['link'] = base_url('index.php/thesa/te_remove/' . $id . '/' . $chk);
            $this -> load -> view('confirm.php', $data);
        }
    }

    /***************************************************************************** Termo oculto */
    function tz($c = '') {
        $c = round($c);
        $th = $_SESSION['skos'];

        /* Load model */
        $this -> load -> model("skoses");
        $this -> cab(0);

        $data = $this -> skoses -> le($c);
        $data['form'] = $this -> skoses -> form_concept($th, $c, 'TH');
        $this -> load -> view('thesa/view/concept_mini', $data);

        $action = get("action");
        $desc = get("tm");
        $tr = get("tr");

        if ((strlen($action) > 0) and (strlen($tr) > 0) and (strlen($desc) > 0)) {
            echo 'SAVED';
            $ac = get("action");
            $this -> skoses -> assign_as_propriety($c, $th, $tr, $desc);
            if ($ac == msg('save')) {
                $this -> load -> view('header/close', null);
            } else {
                redirect(base_url('index.php/thesa/tz/' . $c));
            }

            //$this->skoes->
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
        $this -> load -> view('thesa/view/concept_mini', $data);

        $action = get("action");
        $tg = get("tg");
        if ((strlen($action) > 0) and (strlen($tg) > 0)) {
            $this -> skoses -> assign_as_narrower($tg, $c, $th, 0);
            $this -> load -> view('header/close', null);
            //$this->skoes->
        }

    }

    /***************************************************************************** Notas de definição */
    function tf($c = '') {
        $c = round($c);
        $th = $_SESSION['skos'];

        /* Load model */
        $this -> load -> model("skoses");
        $this -> cab(0);

        $data = $this -> skoses -> le($c);
        $data['form'] = $this -> skoses -> form_concept_tf($th, $c);
        $this -> load -> view('thesa/view/concept_mini', $data);

        $action = get("action");
        $texto = get("nt");
        $idioma = get("tt");
        $tr = get("tr");

        if ((strlen($action) > 0) and (strlen($tr) > 0) and (strlen($idioma) > 0) and (strlen($tr) > 0)) {

            $this -> skoses -> assign_as_note($c, $th, $tr, $texto, $idioma);
            $this -> load -> view('header/close', null);
            //$this->skoes->
        }
    }

    /***************************************************************************** TR Relacionado */
    function tr($c = '') {
        $c = round($c);
        $th = $_SESSION['skos'];

        /* Load model */
        $this -> load -> model("skoses");
        $this -> cab(0);

        $data = $this -> skoses -> le($c);
        $data['form'] = $this -> skoses -> form_concept_tr($th, $c);
        $this -> load -> view('thesa/view/concept_mini', $data);

        $action = get("action");
        $tg = get("tg");
        $tgr = get("tgr");
        if ((strlen($action) > 0) and (strlen($tg) > 0) and (strlen($tgr) > 0)) {
            echo $this -> TG;
            $this -> skoses -> assign_as_relation($tg, $c, $th, $tgr);
            $this -> load -> view('header/close', null);
            //$this->skoes->
        }

    }

    function timg($c = '', $chk = '') {
        $c = round($c);
        $th = $_SESSION['skos'];

        /* Load model */
        $this -> load -> model("skoses");
        $this -> cab(0);

        $form = new form;
        $tela = form_open_multipart() . cr();
        $tela .= '<!-- MAX_FILE_SIZE deve preceder o campo input -->
                    <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
                    <!-- O Nome do elemento input determina o nome da array $_FILES -->
                    Enviar esse arquivo: <input name="userfile" type="file" class="control-form" />
                    <input type="submit" value="Enviar arquivo" />' . cr();
        $tela .= '</form>' . cr();

        if (isset($_FILES['userfile']['tmp_name'])) {
            $fl = $_FILES['userfile']['name'];

            /* ACERVO */
            $temp = '_acervo';
            dircheck($temp);

            /* IMAGES */
            $temp = '_acervo/image';
            echo '<hr>' . $temp;
            dircheck($temp);

            /* IMAGES - DATA */
            $temp = '_acervo/image/' . date("Y");
            dircheck($temp);

            /* IMAGES - DATA */
            $temp = '_acervo/image/' . date("Y") . '/' . date("m") . '/';
            dircheck($temp);

            $img = strzero($c, 7);
            $ext = substr($fl, strlen($fl) - 4, 5);
            $ext = troca($ext, '.', '');

            $uploadfile = $temp . 'img-' . $img . '-' . substr(checkpost_link($img), 2, 10) . '.' . $ext;

            switch($ext) {
                case 'png' :
                    $ok = 1;
                    break;
                case 'jpg' :
                    $ok = 1;
                    break;
                default :
                    $ok = 0;
                    echo 'Formato não suportado "' . $ext . '"!';
            }
            if ($ok == 1) {
                if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                    echo "Arquivo válido e enviado com sucesso.\n";
                    $this -> skoses -> image_concept($c, $uploadfile);
                    $tela = 'Sucesso!';
                    $tela .= $this -> load -> view('wclose');
                } else {
                    echo "Possível ataque de upload de arquivo!\n";
                }
            }

        }

        $data['content'] = $tela;
        $data['title'] = '';
        $this -> load -> view('content', $data);
    }

    /************************************************************** Concept */
    function concept_create($id, $chk = '', $t) {
        /* Load model */
        $this -> load -> model('skoses');
        $th = $_SESSION['skos'];
        if ($id != $th) {
            echo 'OPS-402';
            exit ;
        }
        $this -> cab();

        $is_active = $this -> skoses -> is_concept($t, $th);

        if ($is_active == 0) {
            //$this->skoses->
            $sql = "select * from rdf_literal where id_rl = " . $t;
            $rlt = $this -> db -> query($sql);
            $rlt = $rlt -> result_array();
            $desc = '';
            $act = 'CREAT';
            if (count($rlt) > 0) {
                $line = $rlt[0];
                $desc = msg('concept_create') . ' <b>' . $line['rl_value'] . '</b>';
            }
            $id = $this -> skoses -> concept_create($t, $th);
            $this -> skoses -> log_insert($id, $th, $act, $desc);

            redirect(base_url('index.php/thesa/c/' . $id));
        }

    }

    function concept_add() {
        $this -> load -> model('skoses');
        $this -> cab();
        $id = $_SESSION['skos'];
        $data = $this -> skoses -> le_skos($id);
        $this -> load -> view('thesa/view/thesaurus', $data);
        $data['pg'] = 2;
        //$this -> load -> view('skos/skos_nav', $data);
        $this -> load -> view('thesa/header/navbar_tools', null);

        $form = new form;
        $cp = array();
        array_push($cp, array('$A3', '', msg('terms'), False, False));
        array_push($cp, array('$T80:8', '', msg('terms'), True, False));
        array_push($cp, array('$M', '', '<font class="small">' . msg('terms_info') . '</font>', False, False));
        array_push($cp, array('$QR lg_code:lg_language:select * from language where lg_active = 1 order by lg_order', '', msg('language'), True, False));
        array_push($cp, array('$C', '', msg('Lowercase'), False, False));
        array_push($cp, array('$B8', '', msg('save'), False, False));
        $tela = $form -> editar($cp, '');

        if ($form -> saved > 0) {
            $lc = get("dd4");
            $data['content'] = $this -> skoses -> incorpore_terms(get("dd1"), $id, get("dd3"), $lc);
            $data['title'] = '';
            $this -> load -> view('content', $data);
        } else {
            $data['content'] = $tela;
            $data['title'] = '';
            $this -> load -> view('content', $data);
        }
        $this -> load -> view('header/footer', null);
    }

    function concept_change_preflabel($id = '', $th = '', $chk = '') {
        $this -> load -> model('skoses');
        $this -> cab(0);

        $chk2 = checkpost_link($id . $th);
        if ($chk2 != $chk) {
            $data['title'] = 'Checksum error';
            $data['content'] = 'action canceled';
            $this -> load -> view('skos/510', $data);
            return ('');
        }

        $data = $this -> skoses -> le_c($id, $th);

        /*************** action *********************/
        $idn = get("dd5");
        if (strlen($idn) > 0) {
            //echo '===>' . $idn;
            //echo '<br>===>' . $data['ct_term'];
            //echo '<br>===>' . $id;
            $ok = $this -> skoses -> concept_chage($id, $idn, $data['ct_term'], $data['c_th']);
            if ($ok == 1) {
                $this -> load -> view('wclose');
                //$this->load->
                return ('ok');
            } else {
                $data['title'] = 'Fail';
                $data['content'] = 'Error on save';
                $this -> load -> view('skos/510', $data);
                return ('');
            }
        }

        $sx = '<h1>' . $data['rl_value'] . '</h1>';
        $sx .= '<form method="post">';
        $sx .= '<ul style="list-style-type: none;">';
        $terms = array_merge($data['terms_al'], $data['terms_hd'], $data['terms_ge']);

        for ($r = 0; $r < count($terms); $r++) {
            $line = $terms[$r];
            $rd = '<input type="radio" name="dd5" value="' . $line['ct_term'] . '">';
            $sx .= '<li>' . $rd . ' ' . $line['rl_value'] . '</li>';
        }
        $sx .= '</ul>';
        $sx .= '<input type="submit" value="' . msg('change') . '">';
        $sx .= '</form>';
        $data['content'] = $sx;
        $data['title'] = '';
        $this -> load -> view('content', $data);

    }

    /***************************************************************************** Conecpt */
    function json($id = '') {
        /* Load model */
        $this -> load -> model("skoses");
        $this -> cab();

        $this -> skoses -> to_json($id);

    }

    /* Terms */
    function term_delete($th = '', $chk = '', $idt = '', $act = '', $chk2 = '') {
        $this -> load -> model('skoses');
        $this -> cab();

        if (strlen($th) == 0) {
            $th = $_SESSION['skos'];
        }
        $data = $this -> skoses -> le_th($th);

        $data2 = $this -> skoses -> le_term($idt, $th);

        if (count($data2) == 0) {
            redirect(base_url('index.php/thesa/terms/' . $th));
        }

        $data = array_merge($data, $data2);

        $this -> load -> view('thesa/view/thesaurus', $data);
        /******************************/

        $this -> load -> view('thesa/view/term', $data);

        $chk3 = checkpost_link($th . 'DEL' . $idt);
        if ($chk2 == $chk3) {

            $rs = $this -> skoses -> delete_term_from_th($th, $idt);

            if ($rs == 1) {
                $tela = $this -> load -> view('success', $data, true);
                //$tela .= '<br>';
                //$tela .= '<a href="'.base_url('index.php/thesa/terms/').'" class="btn btn-secondary">'.msg('return').'</div>';
                $url = base_url('index.php/thesa/terms/' . $th);
                $tela .= redirect2($url, 2);
                $data['content'] = $tela;
                $data['title'] = '';

                $this -> load -> view('content', $data);
            } else {
                $data['content'] = msg('item_already_deleted');
                $tela = $this -> load -> view('success', $data, true);
                $data['content'] = $tela;
                $data['title'] = '';
                $this -> load -> view('content', $data);
            }

        } else {
            $data['link'] = base_url('index.php/thesa/term_delete/' . $th . '/' . $chk . '/' . $idt . '/DEL/' . $chk3);
            $data['content'] = msg('delete_term_confirm');
            $tela = $this -> load -> view('confirm', $data, True);
            $data['content'] = $tela;
            $data['title'] = '';
            $this -> load -> view('content', $data);
        }

        $this -> load -> view('header/footer', null);
    }

    function terms_from_to($id = '', $exp = '') {
        if (strlen($id) == 0) {
            $id = $_SESSION['skos'];
        }

        $tp = 1;
        $this -> load -> model('skoses');
        switch ($exp) {
            case 'pdf' :
                $data = $this -> skoses -> le_th($id);

                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                $pdf -> setPrintFooter(false);

                // set document information
                $pdf -> SetCreator(PDF_CREATOR);
                $auth = '';
                $auth2 = '<br><br>';
                for ($r = 0; $r < count($data['authors']); $r++) {
                    if (strlen($auth) > 0) {
                        $auth .= '; ';
                        $auth2 .= '<br>';
                    }
                    $auth .= $data['authors'][$r]['us_nome'];
                    $auth2 .= UpperCase($data['authors'][$r]['us_nome']);
                }
                $pdf -> SetAuthor($auth);
                $pdf -> SetTitle('Thesa: ' . $data['pa_name']);
                $pdf -> SetSubject('Thesaurus');
                $pdf -> SetKeywords('Thesaurus');

                // set default header data
                //$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);

                // set header and footer fonts
                //$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
                //$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

                // set default monospaced font
                $pdf -> SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
                $pdf -> setImageScale(PDF_IMAGE_SCALE_RATIO);

                if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
                    require_once (dirname(__FILE__) . '/lang/eng.php');
                    $pdf -> setLanguageArray($l);
                }

                // ---------------------------------------------------------

                // set font
                $pdf -> SetFont('dejavusans', '', 10);

                // add a page
                $pdf -> AddPage();

                $pdf -> setXY(0, 70);
                $pdf -> SetFont('dejavusans', '', 25);
                $html = '<h2 style="text-align: center;">THESA: ' . $data['pa_name'] . '</h2>';
                $html .= '<span style="text-align: center; font-size: 15px;">' . msg('th_type_' . $data['pa_type']) . '</span>';
                $pdf -> writeHTML($html, true, false, true, false, '');

                /************************************ AUTHORS */
                $pdf -> setXY(0, 10);
                $pdf -> SetFont('dejavusans', '', 10);
                for ($r = 0; $r < count($data['authors']); $r++) {
                    $pdf -> setXY(10, 5 * $r + 20);
                    $auth2 = UpperCase($data['authors'][$r]['us_nome']);
                    $pdf -> Cell(0, 0, $auth2, 0, false, 'C', 0, '', 0, false, 'M', 'M');
                }

                /******************* IMAGEM *********************/
                $img = 'img/background_custumer/biulings.jpg';
                $filename = 'img/background/background_thema_' . $id . '.jpg';
                if (file_exists($filename)) {
                    $img = $filename;
                }
                $pdf -> Image($img, 0, 140, 210, 0);

                $pdf -> AddPage();
                $pdf -> SetFont('dejavusans', '', 10);

                $html = '<div style="line-height: 170%; text-align:justify">';

                if (strlen($data['pa_methodology']) > 0) {
                    $html .= '<h1>' . msg('thesaurus_introdution') . '</h1>';
                    $html .= mst($data['pa_introdution']);
                }

                if (strlen($data['pa_methodology']) > 0) {
                    $html .= '<h1>' . msg('thesaurus_audience') . '</h1>';
                    $html .= mst($data['pa_audience']);
                }

                if (strlen($data['pa_methodology']) > 0) {
                    $html .= '<h1>' . msg('thesaurus_methodology') . '</h1>';
                    $html .= mst($data['pa_methodology']);
                }
                $html = troca($html, '<br/>', '<br>&nbsp;<br>');
                $pdf -> writeHTML($html, true, false, true, false, '');

                

                /******************************** GLASSARIO ****************************/
                $pdf -> AddPage();
                $html = '<h1>Glossário</h1>';
                $html .= $this -> skoses -> glossario_html($id, 'txt');                
                $pdf -> writeHTML($html, true, false, true, false, '');
                
                $pdf -> AddPage();
                $html = '<h1>Glossário - Apresentação Alfabética</h1>';
                $html .= $this -> skoses -> glossario_alfabetico_html($id, 'txt');
                $pdf -> writeHTML($html, true, false, true, false, '');
                
                $pdf -> AddPage();

                // writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
                // writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

                // create some HTML content
                $html = '<h1>' . $data['pa_name'] . '</h1>
				<div style="text-align:center">
				<img src="' . base_url('img/background_custumer/brapci.jpg') . '" alt="test alt attribute" width="800" border="0" />
				</div>';
                $html .= '<h3>==>' . PDF_PAGE_FORMAT . '</h3>';
                // output the HTML content
                $pdf -> writeHTML($html, true, false, true, false, '');

                // reset pointer to the last page
                $pdf -> lastPage();

                // ---------------------------------------------------------

                //Close and output PDF document
                $pdf -> Output('example_006.pdf', 'I');

                //============================================================+
                // END OF FILE
                //============================================================+

                break;
            case 'csv' :
                $arquivo = "thesa_" . strzero($id, 4) . '_' . date("Ymd") . ".csv";
                // Configurações header para forçar o download
                header("Expires: Mon, " . gmdate("D,d M YH:i:s") . " GMT");
                header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
                header("Cache-Control: no-cache, must-revalidate");
                header("Pragma: no-cache");
                header("Content-type: application/x-msexcel");
                header("Content-Disposition: attachment; filename=\"{$arquivo}\"");
                header("Content-Description: PHP Generated Data");
                echo utf8_decode($this -> skoses -> from_to($id, ';', '"'));
                return ('');
                break;
            case 'txt' :
                $arquivo = "thesa_" . strzero($id, 4) . '_' . date("Ymd") . ".txt";
                // Configurações header para forçar o download
                header("Expires: Mon, " . gmdate("D,d M YH:i:s") . " GMT");
                header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
                header("Cache-Control: no-cache, must-revalidate");
                header("Pragma: no-cache");
                header("Content-type: text/html");
                header("Content-Disposition: attachment; filename=\"{$arquivo}\"");
                header("Content-Description: PHP Generated Data");
                echo($this -> skoses -> from_to($id, '=>', ''));
                break;
            case 'json' :
                $arquivo = "thesa_" . strzero($id, 4) . '_' . date("Ymd") . ".txt";
                // Configurações header para forçar o download
                //header("Expires: Mon, " . gmdate("D,d M YH:i:s") . " GMT");
                //header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
                //header("Cache-Control: no-cache, must-revalidate");
                //header("Pragma: no-cache");
                //header("Content-type: text/html");
                //header("Content-Disposition: attachment; filename=\"{$arquivo}\"");
                //header("Content-Description: PHP Generated Data");
                $rst = $this -> skoses -> from_to($id, ':', '"');
                $rst = '{' . troca($rst, chr(13) . chr(10), '};{') . '"fim":"fim"}';
                $rst = troca($rst, '};{', '},' . cr() . '{');
                echo $rst;
                break;
            default :
                echo mst($this -> skoses -> from_to($id, '=>', ''));
                return ('');
                break;
        }
    }

    function ajax($id = '', $id2 = '', $refresh = '0') {
        $this -> load -> model('skoses');

        switch($id) {
            case 'collaborators_add' :
                $refresh = '0';
                $email = get("dd1");
                $th = $_SESSION['skos'];
                $refresh = $this -> skoses -> th_collabotors_add($email, $th);
        }
        if ($refresh == '1') {
            echo ' <meta http-equiv="refresh" content="0">';
        }
    }

    function tools($fc = '') {
        $this -> load -> model("tools");
        $this -> cab();

        $tela = $this -> tools -> form();

        $data['content'] = $tela;
        $data['title'] = '';
        $data['fluid'] = true;
        $this -> load -> view('content', $data);

        $this -> footer();
    }

    function search($id = '') {
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
        $tela .= $this -> load -> view('thesa/header/navbar_tools', null, true);
        $term = get("form_search");
        if (strlen($term) > 0) {
            $tela .= $this -> skoses -> search_term($term, $id);
        }

        $data['content'] = $tela;
        $data['title'] = '';
        $this -> load -> view('content', $data);

    }

    function contact() {
        $this -> cab();
        $this -> load -> view('thesa/contact', null);
        $this -> footer();
    }

    function term_grapho($th) {
        $this -> cab();

        $data['id'] = $th;
        //$this->load->view('grapho/mind_tree',null);
        $this -> load -> view('grapho/mind_map', $data);
        //$this -> load -> view('grapho/mind_map_full', $data);
    }

}
?>