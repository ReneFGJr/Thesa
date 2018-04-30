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
            $this->term_edit2($id,$idt);
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
            $data['action'] .= '<br/><br/><a href="' . base_url('index.php/thesa/term_edit/' . $data['lt_thesauros'] . '/' . checkpost_link($data['lt_thesauros']) . '/' . $data['id_rl']) . '" class="btn btn-warning" style="width: 100%;">' . msg('Term_edit_concept') . '</a></li>';
            $data['action'] .= '<br/><br/><a href="' . base_url('index.php/thesa/term_delete/' . $data['lt_thesauros'] . '/' . checkpost_link($data['lt_thesauros']) . '/' . $data['id_rl']) . '" class="btn btn-danger" style="width: 100%;">' . msg('Term_delete_concept') . '</a></li>';
        }
        $this -> load -> view('thesa/header/navbar_tools', null);
        $this -> load -> view('thesa/view/term', $data);

        $tela = '';
		$data['title'] = '';
        $data['content'] = $tela.'<br><br><br>';
        $this -> load -> view('content', $data);
        
        $this->footer();

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
        $data['content'] = $tela.'<br><br>';
        $this -> load -> view('content', $data);
        
		$this->footer();

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

                $this -> footer();
                break;
        }

        //redirect(base_url('index.php/thesa/myskos'));
        $this->footer();
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
            case 'pwsend':
                $this -> cab();
                $this -> socials -> resend();
                break;
                break;
            case 'signup':
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
                $chk2 = checkpost_link($email.$email);
                
                if ($chk != $chk2)
                    {
                        $data['content'] = 'Erro de Check';
                        $this->load->view('content',$data);
                    } else {
                        $dt = $this->socials->le_email($email);
                        if (count($dt) > 0)
                            {
                                $id = $dt['id_us'];
                                $data['title'] = '';
                                $tela = '<br><br><h1>'.msg('change_password').'</h1>';
                                $data['content'] = $tela . $this -> socials -> change_password($id);
                                $this->load->view('content',$data);  
                            } else {
                                $data['content'] = 'Email não existe!';
                                $this->load->view('error',$data);                                
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
            $this -> skoses -> assign_as_propriety($c, $th, $tr, $desc);
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
                $tela .= redirect2($url,2);
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
		
}


?>    