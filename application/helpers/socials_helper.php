<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Form Helpers
 *
 * @package     CodeIgniter
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Rene F. Gabriel Junior <renefgj@gmail.com>
 * @link        http://www.sisdoc.com.br/CodIgniter
 * @version     v0.20.05.06
 */


/*
no MENU PRINCIPAL 
            <li class="nav-item navbar-toggler-right">
                <?php 
                $socials = new socials;
                echo $socials -> menu_user(); 
                ?>
            </li>

Controller
    function social($act = '',$id='',$chk='') {
        $this -> cab();
        $socials = new socials;        
        $socials->social($act,$id,$chk);
        return('');
        }             
*/

/****************** Security login ****************/
function perfil($p, $trava = 0) {
    $ac = 0;
    if (isset($_SESSION['perfil'])) {
        $perf = $_SESSION['perfil'];
        for ($r = 0; $r < strlen($p); $r = $r + 4) {
            $pc = substr($p, $r, 4);
            //echo '<BR>'.$pc.'='.$perf.'=='.$ac;
            if (strpos(' ' . $perf, $pc) > 0) { $ac = 1;
            }
        }
    } else {
        $ac = 0;
    }
    return ($ac);
}

class socials {

    var $table = "users";

    /* Google */
    var $auth_google = 1;
    var $google_redirect = 'http://www.brapci.inf.br/oauth_google.php';
    var $google_key = '205743538602-t6i1hj7p090g5jd4u70614vldnhe7143.apps.googleusercontent.com';
    var $google_key_client = 'AMhQ7Vfc7Lpzi_ZVZKq4wbWV';
    /* Windows */
    var $auth_microsoft = 1;
    var $microsoft_id = '0000000040124367';
    var $microsoft_key = 'JOlz8eVtECgfKt0MKTg0I-aXZrUboW21';

    /* Facebook */
    var $auth_facebook = 1;
    var $face_id = '547858661992170';
    var $face_app = '06d0290245ca0dad338d821792df96aa';
    var $face_url = 'https://www.facebook.com/dialog';
    var $face_redirect = 'http://www.brapci.inf.br/oauth_facebook.php';

    /* Linked in*/
    var $auth_linkedin = 1;
    var $linkedin_url = "https://www.linkedin.com/uas/oauth2/authorization";
    var $linkedin_token = "https://www.linkedin.com/uas/oauth2/accessToken";
    var $linkedin_key = '77rk2tnk7ykhoi';
    var $linkedin_key_user = '0f68b98f-4e38-4980-b631-4f64520c9c2e';
    var $linkedin_key_secret = '06fd1eff-0c5b-4d95-bb7b-681deb588919';
    var $linkedin_redirect = 'http://www.brapci.inf.br/oauth_linkedin.php';

    function captcha()
        {
            $sx = '
            <script src="https://www.google.com/recaptcha/api.js?render=_reCAPTCHA_site_key"></script>
            <script>
                grecaptcha.ready(function() {
                grecaptcha.execute(\'_reCAPTCHA_site_key_\', {action: \'homepage\'}).then(function(token) {
            });
            });
            </script>';
        }

    function social($act='',$id='',$chk='')
    {
        if ($act == 'user_password_new') { $act = 'npass'; }
        if ($act == 'form') { $act = 'login'; }

        switch($act) {
            case 'perfil' :
            $this -> perfil($id,$chk);
            break;
            case 'pwsend' :
            $this -> resend();
            break;
            case 'signup' :
            $this -> signup();
            break;
            case 'logoff' :
            $this -> logout();
            break;
            case 'logout' :
            $this -> logout();
            break;
            case 'forgot' :
            $this -> forgot();
            break;
            case 'npass':
            $this->npass();
            break;
            /******************************* Login *************/
            case 'login' :
            $this -> login();
            break;

            case 'login_local' :
            $this -> login_local();
            break;

            case 'attrib':
            $this->attributes($id,$chk);
            redirect(base_url(PATH.'social/perfil/'.$id));
            break;

            case 'users':
            $this-> row();
            break;

            case 'user_edit':
            $this-> editar($id,$chk);
            break;            

            case 'user_password':
            $this-> change_password($id,$chk);
            break;            

            default :
            echo "Function not found - ".$act;
            break;
        }
    } 

    function npass()
    {            
        $CI = &get_instance();
        $email = get("dd0");
        $chk = get("chk");
        $chk2 = checkpost_link($email . $email);
        $chk3 = checkpost_link($email . date("Ymd"));

        if ((($chk != $chk2) AND ($chk != $chk3)) AND (!isset($_POST['dd1']))) {
            $data['content'] = 'Erro de Check';
            $CI -> load -> view('content', $data);
        } else {
            $dt = $this ->  le_email($email);
            if (count($dt) > 0) {
                $id = $dt['id_us'];
                $data['title'] = '';
                $tela = '<br><br><h1>' . msg('change_password') . '</h1>';
                $new = 1;
                        // Novo registro
                $data['content'] = $tela . $this ->  change_password($id, $new);
                $CI -> load -> view('content', $data);
            } else {
                $data['content'] = 'Email não existe!';
                $CI -> load -> view('error', $data);
            }
        }
        return('');            
    }   

    function createDB() {
        $CI = &get_instance();
        $sql = "CREATE TABLE users_perfil 
        (
        id_pe serial NOT NULL,
        pe_abrev char(4) NOT NULL,
        pe_descricao char(100) NOT NULL,
        pe_nivel int NOT NULL DEFAULT '9'
        ) ENGINE=InnoDB CHARSET=UTF8;

        INSERT INTO users_perfil (id_pe, pe_abrev, pe_descricao, pe_nivel) VALUES
        (1, '#ADM', 'Administrador do Sistema', 9);";
        $CI -> db -> query($sql);

        $sql = "CREATE TABLE users_perfil_attrib 
        (
        id_pa serial NOT NULL,
        pa_user int NOT NULL,
        pa_perfil int NOT NULL,
        pa_check char(32) NOT NULL,
        pa_created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) 
        ENGINE=InnoDB DEFAULT CHARSET=utf8";


        $sql = "CREATE TABLE IF NOT EXISTS users (
        id_us serial NOT NULL,
        us_nome char(80) NOT NULL,
        us_email char(80) NOT NULL,
        us_cidade char(40) NOT NULL,
        us_pais char(40) NOT NULL,
        us_codigo char(7) NOT NULL,
        us_link char(80) NOT NULL,
        us_ativo char(1) NOT NULL,
        us_nivel char(1) NOT NULL,
        us_image text NOT NULL,
        us_genero char(1) NOT NULL,
        us_verificado char(1) NOT NULL,
        us_autenticador char(3) NOT NULL,
        us_cadastro int(11) NOT NULL,
        us_revisoes int(11) NOT NULL,
        us_colaboracoes int(11) NOT NULL,
        us_acessos int(11) NOT NULL,
        us_pesquisa int(11) NOT NULL,
        us_erros int(11) NOT NULL,
        us_outros int(11) NOT NULL,
        us_last int(11) NOT NULL,
        us_perfil text NOT NULL,
        us_login char(20) NOT NULL,
        us_password char(40) NOT NULL
        ) ENGINE=InnoDB;
        ";
        $CI -> db -> query($sql);

        /* insert Super User **********/
        $sql = "INSERT INTO users (id_us, us_nome, us_email, us_cidade, us_pais, us_codigo, us_link, us_ativo, us_nivel, us_image, us_genero, us_verificado, us_autenticador, us_cadastro, us_revisoes, us_colaboracoes, us_acessos, us_pesquisa, us_erros, us_outros, us_last, us_perfil, us_login,us_password) VALUES
        (1, 'Administrador', 'admin', '', '', '0000001', '', '1', '9', '', 'M', '1', '0', 20140706, 0, 0, 400, 0, 0, 0, 20170715, '#ADM', 'admin','21232f297a57a5a743894a0e4a801fc30'); ";
        $CI -> db -> query($sql);
    }

    function logout() {
        /* Salva session */
        $this -> security_logout();
        redirect(base_url(PATH));
    }

    function ac($id = '') {
        $this -> load -> model('users');

        $line = $this -> users -> le($id);
        /* Salva session */
        $ss_id = $line['id_us'];
        $ss_user = $line['us_nome'];
        $ss_email = $line['us_email'];
        $ss_image = $line['us_image'];
        $ss_perfil = $line['us_perfil'];
        $data = array('id' => $ss_id, 'user' => $ss_user, 'email' => $ss_email, 'image' => $ss_image, 'perfil' => $ss_perfil);
        $this -> session -> set_userdata($data);
        redirect(base_url('index.php/home'));
    }

    function perfil($id)
    {
       $CI = &get_instance();
       if (strlen($id) == 0)
       {
        $id = round($_SESSION['id']);
    }
    $dt = $this->le($id);
    if (count($dt) == 0)
    {
        echo "Erro na Identificação do Perfil ".$id;
        exit;
    }
    $sx = $this->show_perfil($dt);

    $cnt = '<ul>';
    $link = '<a href="'.base_url(PATH.'social/user_password/'.$dt['id_us'].'/'.md5($dt['us_login'])).'">';
    $linka = '</a>';
    $cnt .= '<li>'.$link.msg('change_password').$linka.'</li>';
    $cnt .= '</ul>';

    $cnt .= $this->show_attri($id);

    $sx = troca($sx,'<cnt/>',$cnt);

    $d['content'] = $sx;
    $CI->load->view('content',$d);
    return($sx);
}

function menu_user() {
    if (isset($_SESSION['user']) and (strlen($_SESSION['user']) > 0)) {
        $name = $_SESSION['user'];
        $sx = '
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> ' . $name . ' </a>
                
        ';
        $sx = '
        <li class="nav-item dropdown ml-auto">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> ' . $name . ' </a>        

        <div class="dropdown-menu ml-auto" aria-labelledby="navbarDropdownMenuLink" style="right: 0px;">
        <a class="dropdown-item" href="' . base_url(PATH.'social/perfil') . '">' . msg('user_perfil') . '</a>
        <a class="dropdown-item" href="' . base_url(PATH.'social/logout') . '">' . msg('user_logout') . '</a>
        </div>

        </li>       
        ';
    } else {
        $sx = '<A href="#" class="nav-link" data-toggle="modal" data-target="#exampleModalLong">' . msg('sign_in') . '</a>';
        $sx .= $this -> button_login_modal();
    }
    return ($sx);
}

function button_login_modal() {
    $CI = &get_instance();
    $sx = '';
    $sx .= '
    <!-- Modal -->
    <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
    <div class="modal-header">
    <h5 class="modal-title" id="exampleModalLongTitle">' . msg("user_login") . '</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    </div>
    <div class="modal-body">
    <form method="post" action="' . base_url('index.php/social/login') . '">
    <span>' . msg("form_user_name") . '</span><br>
    <input type="text" name="user_login" value="' . get("user_login") . '" class="form-control">
    <br>
    <span>' . msg("form_user_password") . '</span><br>
    <input type="password" name="user_password" value="" class="form-control">

    <br>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">' . msg('cancel') . '</button>
    &nbsp;|&nbsp;
    <input type="submit" name="action" value="' . msg('user_login') . '" class="btn btn-primary">
    </form>
    </div>
    </div>
    </div>
    </div>';
    $data['email_ok'] = '';
    $data['error'] = '';
    $sx = $CI -> load -> view('social/login/login.php', $data, true);
    return ($sx);
}

function login_local() {
    $dd1 = get('dd1') . get("user_login");
    $dd2 = get('dd2') . get("user_password") . get("password");
    $ok = 0;
    if ((strlen($dd1) > 0) and (strlen($dd2) > 0)) {
        $dd1 = troca($dd1, "'", '´');
        $dd2 = troca($dd2, "'", '´');
        $ok = $this -> security_login($dd1, $dd2);
        if ($ok != 1)
        {
            redirect(base_url(PATH.'social/login?err=login_error'));
        } else {
            redirect(base_url(PATH));    
        }
        echo "OK = ".$ok;
        exit;
        
    }
    return ($ok);
}

function login($simple=0) {
    $CI = &get_instance();
        //$this -> load -> view('auth_social/login_pre', null);
    if ($simple == 1)
    {
        $CI -> load -> view('social/login/login_simple', null);    
    } else {
        $CI -> load -> view('social/login/login_signin', null);    
    }

        //$this -> load -> view('auth_social/login_horizontal', null);
}

public function session($provider) {
    $CI = &get_instance();
    $this -> load -> helper('url_helper');
        //facebook
    if ($provider == 'facebook') {
            //$app_id = $this -> config -> item('fb_appid');
        $app_id = $this -> face_id;
            //$app_secret = $this -> config -> item('fb_appsecret');
        $app_secret = $this -> face_app;
        $provider = $this -> oauth2 -> provider($provider, array('id' => $app_id, 'secret' => $app_secret, ));
    }
        //google
    else if ($provider == 'google') {

            //$app_id = $this -> config -> item('googleplus_appid');
        $app_id = $this -> google_key;

            //$app_secret = $this -> config -> item('googleplus_appsecret');
        $app_secret = $this -> google_key_client;
        $provider = $this -> oauth2 -> provider($provider, array('id' => $app_id, 'secret' => $app_secret, ));
    }

        //foursquare
    else if ($provider == 'foursquare') {

        $app_id = $this -> config -> item('foursquare_appid');
        $app_secret = $this -> config -> item('foursquare_appsecret');
        $provider = $this -> oauth2 -> provider($provider, array('id' => $app_id, 'secret' => $app_secret, ));
    }
    if (!$this -> input -> get('code')) {
            // By sending no options it'll come back here
        $provider -> authorize();
        redirect('social?erro=ERRO DE LOGIN');
    } else {
            // Howzit?
        try {
            $token = $provider -> access($_GET['code']);
            $user = $provider -> get_user_info($token);

            /* Ativa sessão ID */
            $ss_user = $user['name'];
            $ss_email = trim($user['email']);
            $ss_image = $user['image'];
            $ss_nome = $user['name'];
            $ss_link = $user['urls']['Facebook'];
            $ss_nivel = 0;

            $sql = "select * from users where us_email = '$ss_email' ";
            $query = $CI -> db -> query($sql);
            $query = $query -> result_array();
            $data = date("Ymd");

            if (count($query) > 0) {
                /* Atualiza quantidade de acessos */
                $line = $query[0];
                $ss_nivel = $line['us_nivel'];
                $id_us = $line['id_us'];
                $id = $line['id_us'];

                $sql = "update users set us_last = '$data',
                us_acessos = (us_acessos + 1) 
                where us_email = '$ss_email' ";
                $CI -> db -> query($sql);
            } else {
                $sql = "insert into users 
                (
                us_nome, us_email, us_cidade, 
                us_pais, us_codigo, us_link,
                us_ativo, us_nivel, us_genero, us_verificado, 
                us_cadastro, us_last
                ) values (
                '$ss_nome','$ss_email','',
                '','','$ss_link',
                1,0,'',1,
                $data,$data
            )";
            $CI = &get_instance();
            $CI -> db -> query($sql);

            $c = 'us';
            $c1 = 'id_' . $c;
            $c2 = $c . '_codigo';
            $c3 = 7;
            $sql = "update users set us_codigo = lpad($c1,$c3,0) where $c2='' ";
            $rlt = $CI -> db -> query($sql);

            $sql = "select * from users where us_email = '$ss_email' ";
            $query = $CI -> db -> query($sql);
            $query = $query -> result_array();
            $line = $query[0];
            $id = $line['id_us'];
        }
        $ss_perfil = $line['us_perfil'];

        /* Salva session */
        $data = array('id' => $id, 'user' => $ss_user, 'email' => $ss_email, 'image' => $ss_image, 'nivel' => $ss_nivel, 'perfil' => $ss_perfil);
        $this -> session -> set_userdata($data);

        if ($this -> uri -> segment(3) == 'google') {
                    //Your code stuff here
        } elseif ($this -> uri -> segment(3) == 'facebook') {
                    //your facebook stuff here

        } elseif ($this -> uri -> segment(3) == 'foursquare') {
                    // your code stuff here
        }

        $this -> session -> set_flashdata('info', $message);
        redirect('social?tabindex=s&status=sucess');

    } catch (OAuth2_Exception $e) {
        show_error('That didnt work: ' . $e);
    }

}
}

function cp($id) {
    $cp = array();
    array_push($cp, array('$H8', 'id_us', '', False, True));
    array_push($cp, array('$S80', 'us_nome', 'Nome', True, True));
    if ($id == 0) {
        array_push($cp, array('$S80', 'us_email', 'login/email', True, True));
        array_push($cp, array('$P20', '', 'Senha', True, True));
        $pass = $this -> password_cripto(get("dd3"), 'T');
        array_push($cp, array('$HV', 'us_password', $pass, True, True));
    }
    array_push($cp, array('$HV', 'us_login', get("dd2"), True, True));
    array_push($cp, array('$O 1:SIM&0:NÃO', 'us_ativo', 'Ativo', True, True));
    return ($cp);
}

function create_admin_user() {
    $dt = array();
    $dt['us_nome'] = 'Super User Admin';
    $dt['us_email'] = 'admin';
    $dt['us_password'] = md5('admin');
    $dt['us_autenticador'] = 'MD5';
    $this -> insert_new_user($dt);
}

function row($id = '') {
    $form = new form;

    $form -> fd = array('id_us', 'us_nome', 'us_login');
    $form -> lb = array('id', msg('us_name'), msg('us_login'));
    $form -> mk = array('', 'L', 'L', 'L');

    $form -> tabela = $this -> table;
    $form -> see = true;
    $form -> novo = true;
    $form -> edit = true;

    $form -> row_edit = base_url(PATH.'social/user_edit');
    $form -> row_view = base_url(PATH.'social/perfil');
    $form -> row = base_url(PATH.'social/users');

    $sx = '<div class="container">';
    $sx .= '<div class="row">';
    $sx .= '<div class="col-12">';
    $sx .= row($form, $id);
    $sx .= '</div>';
    $sx .= '</div>';
    $sx .= '</div>';
    $data['content'] = $sx;
    $this->load->view('content',$data);
    return ("");
}

function editar($id, $chk) {
    $form = new form;
    $form -> id = $id;
    $cp = $this -> cp($id);
    $data['title'] = '';
    $data['content'] = $form -> editar($cp, $this -> table);
    $this -> load -> view('content', $data);

    if ($form->saved > 0)
    {
        redirect(base_url(PATH.'social/users'));
    }
    return ($form -> saved);
} 

function insert_new_user($data) {
    $CI = &get_instance();
    $email = $data['us_email'];
    $nome = $data['us_nome'];
    $senha = $data['us_password'];
    $auth = $data['us_autenticador'];
    $inst = '';
    if (isset($data['us_institution'])) {
        $inst = $data['us_institution'];
    }

    $sql = "select * from " . $this -> table . " where us_email = '$email' ";
    $rlt = $CI -> db -> query($sql);
    $rlt = $rlt -> result_array();
    if (count($rlt) == 0) {
        $sql = "insert into " . $this -> table . " 
        (us_nome, us_email, us_password, us_ativo, us_autenticador,
        us_perfil, us_perfil_check, us_institution,
        us_login, us_badge)
        values
        ('$nome','$email','$senha','1', '$auth',
        '','','$inst',
        '$email','')
        ";
        $CI -> db -> query($sql);
        $this -> updatex();
        $this -> update_perfil_check($data);
        return (1);
    } else {
        return (-1);
    }
}

function resend() {
    $email = get("dd0");
    $chk = get("chk");
    $chk2 = md5($email . date("Ymd") . $email);
    if ($chk2 == $chk) {

    } else {
        $data['content'] = 'Erro de checagem dos dados!';
        $this -> load -> view('error', $data);
    }
}

function le($id, $fld = 'id') {
    $CI = &get_instance();
    $sql = "select * from " . $this -> table;
    switch($fld) {
        case 'id' :
        $sql .= ' where id_us = ' . round($id);
        break;
        case 'login' :
        $sql .= " where us_email = '$id' ";
        break;
        default :
        $sql .= ' where id_us = ' . round($id);
        break;
    }
    $rlt = $CI -> db -> query($sql);
    $rlt = $rlt -> result_array();
    if (count($rlt) == 0) {
        return ( array());
    } else {
        return ($rlt[0]);
    }
}

function updatex() {
    $CI = &get_instance();
    $sql = "update " . $this -> table . " set us_badge = lpad(id_us,5,0) where us_badge = '' or us_badge is null ";
    $CI -> db -> query($sql);
}

function update_perfil_check($data) {
    $CI = &get_instance();
    if (isset($data['us_email'])) {
        $usr = $this -> le($data['us_email'], 'login');
        $id = $usr['id_us'];
        $pass = $usr['us_password'];
        $perfil = $usr['us_perfil'];
        $check = md5($id . $perfil);
        $sql = "update " . $this -> table . " set us_perfil_check = '$check' where id_us = $id ";
        $rlt = $CI -> db -> query($sql);
        return ('1');
    }
    if (isset($data['id_us'])) {
        $usr = $this -> le($data['id_us'], 'id');
        $id = $usr['id_us'];
        $pass = $usr['us_password'];
        $perfil = $usr['us_perfil'];
        $check = md5($id . $perfil);
        $sql = "update  " . $this -> table . " set us_perfil_check = '$check' where id_us = $id ";
        $rlt = $CI -> db -> query($sql);
        return ('1');
    }
}

/****************** Security login ****************/
function security() {
    $ok = 0;
    if (isset($_SESSION['id'])) {
        $id = round($_SESSION['id']);
        if ($id > 0) {
            return ('');
        }
    }

    redirect(base_url('index.php/social/login'));
}

function security_logout() {
    $CI = &get_instance();
    $data = array('id' => '', 'user' => '', 'email' => '', 'image' => '', 'perfil' => '');
    $CI -> session -> set_userdata($data);
    return ('');
}

function xxx_action($path, $d1, $d2) {
    $CI = &get_instance();
    $path = troca($path,'form','signin');

    switch ($path) {
        case 'login' :
        $user = get("user_login");
        $pass = get("user_password");
        $ok = $this -> security_login($user, $pass);
        if ($ok != 1) {
            redirect(base_url(PATH.'social/form'));
        } else {
            redirect(base_url(PATH));
        }
        break;
        case 'logout' :
        $this -> logout();
        redirect(base_url(PATH));
        default :
        echo 'Método não implementado "'.$path.'"';
        exit ;
    }
}

function security_login($login = '', $pass = '') {
    $CI = &get_instance();
    /****************************** FORCA LOGIN **************/        
    $forced = 0;

    $sql = "select * from " . $this -> table . " where (us_email = '$login' OR us_login = '$login') OR (1=$forced) limit 1";
    $rlt = $CI -> db -> query($sql);
    $rlt = $rlt -> result_array();

    if (count($rlt) > 0) {
        $line = $rlt[0];

        $dd2 = $this -> password_cripto($pass, $line['us_autenticador']);
        $dd3 = trim($line['us_password']);

        if (($dd2 == $dd3) or ($pass == $dd3) or ($forced == 1)) {
            /* Salva session */
            
            $ss_id = $line['id_us'];
            $ss_user = $line['us_nome'];
            $ss_email = $line['us_email'];
            $ss_image = $line['us_image'];
            $ss_perfil = $line['us_perfil'];
            $ss_nivel = $line['us_nivel'];
            $data = array('id' => $ss_id, 'user' => $ss_user, 'email' => $ss_email, 'image' => $ss_image, 'perfil' => $ss_perfil, 'nivel'=>$ss_nivel);
            $CI -> session -> set_userdata($data);
            //$CI -> session -> set_flashdata($data);
            //$_SESSION['usuario'] = $line['us_nome'];
            return (1);
        } else {
            return (0);
        }
    } else {
        return (-1);
    }
}

function my_account($id) {
    $CI = &get_instance();
    $this -> load -> model('user_drh');

    $data1 = $this -> le($id);
    $data2 = $this -> user_drh -> le($id);
    $data = array_merge($data1, $data2);

    $tela = $this -> load -> view('auth_social/myaccount', $data, true);
    return ($tela);
}

function password_cripto($pass, $tipo) {
    $pass = trim($pass);
    switch ($tipo) {
        case 'TXT' :
        $dd2 = trim($pass);        
        break;

        default :
        $dd2 = md5($pass);
        break;
    }
    return ($dd2);
}

function change_password($id, $new = 0) {
    $CI = &get_instance();
    $form = new form;
    $cp = array();
    array_push($cp, array('$H8', '', '', false, false));
    if ($new == 0) {
        array_push($cp, array('$P20', '', 'Senha atual', True, True));
    } else {
        array_push($cp, array('$HV', '', '', False, False));
    }
    array_push($cp, array('$P20', '', 'Nova senha', True, True));
    array_push($cp, array('$P20', '', 'Confirme nova senha', True, True));

    array_push($cp, array('$B', '', 'Alterar senha', false, True));

    $dt = $this->le($id);

    $tela = $this->show_perfil($dt);
    $tela .= '<hr>';
    $tela .= $form -> editar($cp, '');

    /* REGRAS DE VALIDACAO */
    $data = $this -> le($id);
    $pass = get("dd1");
    $dd3 = $data['us_password'];
    $p1 = get("dd2");
    $p2 = get("dd3");
    $dd2 = get("dd2");

    if ($form -> saved > 0) {
        if (($dd2 == $dd2) and (strlen($dd2) > 0)) {
            if (($p1 == $p2) or ($new == 1)) {
                $sql = "update " . $this -> table . " set us_password = '" . md5($p1) . "', us_autenticador = 'MD5' where id_us = " . $id;
                $CI -> db -> query($sql);
                redirect(base_url(PATH.'social/perfil/'.$id));
            } else {
                $tela .= '<div class="alert">Senhas não conferem</div>';
            }
        } else {
            $tela .= '<div class="alert">Senhas atual não confere!</div>';
        }
    }
    $sx = '<div class="container"><div class="row"><div class="col-12">';
    $sx .= $tela;
    $sx .= '</div></div></div>';
    $d['content'] = $sx;
    $CI->load->view('content',$d);
    return ('');
}

function user_id() {
    if (!isset($_SESSION['id'])) {
        return (0);
    }

    $us = round($_SESSION['id']);
    return ($us);

}

function user_email_send($para, $nome, $code) {
    $CI = &get_instance();
    $anexos = array();
    $texto = $this -> email_cab();
    $de = 0;
    switch($code) {
        case 'SIGNUP' :
        $link = base_url(PATH.'social/npass/?dd0=' . $para . '&chk=' . checkpost_link($para . $para));
        $assunto = utf8_decode('Cadastro de novo usuários - '.LIBRARY_NAME);
        $texto .= utf8_decode('<p>' . msg('Dear') . ' <b>' . $nome . ',</b></p>');
        $texto .= utf8_decode('<p>Para ativar seu cadastro é necessário clicar no link abaixo:');
        $texto .= '<br><br>';
        $texto .= '<a href="' . $link . '" target="_new">' . $link . '</a></p>';
        $de = 1;
        break;
        case 'PASSWORD' :
        $this -> le_user_id($para);
        $link = base_url(PATH.'social/user_password_new/?dd0=' . $para . '&chk=' . checkpost_link($para . date("Ymd")));
        $assunto = msg('Cadastro de nova senha') . ' - '.LIBRARY_NAME;
        $texto .= '<p>' . msg('Dear') . ' ' . $this -> line['us_nome'] . '</p>';
        $texto .= '<p>' . utf8_decode(msg('change_new_password')) . '</p>';
        $texto .= '<br><br>';
        $texto .= '<a href="' . $link . '" target="_new">' . $link . '</a>';
        $de = 1;
        break;

        case 'FORGOT':
        $data = $this->le_email($para);
        $link = base_url(PATH.'social/user_password_new/?dd0=' . $para . '&chk=' . checkpost_link($para . date("Ymd")));
        $assunto = msg('Cadastro de nova senha') . ' - SignIn';
        $texto .= '<p>' . msg('Dear') . ' ' . $data['us_nome'] . '</p>';
        $texto .= '<p>' . utf8_decode(msg('change_new_password')) . '</p>';
        $texto .= '<br><br>';
        $texto .= '<a href="' . $link . '" target="_new">' . $link . '</a>';
        $de = 1;
        break;
        default :
        $assunto = 'Enviado de e-mail';
        $texto .= ' Vinculo não informado ' . $code;
        $de = 1;
        break;
    }
    $texto .= $this -> email_foot();
    if ($de > 0) {
        $CI->load->helper('email');
        enviaremail($para, $assunto, $texto, $de);
    } else {
        echo 'e-mail não enviado - ' . $code;
    }
}

/***** EMAIL */
function email_cab() {
    $sx = '<table width="600" align="center"><tr><td>';
    $sx .= '<font style="font-family: Tahoma, Verdana, Arial; font-size: 14px;">' . cr();
    $sx .= '<font style="font-size: 24px; color: #1D0E9B; font-family: Tahoma, Arial">'.LIBRARY_NAME.'</font>' . cr();
    $sx .= '<br>';
    $sx .= utf8_decode('<font style="color: #1D0E9B; font-size: 12px;">Base de dados em Ciência da Informação</font>') . cr();
    $sx .= '<hr>';
    return ($sx);
}

function email_foot() {
    $sx = '';
    $sx .= '<hr>';
    $sx .= '</td></tr></table>';
    return ($sx);
}

function signup() {
    $CI = &get_instance();
    $data = array();
    $name = get("fullName");
    $email = get("email");
    $inst = get("Institution");

    if ((strlen($name) > 0) and (strlen($email))) {
        $dt = array();
        $dt['us_nome'] = $name;
        $dt['us_email'] = $email;
        $dt['us_institution'] = $inst;
        $dt['us_password'] = md5(date("YmdHis"));
        $dt['us_autenticador'] = 'MD5';
        $rs = $this -> insert_new_user($dt);
            //$rs = 1;
        $data['erro'] = $rs;

        if ($rs == 1) {
            $code = 'SIGNUP';
            $this -> user_email_send($email, $name, $code);                
            $CI -> load -> view('social/login/login_signup_sucess', $dt);
            return ("");
        }
    }

    $CI -> load -> view('social/login/login_signup', $data);
}

function forgot() {
    $CI = &get_instance();
    $data = array();
    $email = get("user_login");

    if (strlen($email) > 0) {
        $dt = array();
        $rs = 1;
        if ($rs == 1) {
           $dt = $this->le_email($email);
           $code = 'FORGOT';
           if (count($dt) > 0)
           {
              $this -> user_email_send($email, '', $code);
              $CI -> load -> view('social/login/login_signup_sucess', $dt);
              return ("");						
          } else {
              $sx = 'Email nao localizado';
              return("");
          }
      }
  }

  $CI -> load -> view('social/login/login_forgot', $data);
}


function le_email($e) {
    $CI = &get_instance();
    $sql = "select * from users where us_email = '$e'";
    $rlt = $CI -> db -> query($sql);
    $rlt = $rlt -> result_array();
    if (count($rlt) > 0) {
        $line = $rlt[0];
        return ($line);
    } else {
        return ( array());
    }
}
function token($t)
{
    $CI = &get_instance();
    $t = troca($t,"'",'´');
    $sql = "select * from users where us_password = '$t' ";
    $rlt = $CI->db->query($sql);
    $rlt = $rlt->result_array();
    if (count($rlt) == 1)
    {
        $line = $rlt[0];
        return($line);                    
    } else {
        return(array());
    }
}

function attributes($id,$ack)
{
    $CI = &get_instance();
    if (perfil("#ADM"))
    {
        $cmd = substr($ack,0,1);
        switch($cmd)
        {
            case 'R':
            $sql = "delete from users_perfil_attrib where id_pa = ".sonumero($ack);
            echo $sql;
            break;

            case 'A':
            $idf = sonumero($ack);
            $sql = "insert into users_perfil_attrib 
            (
            pa_user, pa_perfil
            ) values (
            $id,$ipf
        )";
        echo $sql;
        break;
    }
}
}

function show_attri($id)
{
    $CI = &get_instance();
    $sql = "select * from users_perfil_attrib 
    inner join users_perfil ON id_pe = pa_perfil
    where pa_user = ".$id;
    $rlt = $CI->db->query($sql);
    $rlt = $rlt->result_array();
    $sx = '<table class="table">';
    $sx .= '<tr>';
    $sx .= '<th>ID</th>';
    $sx .= '<th>Perfil</th>';
    $sx .= '<th>Atribuído</th>';
    $sx .= '<th>Ação</th>';
    $sx .= '</tr>';
    $nivel = $_SESSION['nivel'];
    for ($r=0;$r < count($rlt);$r++)
    {
        $line = $rlt[$r];
        $sx .= '<tr>';
        $sx .= '<td>'.$line['pe_abrev'].'</td>';
        $sx .= '<td>'.$line['pe_descricao'].'</td>';
        $sx .= '<td>'.stodbr($line['pa_created']).'</td>';

        if ($nivel >= $line['pe_nivel'])
        {
            $link = '<a href="'.base_url(PATH.'social/attrib/'.$id.'/R'.$line['id_pa']).'" style="color: red;">';
            $linka = '</a>';
            $sx .= '<td>'.$link.'[remover]'.$linka.'</td>';
        } else {
            $sx .= '<td>-</td>';
        }
        $sx .= '</tr>';
    }
    $sx .= '</table>';
    return($sx);
}
function show_perfil($dt)
{
    $CI = &get_instance();
    if (!is_array($dt))
    {
        $dt = $this->le($dt);
    }
    $sx = '<div class="row">';
    $sx .= '<div class="col-2">';
    $sx .= '</div>';
    $sx .= '<div class="col-8">';
    $sx .= '<h2>'.$dt['us_nome'].'</h2>';
    $sx .= '<h6>'.msg('login').': <b>'.$dt['us_login'].'</b></h6>';
    $sx .= '<cnt/>';
    $sx .= '</div>';

    $sx .= '<div class="col-2">';
    if ($dt['us_ativo'] == 1)
    {
        $sx .= '<span class="btn btn-success fluid">'.msg('active').'</span>';
    }
    $sx .= '</div>';
    $sx .= '</div>';
    return($sx);
}
}
?>