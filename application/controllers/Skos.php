<?php
class Skos extends CI_Controller {

    var $CO = 25;
    /* Conceito */
    var $TG = 26;
    /* Termo Geral */
    var $BT = 27;
    /* Termo Específico */
    var $TRc = 28;
    /* Termo Coordenado */
    var $TRu = 29;
    /* União com */
    var $TH = 34;
    /* Hidden */

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
        //		$this -> security();
    }

    function user_manual() {
        $this -> load -> model('skoses');

        $this -> cab(1);
        $data = array();
        $idioma = 'pt';

        $tela = '';
        $tela .= $this -> load -> view("skos/manual/" . $idioma . "/index", $data, true);
        $tela .= $this -> load -> view("skos/manual/" . $idioma . "/cap-01", $data, true);

        $data['content'] = $tela;
        $data['title'] = '';
        $this -> load -> view('content', $data);
    }

    function index() {
        $this -> load -> model('skoses');

        $this -> cab(1);
        $this -> load -> view("skos/github_fork", null);

        $data = $this -> skoses -> welcome_resumo();

        $this -> load -> view("skos/welcome", $data);

        $this -> load -> view("skos/thesa_home_pt", null);
        //redirect(base_url('index.php/skos/myskos'));
    }

    function cab($nb = 1) {
        $this -> load -> view('skos/thesa_header', null);
        if ($nb > 0) {
            $this -> load -> view("skos/thesa_cab", null);
            $this -> load -> view('skos/thesa_menu');
            if ($nb != 2) {
                $this -> load -> view("skos/thesa_search");
            }
        }
    }

    function show_404() {
        $this -> cab(0);
        $this -> load -> view('skos/404', null);
    }

    function foot() {
        $this -> load -> view('header/footer', null);
    }

    function security() {
        $user = $this -> session -> userdata('user');
        $email = $this -> session -> userdata('email');
        $nivel = $this -> session -> userdata('nivel');
        if (round($nivel) < 1) {
            redirect(base_url('index.php/social/login'));
        }
    }

    /* LOGIN */
    function login($act = '') {
        $this -> load -> model('skoses');
        $this -> cab(2);
        $data['email_ok'] = '';
        if (strlen($act) > 0) {
            $rs = $this -> validate($act);
            if ($rs == 1) {
                redirect(base_url('index.php/skos/'));
            }
        }

        $ok = $this -> validate();

        $data['error'] = $ok;
        $data['link'] = '';
        $data['user'] = $this -> line;
        if ($ok == 1) {
            redirect(base_url('index.php/skos/myth'));
            exit ;
        } else {
            if (strlen(get("userName")) > 0)
                switch($ok) {
                    case -1 :
                        $idu = $this -> skoses -> le_user_email(get("userName"));
                        $data_us = $this -> skoses -> line;
                        $idu = $data_us['id_us'];
                        $data['email_ok'] = '<span class="alert-danger">' . msg("user_not_validaded") . '</span>';
                        $link = '</br></br><a href="' . base_url('index.php/skos/user_revalid/?dd0=' . $idu . '&chk=' . checkpost_link($idu)) . '" class="btn btn-danger">';
                        $link .= msg('resend_validation');
                        $link .= '</a>';
                        $link .= '<br/><br/>';
                        $data['email_ok'] .= $link;
                        break;
                    case -9 :
                        $link = base_url('index.php/skos/user_password_new/?dd0=' . $idu . '&chk=' . checkpost_link($idu . "SIGNIN"));
                        $data['link'] = $link;
                        break;
                    case -2 :
                        $data['email_ok'] = '<span class="btn alert-danger">' . msg("user_invalid_password") . '#' . $ok . '</span><br/><br/>';
                        break;
                    default :
                        $data['email_ok'] = '<span class="btn alert-danger">' . msg("user_invalid_password") . '#' . $ok . '</span><br/><br/>';
                        break;
                }
        }
        $this -> load -> view('skos/thesa_login', $data);

    }

    function login_ajax($cmd = '', $id = '') {
        $this -> load -> model('skoses');
        $list = array();
        $arr = array();

        $id = round($id);
        $ok = $this -> skoses -> le_user_id($id);
        if ($ok == 1) {
            $data = $this -> skoses -> line;
            $subject = 'THESA: ' . msg('resend_password');
            $email = $data['us_email'];
            $nome = $data['us_nome'];
            $pass = $data['us_password'];
            $aute = $data['us_autenticador'];
            $texto = $this -> skoses -> email_cab();
            $texto .= msg('email_recover_password');
            $texto .= $this -> skoses -> email_foot();

            $texto = troca($texto, '$nome', $nome);
            $texto = troca($texto, '$password', $pass);
            $texto = troca($texto, '$data', date("d/m/Y"));
            $texto = troca($texto, '$hora', date("H:i"));

            array_push($arr, $email);
            $ok = enviaremail($arr, $subject, $texto);
            echo '<div class="alert alert-success text-left" role="alert">
						  <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
						  <span class="sr-only">' . msg('success') . '</span>
						  ' . msg('has_email_send_from') . ' ' . $email . ' 
						</div>';
        } else {
            echo '<div class="alert alert-danger" role="alert">
						  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
						  <span class="sr-only">Error:</span>
						  ' . msg('user_not_found') . '
						</div>';
        }
    }

    function login_change() {
        $this -> load -> model('skoses');
        $this -> cab(2);
        $form = new form;
        if (!isset($_SESSION['id'])) {
            redirect(base_url('index.php/skos'));
        }
        $id = round($_SESSION['id']);
        if ($id <= 0) {
            redirect(base_url('index.php/skos'));
        }
        $form -> id = $id;
        $cp = array();
        array_push($cp, array('$H8', 'id_us', '', false, true));
        array_push($cp, array('$A2', '', msg('change_my_data'), false, true));
        array_push($cp, array('$S100', 'us_nome', msg('user_nome'), false, false));
        array_push($cp, array('$P20', 'us_password', msg('password'), true, true));
        array_push($cp, array('$B8', '', msg('save'), false, true));
        $tela = $form -> editar($cp, 'users');
        $data['content'] = $tela;
        $data['title'] = '<div style="height: 10%"></div>';
        $this -> load -> view('content', $data);

        if ($form -> saved > 0) {
            redirect(base_url('index.php/skos/myth'));
        }
    }

    /* LOGIN SIGN UP */
    function login_sign_up() {
        $this -> load -> model('skoses');
        $this -> cab(2);

        $nome = get("fullName");
        $email = get("email");
        $data = array();
        $data['email_ok'] = '';
        if (strlen($email) > 0) {
            $ok = validaemail($email);
            if ($ok == 1) {
                $ok = $this -> skoses -> user_exist($email);
                if ($ok == 1) {
                    $id = $this -> skoses -> line['id_us'];
                    $data['email_ok'] = '<a href="' . base_url('index.php/skos/login') . '"><span class="btn alert-danger">' . msg("email_already_inserted") . '</span></a>';
                } else {
                    $data['email_ok'] = '<span class="btn alert-success">' . msg("email_inserted") . '</span>';

                    $this -> skoses -> user_email_send($email, $nome, 'SIGNUP');
                    $this -> skoses -> user_insert_temp($email, $nome);
                }
            } else {
                $data['email_ok'] = '<span class="btn alert-danger">' . msg("email_error") . '</span>';
            }
        }

        $this -> load -> view('skos/thesa_sign_up', $data);
    }

    /* EDIcaO DAS CLASSE */
    function classe() {
        $this -> load -> model('skoses');

        $this -> cab();
        $tela = $this -> skoses -> form(1, 1);
        $data['content'] = $tela;
        $data['title'] = '';
        $this -> load -> view('content', $data);
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

            redirect(base_url('index.php/skos/myth/'));
        }

    }

    function thesa() {
        $this -> load -> model('skoses');

        $this -> cab();
        $us = 1;
        $tela = $this -> skoses -> myskoses(0);
        $data['content'] = $tela;
        $data['title'] = msg('my_thesauros');
        $this -> load -> view('content', $data);
    }

    function select($id = 0, $chk = '') {
        $this -> load -> model('skoses');
        if (checkpost_link($id) == $chk) {
            $this -> skoses -> skoses_select($id);
        }
        redirect(base_url('index.php/skos/terms/' . $id));
    }

    function show($id = '', $chk = '', $ltr = '') {
        $this -> load -> model('skoses');
        $this -> cab();

        if (strlen($id) == 0) {
            $id = $_SESSION['skos'];
        }

        $data = $this -> skoses -> le_skos($id);
        $this -> load -> view('skos/view', $data);

        $this -> load -> view('skos/skos_nav', $data);

        $tela = $this -> skoses -> termos_pg($id);
        $tela = '<div class="row">';
        $tela = '	<div class="col-md-9">';

        /* Tree */
        $tela .= $this -> load -> view('skos/search', null, true);

        $tela .= '	</div>';

        $tela .= '	<div class="col-md-3">';
        $tela .= $this -> skoses -> thesaurus_resume($id);
        $tela .= '<a href="' . base_url('index.php/skos/thes/') . '" class="btn btn-default" style="width: 100%;">' . msg('Conceitual map') . '</a>' . cr();
        $tela .= '<br><br>';
        $tela .= '<a href="' . base_url('index.php/skos/thrs/') . '" class="btn btn-default" style="width: 100%;">' . msg('Report Thesaurus') . '</a>' . cr();
        $tela .= '	</div>';
        $tela .= '</div>';
        $data['content'] = $tela;
        $this -> load -> view('content', $data);
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
            echo '===>' . $idn;
            echo '<br>===>' . $data['ct_term'];
            echo '<br>===>' . $id;
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
        $this -> load -> view('content', $data);

    }

    function concept_add() {
        $this -> load -> model('skoses');
        $this -> cab();
        $id = $_SESSION['skos'];
        $data = $this -> skoses -> le_skos($id);
        $this -> load -> view('skos/view', $data);
        $data['pg'] = 2;
        //$this -> load -> view('skos/skos_nav', $data);
        $this -> load -> view('skos/thesa_admin_menu', null);

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

    function concept_subordinate($th = '', $chk = '', $idt) {

        $this -> load -> model('skoses');
        $this -> cab();
        if (strlen($th) == 0) {
            $th = $_SESSION['skos'];
        }

        $data = $this -> skoses -> le_skos($th);
        $this -> load -> view('skos/view', $data);

        $data = $this -> skoses -> le_term($idt, $th);
        $data['pg'] = 1;
        $this -> load -> view('skos/skos_nav_term', $data);
        if (strlen(trim(trim($data['ct_concept']))) == 0) {
            $data['action'] = '<a href="' . base_url('index.php/skos/concept_create/' . $data['lt_thesauros'] . '/' . checkpost_link($data['lt_thesauros']) . '/' . $data['id_lt']) . '" class="btn btn-primary">' . msg('Term_create_concept') . '</a></li>';
        }
        $this -> load -> view('skos/view_term', $data);

        $this -> skoses -> th_concept_subordinate($idt, $th);
        $tela = '';
        $data['content'] = $tela;
        $this -> load -> view('content', $data);

    }

    function bug_report() {
        /* Load model */
        $this -> load -> model("skoses");
        $this -> cab();

        if (!isset($_SESSION['id']) > 0) {
            redirect(base_url('index.php/skos'));
        }

        $nome = $_SESSION['nome'];
        $email = $_SESSION['user'];
        $id = $_SESSION['id'];

        $form = new form;
        $form -> table = 'bugs';
        $_POST['dd1'] = $nome;
        $_POST['dd3'] = $email;

        $cp = array();
        array_push($cp, array('$H8', '', '', false, FALSE));
        array_push($cp, array('$A', '', $nome, false, false));
        array_push($cp, array('$HV', 'bug_user', $id, true, true));

        array_push($cp, array('$A', '', $email, false, false));

        array_push($cp, array('$T80:7', 'bug_text', msg('report_the_bug'), TRUE, True));
        array_push($cp, array('$B', '', msg('send_bug'), false, True));

        $tela = '<div class="col-md-3 cols-xs-12"><img src="' . base_url('img/icone/bug.png') . '" class="img-responsive"></div>';
        $tela2 = '<div class="col-md-9 cols-xs-12">' . $form -> editar($cp, $form -> table) . '</div>';

        if ($form -> saved > 0) {
            $tela2 .= '<div class="alert alert-success" role="alert">
							' . msg('your_bug_has_reported') . '
							</div>';
        }
        $tela .= $tela2;
        $data['content'] = $tela;
        $data['title'] = msg('report_a_bug');
        $this -> load -> view('content', $data);
    }

    function timg($c = '', $chk = '') {
        $c = round($c);
        $th = $_SESSION['skos'];

        /* Load model */
        $this -> load -> model("skoses");
        $this -> cab(0);
        
        $form = new form;
        $tela = form_open_multipart().cr();
        $tela .= '<!-- MAX_FILE_SIZE deve preceder o campo input -->
                    <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
                    <!-- O Nome do elemento input determina o nome da array $_FILES -->
                    Enviar esse arquivo: <input name="userfile" type="file" class="control-form" />
                    <input type="submit" value="Enviar arquivo" />'.cr();
        $tela .= '</form>'.cr();
        
        if (isset($_FILES['userfile']['tmp_name']))
            {
                $fl = $_FILES['userfile']['name'];

                /* ACERVO */
                $temp = '_acervo';
                dircheck($temp);
                 
                /* IMAGES */
                $temp = '_acervo/image';
                echo '<hr>'.$temp;
                dircheck($temp); 
                                               
                /* IMAGES - DATA */
                $temp = '_acervo/image/'.date("Y");
                dircheck($temp); 

                /* IMAGES - DATA */
                $temp = '_acervo/image/'.date("Y").'/'.date("m").'/';
                dircheck($temp); 
                
                $img = strzero($c,7);
                $ext = substr($fl,strlen($fl)-4,5);
                $ext = troca($ext,'.','');
                
                $uploadfile = $temp.'img-'.$img.'-'.substr(checkpost_link($img),2,10).'.'.$ext;

                switch($ext)
                    {
                    case 'png':
                        $ok = 1;
                        break;                        
                    case 'jpg':
                        $ok = 1;
                        break;
                    default:
                        $ok = 0;
                        echo 'Formato não suportado "'.$ext.'"!';
                        
                    }
                if ($ok==1)
                    {
                        if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                            echo "Arquivo válido e enviado com sucesso.\n";
                            $this->skoses->image_concept($c,$uploadfile);
                            $tela = 'Sucesso!';
                            $tela .= $this->load->view('wclose');
                        } else {
                            echo "Possível ataque de upload de arquivo!\n";
                        }                        
                    }
                

            }
        
        $data['content'] = $tela;
        $data['title'] = '';
        $this->load->view('content',$data);
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
        $this -> load -> view('skos/view_concept_mini', $data);

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

    /***************************************************************************** Definição de conceito */
    function tf($c = '') {
        $c = round($c);
        $th = $_SESSION['skos'];

        /* Load model */
        $this -> load -> model("skoses");
        $this -> cab(0);

        $data = $this -> skoses -> le($c);
        $data['form'] = $this -> skoses -> form_concept_tf($th, $c);
        $this -> load -> view('skos/view_concept_mini', $data);

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

    function tf_edit($id = '', $check = '') {
        $id = round($id);
        $th = $_SESSION['skos'];

        /* Load model */
        $this -> load -> model("skoses");
        $this -> cab(0);

        $data = $this -> skoses -> le($c);
        $data['form'] = $this -> skoses -> form_concept_tf($th, $c);
        $this -> load -> view('skos/view_concept_mini', $data);

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

    /***************************************************************************** TG Geral */
    function tg($c = '') {
        $c = round($c);
        $th = $_SESSION['skos'];

        /* Load model */
        $this -> load -> model("skoses");
        $this -> cab(0);

        $data = $this -> skoses -> le($c);
        $data['form'] = $this -> skoses -> form_concept_tg($th, $c);
        $this -> load -> view('skos/view_concept_mini', $data);

        $action = get("action");
        $tg = get("tg");
        if ((strlen($action) > 0) and (strlen($tg) > 0)) {
            echo $this -> TG;
            $this -> skoses -> assign_as_narrower($tg, $c, $th, 0);
            $this -> load -> view('header/close', null);
            //$this->skoes->
        }

    }

    /***************************************************************************** Conecpt */
    function th($th = '') {
        /* Load model */
        $this -> load -> model("skoses");
        $this -> cab();

        if (strlen($th) == 0) {
            $th = $_SESSION['skos'];
        }
        $data = $this -> skoses -> le_skos($th);
        $this -> load -> view('skos/view', $data);

        $tela = '';
        $tela .= $this -> load -> view('skos/thesa_admin_menu', null, true);
        $data['content'] = $tela;
        $this -> load -> view('content', $data);

        $data['file'] = $th;
        //$this->load->view('grapho/mind_tree',null);
        //$this->load->view('grapho/mind_map',null);
        $this -> load -> view('grapho/mind_map_full', $data);
        //

    }

    function th_edit($id = '', $chk = '') {
        /* Load model */
        $this -> load -> model("skoses");
        $this -> skoses -> th_assosiation_users();

        $this -> cab(1);

        if (!isset($_SESSION['skos'])) {
            $_SESSION['skos'] = $id;
        }

        $id = $_SESSION['skos'];
        $data = $this -> skoses -> le_skos($id);

        $this -> load -> view('skos/view', $data);
        $this -> load -> view('skos/thesa_admin_menu', null);

        if ((count($data) > 0)) {
            if ((isset($_SESSION['id']) and ($_SESSION['id']) == $data['pa_creator']) and ($data['id_pa'] == $id)) {
                $cp = $this -> skoses -> cp_th($id);
                $form = new form;
                $form -> table = $this -> skoses -> table_thesaurus;
                $form -> cp = $cp;
                $form -> id = $id;

                $data['content'] = $form -> editar($cp, $this -> skoses -> table_thesaurus);
                $data['title'] = 'my_thesauros';
                $this -> load -> view('content', $data);

                if ($form -> saved > 0) {
                    if (strlen($id) > 0) {
                        redirect(base_url('index.php/skos/myth/' . $id));
                    } else {
                        redirect(base_url('index.php/skos/myth'));
                    }
                }

                $msg = '';

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

    /***************************************************************************** TR Relacionado */
    function tr($c = '') {
        $c = round($c);
        $th = $_SESSION['skos'];

        /* Load model */
        $this -> load -> model("skoses");
        $this -> cab(0);

        $data = $this -> skoses -> le($c);
        $data['form'] = $this -> skoses -> form_concept_tr($th, $c);
        $this -> load -> view('skos/view_concept_mini', $data);

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

    /***************************************************************************** Termo oculto */
    function tz($c = '') {
        $c = round($c);
        $th = $_SESSION['skos'];

        /* Load model */
        $this -> load -> model("skoses");
        $this -> cab(0);

        $data = $this -> skoses -> le($c);
        $data['form'] = $this -> skoses -> form_concept($th, $c, 'TH');
        $this -> load -> view('skos/view_concept_mini', $data);

        $action = get("action");
        $desc = get("tm");
        $tr = get("tr");

        if ((strlen($action) > 0) and (strlen($tr) > 0) and (strlen($desc) > 0)) {
            echo 'SAVED';
            $this -> skoses -> assign_as_propriety($c, $th, $tr, $desc);
            $this -> load -> view('header/close', null);
            //$this->skoes->
        }
    }

    /***************************************************************************** Termo oculto */
    function glossario($th = '') {
        $th = round($_SESSION['skos']);

        /* Load model */
        $this -> load -> model("skoses");
        $this -> cab(0);

        $data['content'] = $this -> skoses -> glossario($th);
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

    /***************************************************************************** Conecpt */
    function ti($c = '', $th = '') {
        /* Load model */
        $this -> load -> model("skoses");
        $this -> cab();
        $t = troca(get("dd1"), chr(13), ';');
        $t = troca($t, chr(10), '');
        $t = splitx(';', $t);
        $lang = get("dd2");
        if (strlen($lang) == 0) { $lang = 'por';
        }
        for ($r = 0; $r < count($t); $r++) {
            $ta = $t[$r];
            $type = 27;
            $idt = $this -> skoses -> terms_add($ta, $th, $lang);
            $this -> skoses -> association_term_th($ta, $lang, $th);
            $this -> skoses -> association_term_concept($idt, $c, $th, $type);
        }
        redirect(base_url('index.php/skos/c/' . $c));
    }

    /***************************************************************************** Conecpt */
    function c($c = '', $proto = '') {
        $this -> load -> model("skoses");

        $data = $this -> skoses -> le($c);
        if (count($data) == 0) { redirect(base_url('index.php/skos/error/c'));
        }
        $data = $this -> skoses -> le_c($data['id_c'], $data['ct_th']);
        if (count($data) == 0) { redirect(base_url('index.php/skos/error/c'));
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
                $this -> load -> view('skos/view', $datask);
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
            $data['link'] = base_url('index.php/skos/te_remove/' . $id . '/' . $chk);
            $this -> load -> view('confirm.php', $data);
        }
    }

    function ntedit($id = '', $chk = '') {
        /* Load model */
        $edit = true;
        $this -> load -> model("skoses");
        $this -> cab(0);

        $data['content'] = $this -> skoses -> edit_nt($id);
        $data['title'] = '';
        $this -> load -> view('content', $data);

        if (strlen($data['content']) == 0) {
            $this -> load -> view('wclose');
        }

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
        $this -> load -> view('skos/view', $data);
        $data['pg'] = 4;
        $data['editar'] = 1;

        /* menu */
        $this -> load -> view('skos/thesa_admin_menu', null);

        /* Recupera informações sobre o Concecpt */
        $data2 = $this -> skoses -> le_c($c, $th);
        $data2['editar'] = $data['editar'];
        $this -> load -> view('skos/view_concept', $data2);

        $this -> load -> view('skos/thesa_concept_tools', $data2);

        $data['content'] = $data2['logs'];
        $this -> load -> view('content', $data);

        $this -> load -> view('header/footer', null);

    }

    function c2($c = '', $th = '') {

        /* Recupera informações sobre o Concecpt */
        $data2 = $this -> skoses -> le_c($c, $th);
        $data['th'] = $this -> skoses -> le_tree_js($c, $data);

        $data['tree'] = $this -> load -> view('skos/skos_concept_tree_view', $data, true);
        /* assigned */
        $dd10 = get("dd10");
        $dd11 = get("dd11");
        $dd12 = get("dd12");
        switch($dd11) {
            case 'assign_as_narrower' :
                if ($dd12 == $c) {
                    $tm = 0;
                    $this -> skoses -> assign_as_narrower($c, $dd10, $th, $tm);
                }
                break;
        }

        print_r($data2);
        $tela = $this -> load -> view('skos/view_concept', $data2, true);
        $sx = '';
        $sx .= $this -> skoses -> concept_show($c);

        if ($edit) {
            $dt = $this -> skoses -> le_tree_not_assign($th, $data);

            /* ADD NEW TERM */
            $sx .= $this -> load -> view('skos/skos_term_add', $data2, true);

            /* BROADER */
            $sx .= '<form action="' . base_url('index.php/skos/c/' . $c) . '">';
            $sx .= '<font class="middle">' . msg('select_a_concept_to_assign') . '</font>';
            $sx .= '<input type="hidden" value="assign_as_narrower" name="dd11">';
            $sx .= '<input type="hidden" value="' . $c . '" name="dd12">';
            $sx .= '<select name="dd10" size="15" class="middle" style="width: 100%;">';
            for ($r = 0; $r < count($dt); $r++) {
                $line = $dt[$r];
                if ($line['c1'] != $c) {
                    $sx .= '<option value="' . $line['c1'] . '">' . $line['rl_value'] . '</option>' . cr();
                }
            }
            $sx .= '</select>' . cr();
            $sx .= '<br>' . cr();
            $sx .= '<br>' . cr();
            $sx .= '<input type="submit" value="' . msg('assign_as_narrower') . ' >>>" class="btn btn-primary" style="width: 100%">' . cr();
            $sx .= '</form>';

            /* */

            $data['form_terms'] = $sx;
            //$tela .= $this -> load -> view('skos/view_concept_add_broader', $data, true);
        }

        $data['content'] = $tela;
        $this -> load -> view('content', $data);
    }

    /***************************************************************************** term */
    function t($term = '') {

    }

    function edc($idc = 0, $tp = '') {
        /* Load model */
        $this -> load -> model("skoses");
        $this -> load -> view('header/header');
        $form = new form;

        $cp = array();
        array_push($cp, array('$H8', '', '', False, True));
        array_push($cp, array('$S80', '', msg('Term'), True, True));
        array_push($cp, array('$Q id_lang:lang_name:select * from wese_language order by lang_ordem', '', msg('Language'), True, True));
        array_push($cp, array('$Q id_sh:sh_name:select * from wese_scheme order by sh_name', '', msg('sh_name'), True, True));
        array_push($cp, array('$B8', '', msg('add') . ' >>', False, True));

        $tela = $form -> editar($cp, '');

        if ($form -> saved > 0) {
            $dominio = get("dd3");
            $concept = $idc;
            $term = $this -> skoses -> insere_termo(get("dd1"), get("dd2"));
            $this -> skoses -> create_concept($term, $dominio);

            echo '
					<script>
							window.opener.location.reload();
							close(); 
					</script>
					';
        }

        $data['content'] = $tela;
        $this -> load -> view('content', $data);
    }

    function edn($idn = 0, $tp = '') {
        /* Load model */
        $this -> load -> model("skoses");
        $this -> load -> view('header/header');
        $form = new form;
        $form -> id = $idn;
        $cp = array();
        array_push($cp, array('$H8', 'id_wn', '', False, True));
        array_push($cp, array('$T80:12', 'wn_note', msg('Comment'), True, True));
        array_push($cp, array('$B8', '', msg('save') . ' >>', False, True));

        $tela = $form -> editar($cp, 'wese_note');

        if ($form -> saved > 0) {
            echo '
					<script>
							window.opener.location.reload();
							close(); 
					</script>
					';
        }

        $data['content'] = $tela;
        $this -> load -> view('content', $data);
    }

    function error($er = '') {
        $this -> cab();
        $data['title'] = 'Erro 510';
        $data['content'] = msg('concept_not_found');
        $this -> load -> view('skos/510', $data);
    }

    function ed($idc = 0, $tp = '') {
        /* Load model */
        $this -> load -> model("skoses");
        $this -> load -> view('header/header');
        $form = new form;

        switch ($tp) {
            case 'ALT' :
                $cp = array();
                array_push($cp, array('$H8', '', '', False, True));
                array_push($cp, array('$S80', '', msg('Term'), True, True));
                array_push($cp, array('$Q id_lang:lang_name:select * from wese_language order by lang_ordem', '', msg('Language'), True, True));
                array_push($cp, array('$B8', '', msg('add') . ' >>', False, True));
                array_push($cp, array('$HV', '', 'ALT', True, True));
                break;
            case 'PREF' :
                $cp = array();
                array_push($cp, array('$H8', '', '', False, True));
                array_push($cp, array('$S80', '', msg('Term'), True, True));
                array_push($cp, array('$Q id_lang:lang_name:select * from wese_language order by lang_ordem', '', msg('Language'), True, True));
                array_push($cp, array('$B8', '', msg('add') . ' >>', False, True));
                array_push($cp, array('$HV', '', 'PREF', True, True));
                break;
            case 'HIDDEN' :
                $cp = array();
                array_push($cp, array('$H8', '', '', False, True));
                array_push($cp, array('$S80', '', msg('Term'), True, True));
                array_push($cp, array('$Q id_lang:lang_name:select * from wese_language order by lang_ordem', '', msg('Language'), True, True));
                array_push($cp, array('$B8', '', msg('add') . ' >>', False, True));
                array_push($cp, array('$HV', '', 'HIDDEN', True, True));
                break;
            case 'NOTE' :
                $cp = array();
                array_push($cp, array('$H8', '', '', False, True));
                array_push($cp, array('$T80:8', '', msg('Note'), True, True));
                array_push($cp, array('$B8', '', msg('add') . ' >>', False, True));
                array_push($cp, array('$HV', '', 'NOTE', True, True));
                break;
        }
        $tela = $form -> editar($cp, '');

        if ($form -> saved > 0) {
            $type = get("dd4");
            $concept = $idc;
            if (($tp == 'ALT') or ($tp == 'PREF') or ($tp == 'HIDDEN')) {
                $term = $this -> skoses -> insere_termo(get("dd1"), get("dd2"));
                $idt = $this -> skoses -> association_term($term, $concept, $type);
            }
            if (($tp == 'NOTE')) {
                $dd1 = get("dd1");
                $sql = "insert into wese_note (wn_id_c, wn_note) value ($idc,'$dd1')";
                $this -> db -> query($sql);
            }
            echo '
					<script>
							window.opener.location.reload();
							close(); 
					</script>
					';
        }

        $data['content'] = $tela;
        $this -> load -> view('content', $data);

        $this -> load -> view('header/footer', null);
    }

    /* Narrowed */
    function narrows_add($id = '') {
        /* Load model */
        $this -> load -> model("skoses");

        $dd1 = utf8_decode($this -> input -> post('link'));
        $txt = '/thema/';
        $dd1 = substr($dd1, strpos($dd1, $txt) + strlen($txt), strlen($dd1));
        $dd1 = troca($dd1, '/', ';');
        $it = splitx(';', $dd1);

        $c1 = $this -> skoses -> recupera_id_concept($it[1]);
        $c2 = $this -> skoses -> recupera_id_concept($it[2]);

        if (($c1 > 0) and ($c2 > 0)) {
            $this -> skoses -> narrows_link($c1, $c2);
        }
        $sx = '';
        $sx .= '
			<script>
				location.reload();
			</script>
			';
        echo $sx;
    }

    /* Concept */
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

            redirect(base_url('index.php/skos/c/' . $id));
        }

    }

    /* Terms */
    function term($th = '', $idt = '') {

        $this -> load -> model("skoses");

        /* CAB */
        $this -> cab();

        $data = $this -> skoses -> le_th($th);

        $data2 = $this -> skoses -> le_term($idt, $th);
        $data = array_merge($data, $data2);

        $this -> load -> view('skos/view', $data);
        /******************************/

        if (strlen(trim(trim($data['ct_concept']))) == 0) {
            $data['action'] = '<a href="' . base_url('index.php/skos/concept_create/' . $data['lt_thesauros'] . '/' . checkpost_link($data['lt_thesauros']) . '/' . $data['id_rl']) . '" class="btn btn-primary" style="width: 100%;">' . msg('Term_create_concept') . '</a></li>';
            $data['action'] .= '<br/><br/><a href="' . base_url('index.php/skos/term_edit/' . $data['lt_thesauros'] . '/' . checkpost_link($data['lt_thesauros']) . '/' . $data['id_rl']) . '" class="btn btn-warning" style="width: 100%;">' . msg('Term_edit_concept') . '</a></li>';
            $data['action'] .= '<br/><br/><a href="' . base_url('index.php/skos/term_delete/' . $data['lt_thesauros'] . '/' . checkpost_link($data['lt_thesauros']) . '/' . $data['id_rl']) . '" class="btn btn-danger" style="width: 100%;">' . msg('Term_delete_concept') . '</a></li>';
        }
        $this -> load -> view('skos/thesa_admin_menu', null);
        $this -> load -> view('skos/view_term', $data);

        $tela = '';
        $data['content'] = $tela;
        $this -> load -> view('content', $data);

    }

    function term_delete($th = '', $chk = '', $idt = '', $act = '', $chk2 = '') {
        $this -> load -> model('skoses');
        $this -> cab();

        if (strlen($th) == 0) {
            $th = $_SESSION['skos'];
        }
        $data = $this -> skoses -> le_th($th);

        $data2 = $this -> skoses -> le_term($idt, $th);

        if (count($data2) == 0) {
            redirect(base_url('index.php/skos/terms/' . $th));
        }

        $data = array_merge($data, $data2);

        $this -> load -> view('skos/view', $data);
        /******************************/

        $this -> load -> view('skos/view_term', $data);

        $chk3 = checkpost_link($th . 'DEL' . $idt);
        if ($chk2 == $chk3) {

            $rs = $this -> skoses -> delete_term_from_th($th, $idt);

            if ($rs == 1) {
                $tela = $this -> load -> view('success', $data, true);
                $data['content'] = $tela;
                $this -> load -> view('content', $data);
            } else {
                $data['content'] = msg('item_already_deleted');
                $tela = $this -> load -> view('success', $data, true);
                $data['content'] = $tela;
                $this -> load -> view('content', $data);
            }

        } else {
            $data['link'] = base_url('index.php/skos/term_delete/' . $th . '/' . $chk . '/' . $idt . '/DEL/' . $chk3);
            $data['content'] = msg('delete_term_confirm');
            $tela = $this -> load -> view('confirm', $data, True);
            $data['content'] = $tela;
            $this -> load -> view('content', $data);
        }

        $this -> load -> view('header/footer', null);
    }

    function term_edit($th = '', $id2 = '', $idt = '') {
        $this -> load -> model('skoses');
        $this -> cab();

        if (strlen($th) == 0) {
            $th = $_SESSION['skos'];
        }
        $data = $this -> skoses -> le_th($th);

        $data2 = $this -> skoses -> le_term($idt, $th);
        $data = array_merge($data, $data2);

        $this -> load -> view('skos/view', $data);
        /******************************/

        $this -> load -> view('skos/view_term', $data);

        $form = new form;
        $form -> id = $idt;
        $cp = $this -> skoses -> cp_term();
        $data['content'] = $form -> editar($cp, $this -> skoses -> table_terms);
        $data['title'] = '';
        $this -> load -> view('content', $data);

        $tela = '';
        $data['content'] = $tela;
        $this -> load -> view('content', $data);

        if ($form -> saved) {
            redirect(base_url('index.php/skos/term/' . $th . '/' . $idt));
        }
        $this -> load -> view('header/footer', null);
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

        $this -> load -> view('skos/view', $data);
        /******************************/

        $this -> load -> view('skos/view_term', $data);

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
            redirect(base_url('index.php/skos/terms_list/' . $th));
        }
        $this -> load -> view('header/footer', null);
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

    function collaborators() {
        $this -> load -> model('skoses');
        $this -> cab();
        $id = $_SESSION['skos'];
        $data = $this -> skoses -> le_skos($id);
        $this -> load -> view('skos/view', $data);
        $data['pg'] = 2;
        //$this -> load -> view('skos/skos_nav', $data);
        $this -> load -> view('skos/thesa_admin_menu', null);

        $tela = $this -> skoses -> th_users();

        $data['title'] = msg('collaborators');
        $data['content'] = $tela;
        $this -> load -> view('content', $data);

    }

    function terms($id = '', $ltr = '') {
        $this -> load -> model('skoses');
        $this -> cab();

        if (strlen($id) == 0) {
            $id = $_SESSION['skos'];
        }

        $data = $this -> skoses -> le_skos($id);
        $this -> load -> view('skos/view', $data);

        $tela = '';
        $tela .= $this -> load -> view('skos/thesa_admin_menu', null, true);

        $tela .= $this -> skoses -> termos_pg($id);
        $tela .= '<div class="row">';

        $tela .= '	<div class="col-md-9">';
        $tela .= $this -> skoses -> termos_show_letter($id, $ltr);
        $tela .= '	</div>';

        $tela .= '	<div class="col-md-3">';
        $tela .= $this -> skoses -> thesaurus_resume($id);
        if ($this -> skoses -> autho('', $id) == 1) {
            /* TERMO PERDIDOS */
            $tela .= $this -> skoses -> termos_sem_conceito($id, $ltr);
        }
        $tela .= '	</div>';
        $tela .= '</div>';
        $data['content'] = $tela;
        $this -> load -> view('content', $data);

        $this -> load -> view('header/footer', null);

    }

    function terms_list($pag = 0) {
        $this -> load -> model('skoses');
        $this -> cab();

        $id = $_SESSION['skos'];

        $data = $this -> skoses -> le_skos($id);
        $this -> load -> view('skos/view', $data);

        $tela = '';
        $tela .= $this -> load -> view('skos/thesa_admin_menu', null, true);

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

        $form -> row_view = base_url('index.php/skos/term_edit2');
        $form -> row = base_url('index.php/skos/terms_list/');

        $tela .= row($form, $pag);
        $tela .= '</div></div>';
        $data['content'] = $tela;
        $this -> load -> view('content', $data);

        $this -> load -> view('header/footer', null);
    }

    /* Scheme */
    function thema_edit($scheme = '', $concept = '') {
        /* Load model */

        $this -> load -> model("skoses");
        $this -> cab_scheme();

        /* Show list Terms */
        $data = array();
        $data['terms'] = $this -> skoses -> concepts($scheme, $concept);
        $data['terms'] .= '<hr>';
        $data['terms'] .= $this -> skoses -> concepts_no($scheme, $concept);
        if (strlen($concept) > 0) {
            $data['terms_content'] = $this -> skoses -> concept($concept);
        } else {
            $data['terms_content'] = '';
        }

        $this -> load -> view('wese/term_drashboard', $data);

    }

    /* Scheme */
    function thes() {

        $th = $_SESSION['skos'];
        if (strlen($th) == 0) {
            redirect(base_url('index.php/skos'));
        }
        /* Load model */

        $this -> load -> model("skoses");
        $this -> cab();
        $data = $this -> skoses -> le_tree($th);

        $data = array();

    }

    function thrs() {
        $th = $_SESSION['skos'];
        if (strlen($th) == 0) {
            redirect(base_url('index.php/skos'));
        }
        /* Load model */
        
        $this -> load -> model('skoses');
        $this -> cab();

        if (strlen($th) == 0) {
            $th = $_SESSION['skos'];
        }

        $data = $this -> skoses -> le_skos($th);
        $this -> load -> view('skos/view', $data);

        $tela = '';
        $tela .= $this -> load -> view('skos/thesa_admin_menu', null, true);
        $data['content'] = $tela;
        $this -> load -> view('content', $data);
        
        /*************/        
        $data = $this -> skoses -> le_report($th);
    }
    
    function thri($th='') {

        $th = $_SESSION['skos'];
        if (strlen($th) == 0) {
            redirect(base_url('index.php/skos'));
        }
        /* Load model */
        
        $this -> load -> model('skoses');
        $this -> cab();

        if (strlen($th) == 0) {
            $th = $_SESSION['skos'];
        }

        $data = $this -> skoses -> le_skos($th);
        $this -> load -> view('skos/view', $data);

        $tela = '';
        $tela .= $this -> load -> view('skos/thesa_admin_menu', null, true);

        $tela .= '<div class="row">';

        $tela .= '  <div class="col-md-12">';
        $tela .= '<span class="midle"><tt>';
        $tela .= $this -> skoses -> le_tree_sistematic($th);
        $tela .= '</tt></span>';
        $tela .= '  </div>';

        $tela .= '</div>';
        $data['content'] = $tela;
        $this -> load -> view('content', $data);

        $this -> load -> view('header/footer', null);        
    }    

    function scheme($scheme = '') {
        /* Load model */

        $this -> load -> model("skoses");
        $this -> cab();

        /* Set Scheme */
        if (strlen($scheme) > 0) {
            if ($this -> skoses -> scheme_set($scheme) == 1) {
                $tema = $this -> session -> userdata('sh_initials');
                redirect(base_url('index.php/skos/thema/' . $tema));
            }
        }

        /* Show list Scheme */
        $data = array();
        $data['content'] = $this -> skoses -> schemes();
        $this -> load -> view('content', $data);

    }

    /* autenticacao */
    private function validate($act = '') {
        $this -> line = array();
        if ($act == 'out') {
            $ck = array();
            $ck['user'] = null;
            $ck['perfil'] = null;
            $ck['nome'] = null;
            $ck['nivel'] = null;
            $ck['id'] = null;
            $ck['check'] = null;
            $this -> session -> set_userdata($ck);
            return (0);
        }

        $login = get("userName");
        $passw = get("userPassword");

        if ((strlen($login) > 0) and (strlen($passw) > 0)) {
            $sql = "select * from users where us_login = '$login' or us_email = '$login' ";
            $rlt = $this -> db -> query($sql);
            $rlt = $rlt -> result_array();
            if (count($rlt) > 0) {
                $line = $rlt[0];
                $this -> line = $line;
                if (trim($passw) == trim($line['us_password']) and ($line['us_ativo'] == '1')) {
                    $ck = array();
                    $ck['user'] = trim($line['us_login']);
                    $ck['perfil'] = trim($line['us_perfil']);
                    $ck['nome'] = trim($line['us_nome']);
                    $ck['nivel'] = trim($line['us_nivel']);
                    $ck['id'] = trim($line['id_us']);
                    $ck['check'] = md5($line['id_us'] . date("Ymd"));
                    $this -> session -> set_userdata($ck);
                    return (1);
                } else {
                    $ok = $line['us_ativo'];
                    if ($ok == 1) { $ok = -9;
                    }
                    return ($ok);
                }
            } else {
                return (-2);
            }
        } else {
            if (isset($_SESSION['check'])) {
                return (1);
            }
        }
        return (0);
    }

    function user_password_new() {
        /* Load model */
        $this -> load -> model("skoses");
        $this -> cab();

        $id = get("dd0");
        $check = get("chk");

        $chk = checkpost_link($id . "SIGNIN");

        //if ($check == $chk) {
        if ($chk == $chk) {
            //$this->skoses->user_email_send($email,'PASSWORD');
            $ok = $this -> skoses -> le_user_email($id);
            $line = $this -> skoses -> line;

            if ($ok == 1) {
                $passw = get("password");
                $data = $this -> skoses -> line;

                $data['email_ok'] = msg('new_password');
                if (strlen($passw) > 0) { $ok = 1;
                }
                if (strlen(trim($passw)) < 4) {
                    $data['email_ok'] = msg('password_is_short');
                    $ok = 0;
                }
                if ($ok == 1) {
                    $sql = "update users set us_password = '$passw', us_ativo = 1 where id_us = " . round($line['id_us']);
                    $this -> db -> query($sql);
                    redirect(base_url('index.php/skos/login'));
                }
                //$data['email_ok'] .= '['.$passw.']';
                $this -> load -> view('skos/thesa_sign_newpassword', $data);

            } else {
                $data = array();
                $data['title'] = 'Error 534';
                $data['content'] = 'Invalid user';
                $this -> load -> view('skos/510', $data);
            }
        } else {
            $this -> cab(0);
            $data = array();
            $data['title'] = 'Error 533';
            $data['content'] = 'Invalid checkpost';
            $this -> load -> view('skos/510', $data);
        }

    }

    function user_forgot($email = '', $check = '') {
        /* Load model */
        $this -> load -> model("skoses");
        $this -> cab(0);

        $chk = checkpost_link($email . date("Ymd"));
        if ($check == $chk) {

            $this -> skoses -> user_email_send($email, 'PASSWORD');
        } else {
            $this -> cab(0);
            $data = array();
            $data['title'] = 'Error 533';
            $data['content'] = 'Invalid checkpost';
            $this -> load -> view('skos/510', $data);
        }

    }

    function user_revalid() {
        /* Load model */

        $this -> load -> model("skoses");

        $id = get("dd0");
        $check = get("chk");

        $chk = checkpost_link($id);
        if ($check == $chk) {
            $this -> cab();
            if ($this -> skoses -> le_user_id($id) == 1) {
                $line = $this -> skoses -> line;
                $nome = $line['us_nome'];
                $email = $line['us_email'];
                $this -> skoses -> user_email_send($email, $nome, 'SIGNUP');
                $data = array();
                $data['title'] = msg('email');
                $data['content'] = msg('has_send_email_to') . ' ' . $email;
                $this -> load -> view('skos/100', $data);
            } else {
                $data = array();
                $data['title'] = 'Error 535';
                $data['content'] = 'User not found';
                $this -> load -> view('skos/510', $data);
            }
        } else {
            $this -> cab(0);
            $data = array();
            $data['title'] = 'Error 533';
            $data['content'] = 'Invalid checkpost';
            $this -> load -> view('skos/510', $data);
        }

    }

    function user_valid() {
        /* Load model */

        $this -> load -> model("skoses");
        $this -> cab();

        $email = get("dd0");
        $check = get("chk") . get("dd4");
        $acao = get("acao");
        $chk2 = '';
        $chk = checkpost_link($email . date("Ymd"));
        if ($chk == $check) {
            /* CHECK #1 */
            if (strlen($acao) > 0) {
                $pw1 = get("dd1");
                $pw2 = get("dd2");
                if (strlen($pw1) < 5) {
                    if (strlen($pw1) > 0) {
                        $chk2 = '<span class="btn alert-danger">' . msg('password_more_shot') . '</span>';
                    } else {
                        $chk2 = '<span class="btn alert-danger">' . msg('password_is_requered') . '</span>';
                    }
                } else {
                    if ($pw1 != $pw2) {
                        $chk2 = '<span class="btn alert-danger">' . msg('no_match_password') . '</span>';
                    } else {
                        $this -> skoses -> user_set_password($email, $pw1);
                        $data = array();
                        $data['title'] = msg('change_password');
                        $data['content'] = msg('change_password_successful');
                        $data['content'] .= '<br/><br/><a href="' . base_url('index.php/skos/login') . '" class="btn btn-default">' . msg('return') . '</a>';
                        $this -> load -> view('skos/100', $data);
                        return (0);
                    }
                }
            }

            $this -> skoses -> user_valid($email, 1);
            $cp = array();
            array_push($cp, array('$HV', '', $email, True, True));
            array_push($cp, array('$P80', '', msg('new_password'), True, True));
            array_push($cp, array('$P80', '', msg('retype') . ' ' . msg('new_password'), True, True));
            array_push($cp, array('$B8', '', msg('save'), False, True));
            array_push($cp, array('$HV', '', $chk, True, True));
            array_push($cp, array('$M', '', $chk2, True, True));
            $form = new form;

            $form -> id = 0;
            $tela = $form -> editar($cp, '');

            $data['title'] = msg('welcome');
            $data['content'] = msg('define_your_password');
            $data['content'] .= $tela;
            $this -> load -> view('skos/100', $data);

            //$this -> skoses -> user_email_send($email, $nome, 'WELCOME');
        }
    }

    function search($term1 = '') {
        $this -> load -> model("skoses");
        $this -> cab();
        $th = '';

        $term = get("search") . $term1;

        $tela = '<h3>' . $term . '</h3>' . cr();
        $tela .= $this -> skoses -> search_term($term, $th);

        $data['content'] = $tela;
        $data['title'] = '';
        $this -> load -> view('content', $data);

    }

    function export($th = '', $type = 'simple') {
        $this -> load -> model("skoses");
        $this -> skoses -> export($th, $type);
    }

}
?>