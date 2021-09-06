<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
* CodeIgniter Form Helpers
*
* @package     CodeIgniter
* @subpackage  Helpers
* @category    Helpers
* @author      Rene F. Gabriel Junior <renefgj@gmail.com>
* @link        http://www.sisdoc.com.br/CodIgniter
* @version     v0.21.04.30
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
    $data['content'] = $socials->social($act,$id,$chk);
    if (strlen($data['content']) > 0)
    {
        $this->load->view('content',$data);
    }
    return('');
}             
*/
/****************** Security login ****************/
function perfil($p, $trava = 0)
{
    $ac = 0;
    if (isset($_SESSION['perfil'])) {
        $perf = $_SESSION['perfil'];
        for ($r = 0; $r < strlen($p); $r = $r + 4) {
            $pc = substr($p, $r, 4);
            //echo '<BR>'.$pc.'='.$perf.'=='.$ac;
            if (strpos(' ' . $perf, $pc) > 0) {
                $ac = 1;
            }
        }
    } else {
        $ac = 0;
    }
    return ($ac);
}

class socials
{
    
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

    var $user_picture_dir = '_repositorio/users/';
    
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
    
    function social($act = '', $id = '', $chk = '')
    {
        $sx = '';
        if ($act == 'user_password_new') {
            $act = 'npass';
        }
        if ($act == 'form') {
            $act = 'login';
        }
        
        switch ($act) {
            case 'perfil':
                $this->perfil($id, $chk);
            break;
            case 'pwsend':
                $this->resend();
            break;
            case 'signup':
                $this->signup();
            break;
            case 'logoff':
                $this->logout();
            break;
            case 'logout':
                $this->logout();
            break;
            case 'forgot':
                $this->forgot();
            break;
            case 'npass':
                $this->npass();
            break;
            /******************************* Login *************/
            case 'login':
                $this->login();
            break;
            
            case 'login_local':
                $this->login_local();
            break;

            case 'email':
                $sx = $this->email($id,$chk);
            break;

            case 'group':
                $this->groups($id, $chk);                
            break;  

            case 'group_members':
                if (perfil("#ADM#GER"))
                {            
                    $this->groups_members($id);
                }
            break;

            case 'group_edit':
                if (perfil("#ADM#GER"))
                {
                    $this->group_edit($id);
                }
            break;  
            
            case 'attrib':
                $this->attributes($id, $chk);
                redirect(base_url(PATH . 'social/perfil/' . $id));
            break;
            
            case 'users':
                $this->row();
            break;
            
            case 'user_edit':
                $this->editar($id, $chk);
            break;
            
            case 'user_password':
                $this->change_password($id, $chk);
            break;
            
            default:
            if (perfil("#ADMIN")) {
                $sx = '<h1>Socials Menu</h1>';
                $sx .= '<ul>';
                $sx .= '<li><a href="' . base_url(PATH . 'social/email/') . '">' . msg('email_test') . '</a></li>';
                $sx .= '<li><a href="' . base_url(PATH . 'social/group/') . '">' . msg('groups') . '</a></li>';

                $sx .= '</ul>';
            } else {
                $sx = message("Function not found - " . $act, 3);
            }
        }
        return ($sx);
    }

    function group_edit($id='')
        {
            $sx = '
            <div class="row">
            <div class="'.bscol(12).'">
            <h1>Grupos de usuários</h1>';

            $CI = &get_instance();
            $form = new form;
            $form->id = round($id);
            $cp = array();
            array_push($cp,array('$H8','id_gr','',false,false));
            array_push($cp,array('$S100','gr_name',msg('gr_name'),true,true));
            array_push($cp,array('$S10','gr_hash',msg('gr_hash'),true,true));
            if ($id == 0)
                {
                    array_push($cp,array('$HV','gr_library',LIBRARY,true,true));
                } else {
                    array_push($cp,array('$S20','gr_library',msg('gr_library'),false,false));
                }       
            $sx .= $form->editar($cp,'users_group');
            $sx .= '</div></div>';

            /* Saved */
            if ($form->saved > 0) { redirect(base_url(PATH.'social/group')); }
            $data['content'] = $sx;
            $CI->load->view('content',$data);            
        }

    function groups_members($id)         
        {
            $CI = &get_instance();
            $id = round($id);
            $sx = $this->group_show($id);
            $sx .= $this->group_members_show($id);

            $data['content'] = $sx;
            $CI->load->view('content',$data); 
        }

    
    function group_show($id)
        {
            $CI = &get_instance();
            $sql = "select * from users_group 
                        where gr_library = '".LIBRARY."' and id_gr = $id";
            $rlt = $CI->db->query($sql);
            $rlt = $rlt->result_array();            
            $line = $rlt[0];
            $sx = '<div class="row" style="border-bottom: 1px solid #000000">';
            $sx .= '<div class="'.bscol(11).'  big">'.$line['gr_name'].'</div>';
            $sx .= '<div class="'.bscol(1).'">'.$line['gr_hash'].'</div>';
            $sx .= '</div>';
            return($sx);
        }

    function group_members_include($gr,$us,$lb)
        {
            $CI = &get_instance();
            $sql = "select * from users_group_members
                    where
                    grm_group = $gr
                    and grm_user = $us
                    and grm_library = '$lb'";
            $rlt = $CI->db->query($sql);
            if ($rlt->result_id->num_rows == 0)
                {
                    $sql = "insert into users_group_members
                            (grm_group, grm_user, grm_library)
                            values
                            ($gr, $us, '$lb')";
                    $rlt = $CI->db->query($sql);
                    return(TRUE);
                }
            return(FALSE);            
        }

    function group_members_show($id)
        {
            $CI = &get_instance();
            if (perfil("#ADM#GES") > 0)
            {
                $us = get("us");
                $chk = get("chk");
                if (checkpost_link($us.$id) == $chk)
                    {
                        $this->group_members_include($id,$us,LIBRARY);
                    }
            }

            $sql = "select * from users_group_members
                        inner join users ON id_us = grm_user
                        where grm_library = '".LIBRARY."' and grm_group = $id";
            $rlt = $CI->db->query($sql);
            $rlt = $rlt->result_array();            
            $sx = '<div class="row" style="margin-bottom: 50px;">';
            for ($r=0;$r < count($rlt);$r++)
            {
                $line = $rlt[$r];
                $link = '<a href="#">'.'[remove]'.'</a>';
                $sx .= '<div class="'.bscol(6).'">'.$line['us_nome'].'</div>';
                $sx .= '<div class="'.bscol(5).'">'.$line['us_login'].'</div>';
                $sx .= '<div class="'.bscol(1).'">'.$link.'</div>';
                //$sx .= '<div class="'.bscol(1).'">'.$line['gr_hash'].'</div>';
            } 
            if (count($rlt) == 0)
                {
                    $sx .= message('Sem usuários atribuídos',3);
                }
            $sx .= '</div>';

            if (perfil("#ADM#GES") > 0)
            {                
                $form = new form;
                $form->id = 0;
                $cp = array();
                array_push($cp,array('$H8','','',false,false));
                array_push($cp,array('$S100','',msg('user_name'),true,true));
                $sx .= '<div class="row" >';
                $sx .= '<div class="'.bscol(12).'" style="border-top: 1px solid #000000">';
                $sx .= '<span class="big">Buscar / inserir</span>';
                $sx .= $form->editar($cp,'');
                $sx .= '</div>';
                $sx .= '</div>';

                /* */
                $q = get("dd1");
                if (strlen($q) > 0)
                {
                    $sx .= '<div class="row" style="border-bottom: 1px solid #000000">';
                    $sx .= '<div class="'.bscol(12).'">';
                    $sql = "select * from users where us_nome like '%$q%' order by us_nome";
                    $rlt = $CI->db->query($sql);
                    $rlt = $rlt->result_array();
                    $sx .= '<ul>';
                    for ($r=0;$r < count($rlt);$r++)
                        {
                            $line = $rlt[$r];
                            $link = '<a href="'.base_url(PATH.'social/group_members/'.$id.'/?dd1='.$q.'&us='.$line['id_us'].'&chk='.checkpost_link($line['id_us'].$id)).'">+</a>';
                            $sx .= '<li>'.$line['us_nome'].' ('.$line['us_email'].') '.$link.'</li>';
                        }
                    $sx .= '</ul>';
                    $sx .= '</div>';
                    $sx .= '</div>';
                }
            }                    
            
            return($sx);
        }        

    function groups($id='',$chk='') 
        {
            $CI = &get_instance();
            $sx = '
            <div class="row">
            <div class="'.bscol(12).'">
            <h1>Grupos de usuários</h1>';
            
            $this->check_sql_tables();
            $sql = "select * from users_group 
                        where gr_library = '".LIBRARY."' 
                        ORDER BY gr_name";
            $rlt = $CI->db->query($sql);
            $rlt = $rlt->result_array();

            if (count($rlt) == 0)
                {
                    $this->group_create("Administradores",LIBRARY,'#ADM');
                    $this->group_create("Gerentes",LIBRARY,'#GER');
                    redirect(base_url(PATH.'social/group'));
                }  else {
                    $sx .= '<ul>';
                    for ($r=0;$r < count($rlt);$r++)
                        {
                            $line = $rlt[$r];
                            $sx .= '<li class="big">'.$line['gr_name'].'</li>';

                            /* Mostra membros */
                            $sql = "select * from users_group_members
                                        INNER JOIN users ON grm_user = id_us
                                        where grm_group = ".$line['id_gr']."
                                        and grm_library = '".LIBRARY."'";
                            $rrr = $CI->db->query($sql);
                            $rrr = $rrr->result_array();
                            $sx .= '<div class="small">';
                            for ($y=0;$y < count($rrr);$y++)
                                {
                                    $sx .= $rrr[$y]['us_nome'].'. ';
                                }
                            $sx .= '</div>';

                            /* Novos membros */
                            if (perfil("#ADM#GES"))
                            {
                                $sx .= '<a class="small" href="'.base_url(PATH.'social/group_members/'.$line['id_gr']).'">'.msg('edit_member').'</a>';
                            } 

                        }
                    $sx .= '</ul>';
                }           
            
            for($r=0;$r < count($rlt);$r++)
                {

                }

            /* Novo grupo */
            if (perfil("#ADM#GES"))
            {
                $sx .= '<a class="small" href="'.base_url(PATH.'social/group_edit/0').'">'.msg('new_group').'</a>';
            }
            $sx .= '</div></div>';
            $data['content'] = $sx;
            $CI->load->view('content',$data);
            return("");
        }

    function group_create($name,$lb,$hash)
        {
            $CI = &get_instance();
            $sql = "select * from users_group
                        where gr_hash = '$hash'
                        and gr_library = '$lb' ";
            $rlt = $CI->db->query($sql);
            $t = $rlt->result_id->num_rows;
            if ($t==0)
                {
                    $sql = "insert into users_group
                            (gr_name, gr_library,gr_hash)
                            values
                            ('$name','$lb','$hash')";
                    $rlt = $CI->db->query($sql);
                }
        }

    function check_sql_tables()
        {
            $CI = &get_instance();
            $db = $CI->db->database;
            /************************************************* users_group */

            $sql = "
            SELECT COUNT(*) as total
            FROM information_schema.tables 
            WHERE table_schema = '$db' 
            AND table_name = 'users_group'
            ";
            $rlt = $CI->db->query($sql);
            $rlt = $rlt->result_array();
            
            if ($rlt[0]['total'] == 0)
                {
                    $sql = "CREATE TABLE users_group (
                    id_gr serial NOT NULL,
                    gr_name char(50) NOT NULL,
                    gr_hash char(4) NOT NULL,
                    gr_library char(4) NOT NULL,
                    gr_created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
                    ) ENGINE=InnoDB DEFAULT CHARSET=UTF8;";
                    $rlt = $CI->db->query($sql);
                }

            /************************************************* users_group */
            $sql = "
            SELECT COUNT(*) as total
            FROM information_schema.tables 
            WHERE table_schema = '$db' 
            AND table_name = 'users_group_members'
            ";
            $rlt = $CI->db->query($sql);
            $rlt = $rlt->result_array();
            
            if ($rlt[0]['total'] == 0)
                {
                    $sql = "CREATE TABLE users_group_members (
                    id_grm serial NOT NULL,
                    grm_group int(4) NOT NULL,
                    grm_user int(4) NOT NULL,
                    grm_library char(4) NOT NULL,
                    grm_created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
                    ) ENGINE=InnoDB DEFAULT CHARSET=UTF8;";
                    $rlt = $CI->db->query($sql);
                }
        }

    function email($act)
        {
            $sx = '';
            switch($act)
                {
                    case 'test_gmail':
                        $sx .= $this->email_gmail();
                    break;

                    default:
                        $sx = '<h1>Socials Menu - email</h1>';
                        $sx .= '<ul>';
                        $sx .= '<li><a href="' . base_url(PATH . 'social/email/test') . '">' . msg('email_test') . '</a></li>';
                        $sx .= '<li><a href="' . base_url(PATH . 'social/email/test_gmail') . '">' . msg('email_test_gmail') . '</a></li>';
                        $sx .= '</ul>';
                    break;
                }
            return($sx);
        }

    function email_gmail()
        {
            global $sender;
            $CI = &get_instance();
            $CI->load->library("email");
            $form = new form;
            $cp = array();
            $_POST['dd3'] = 'smtp.gmail.com';
            $_POST['dd4'] = '587';
            array_push($cp, array('$H8','','',false,false));
            array_push($cp, array('$S100','','e-mail',True,True));
            array_push($cp, array('$S100','','senha',True,True));
            array_push($cp, array('$S100','','smtp',True,True));
            array_push($cp, array('$S20','','post',True,True));
            array_push($cp, array('$HV','','smtp',True,True));
            array_push($cp, array('$M','','&nbsp;',False,True));
            array_push($cp, array('$S100','','enviar para e-mail',True,True));

            $sx = $form->editar($cp,'');

            if ($form->saved > 0)
                {
                $CI = &get_instance();
                $CI->load->helper("email");
                $sender = Array('smtp_protocol' => 'PHPMailer',
                        'smtp_host' => get("dd3"), 
                        'smtp_port' => get("dd4"), 
                        'smtp_user' => get("dd1"), 
                        'smtp_pass' => get("dd2"), 
                        'mailtype' => 'html', 
                        'charset' => 'iso-8859-1', 
                        'wordwrap' => TRUE); 
                $r = enviaremail(get("dd7"),'Teste - '.date("Y-m-d H:i:s"),'<b>HTML</b>teste');
                print_r($r);
                }
            return($sx);
        }
    
    function npass()
    {
        $CI = &get_instance();
        $email = get("dd0");
        $chk = get("chk");
        $chk2 = checkpost_link($email . $email);
        $chk3 = checkpost_link($email . date("Ymd"));
        
        if ((($chk != $chk2) and ($chk != $chk3)) and (!isset($_POST['dd1']))) {
            $data['content'] = 'Erro de Check';
            $CI->load->view('content', $data);
        } else {
            $dt = $this->le_email($email);
            if (count($dt) > 0) {
                $id = $dt['id_us'];
                $data['title'] = '';
                $tela = '<br><br><h1>' . msg('change_password') . '</h1>';
                $new = 1;
                // Novo registro
                $data['content'] = $tela . $this->change_password($id, $new);
                $CI->load->view('content', $data);
            } else {
                $data['content'] = 'Email não existe!';
                $CI->load->view('error', $data);
            }
        }
        return ('');
    }
    
    
    function logout()
    {
        /* Salva session */
        $this->security_logout();
        redirect(base_url(PATH));
    }

	function user_image($id)
		{
            if (is_array($id))
                {
                    $id = $id['id_us'];
                }
            
            $imgt = $this->user_picture_dir.'/user_'.strzero($id,7).'.jpg';
            if (file_exists($imgt))
                {
                    return($imgt);
                }
			$img = 'img/no_image.png';
			return($img);
		}    

	function user_tela($dt)
		{
			$sx = '';
			$img = $this->user_image($dt);		

			$sx .= '<div class="'.bscol(8).'">';
			$sx .= '<span class="small">'.msg('us_nome').'</span><br/>';
			$sx .= '<span class="large">'.$dt['us_nome'].'</span><br/>';

			$sx .= '<span class="small">'.msg('us_email').'</span><br/>';
			$sx .= '<span class="large">'.$dt['us_email'].'</span><br/>';

			$sx .= '</div>';

			$sx .= '<div class="'.bscol(4).' small">';
			$sx .= '<img src="'.base_url($img).'" class="img-fluid">';
			$sx .= '</div>';
			return($sx);
		}    
    
    function ac($id = '')
    {
        $this->load->model('users');
        
        $line = $this->users->le($id);
        /* Salva session */
        $ss_id = $line['id_us'];
        $ss_user = $line['us_nome'];
        $ss_email = $line['us_email'];
        $ss_image = $line['us_image'];
        $ss_perfil = $line['us_perfil'];
        $data = array('id' => $ss_id, 'user' => $ss_user, 'email' => $ss_email, 'image' => $ss_image, 'perfil' => $ss_perfil);
        $this->session->set_userdata($data);
        redirect(base_url('index.php/home'));
    }
    
    function perfil($id)
    {
        $CI = &get_instance();
        if (strlen($id) == 0) {
            if (isset($_SESSION['id'])) {
                $id = round($_SESSION['id']);
            } else {
                $id = 0;
            }
        }
        $dt = $this->le($id);
        if (count($dt) == 0) {
            echo "Erro na Identificação do Perfil " . $id;
            exit;
        }
        $sx = $this->show_perfil($dt);
        
        $cnt = '<ul>';
        $link = '<a href="' . base_url(PATH . 'social/user_password/' . $dt['id_us'] . '/' . md5($dt['us_login'])) . '">';
        $linka = '</a>';
        $cnt .= '<li>' . $link . msg('change_password') . $linka . '</li>';

        $link = '<a href="' . base_url(PATH . 'social/api_key/' . $dt['id_us'] . '/' . md5($dt['us_login'])) . '">';
        $cnt .= '<li>' . $link . msg('gerate_api_key') . $linka . '</li>';
        $cnt .= '</ul>';
        
        $cnt .= $this->show_attri($id);
        
        $sx = troca($sx, '<cnt/>', $cnt);
        
        $d['content'] = $sx;
        $CI->load->view('content', $d);
        return ($sx);
    }
    
    function menu_user()
    {        
        if (isset($_SESSION['user']) and (strlen($_SESSION['user']) > 0)) {
            $name = $_SESSION['user'];
            $sx = '
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> ' . $name . ' </a>
            
            ';
            $sx = '
            <li class="nav-item dropdown ml-auto">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> ' . $name . ' </a>        
            
            <div class="dropdown-menu ml-auto" aria-labelledby="navbarDropdownMenuLink" style="right: 0px;">
            <a class="dropdown-item" href="' . base_url(PATH . 'social/perfil') . '">' . msg('user_perfil') . '</a>
            <a class="dropdown-item" href="' . base_url(PATH . 'social/logout') . '">' . msg('user_logout') . '</a>
            </div>
            
            </li>       
            ';
        } else {
            $sx = '<A href="#" class="nav-link" data-toggle="modal" data-target="#exampleModalLong">' . msg('sign_in') . '</a>';
            $sx .= $this->button_login_modal();
        }
        return ($sx);
    }
    
    function button_login_modal()
    {
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
        <form method="post" action="' . base_url(PATH.'social/login_local') . '">
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
        //$sx = $CI->load->view('social/login/login.php', $data, true);
        return ($sx);
    }
    
    function login_local()
    {
        $dd1 = get('dd1') . get("user_login");
        $dd2 = get('dd2') . get("user_password") . get("password");
        $ok = 0;
        if ((strlen($dd1) > 0) and (strlen($dd2) > 0)) {
            $dd1 = troca($dd1, "'", '´');
            $dd2 = troca($dd2, "'", '´');
            $ok = $this->security_login($dd1, $dd2);
            if ($ok != 1) {
                redirect(base_url(PATH . 'social/login?err=login_error'));
            } else {
                redirect(base_url(PATH));
            }
            echo "OK = " . $ok;
            exit;
        }
        return ($ok);
    }
    
    function login($simple = 0)
    {
        $CI = &get_instance();
        //$this -> load -> view('auth_social/login_pre', null);
        if ($simple == 1) {
            $CI->load->view('social/login/login_simple', null);
        } else {
            $data['content'] = $this->view_login_signin();
            $CI -> load->view('content',$data);
        }
        
        //$this -> load -> view('auth_social/login_horizontal', null);
    }
    
    function view_login_signin($data=array())
    {
        $sx = '';
        $err = get("err");
        if (strlen($err) > 0)
        {
            $sx .= message(msg($err),3);
        }
        
        $sx .= '
        <!---- Social Login ---->
        <style>
        .box100 {
            border: 2px solid #cccccc;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        }
        .form_title {
            font-size: 300%;
            line-height: 100%;
        }
        </style>';

        $sx .= '
        <div class="container" style="margin-top: 100px;">
        <div class="row">
        <div class="col-md-2 col-lg-3 col-sm-1"></div>
        <div class="col-md-8 col-lg-6 col-sm-10 box100">
        <form method="post" action="'.base_url(PATH.'social/login_local').'">
        <span class="form_title"> '.LIBRARY_NAME.' </span>
        <br/>
        <br/>
        <h2 class="text-center">'.msg('SignIn').'</h2>
        
        <!--- email login social --->
        <div class="" data-validate = "Valid email is: a@b.c">
        <span>'.msg('e-mail').'</span>
        <input class="form-control" type="text" name="user_login">
        <span class="focus-input100" data-placeholder="Email"></span>
        </div>
        
        <!--- password login social --->
        <br/>
        <div class="" data-validate="Enter password">
        <span>'.msg('password').'</span>
        <input class="form-control" type="password" name="user_password">
        <span class="focus-input100" data-placeholder="Password"></span>
        </div>
        <br/>
        <div class="">
        <input type="submit" class="btn btn-primary" style="width: 100%;" value="'.msg('login').'">
        </div>
        <br/>';
        
        if (!isset($data['forgot']))
        {
        $sx .= '
        <!--- Forgot Password --->
        <div class="text-center p-t-115">
        <a class="txt2 text-dark" href="'.base_url(PATH.'social/forgot').'"> '.msg('Forgot Password?').' </a>
        </div>
        <br/>';
        }

        if (!isset($data['signup']))
        {
        $sx .= '
        <!--- Create a Passwrod --->
        <div class="text-center p-t-115">
        <span class="txt1">'.msg('Don’t have an account?').'</span>                
        <a class="txt2 text-dark" href="'.base_url(PATH.'social/signup').'"> '.msg('SignUp').' </a>
        </div>
        <br/>';
        }

        $sx .= '
        </form>
        </div>
        <div class="col-md-2 col-lg-3 col-sm-1"></div>
        </div>
        </div>
        ';
        
        /* Scripts */
        $sx .= "
        <script>
        (function($) {
            'use strict';                    
            /*==================================================================
            [ Focus input ]*/
            $('.input100').each(function() {
                $(this).on('blur', function() {
                    if ($(this).val().trim() != '') {
                        $(this).addClass('has-val');
                    } else {
                        $(this).removeClass('has-val');
                    }
                })
            })
            /*==================================================================
            [ Validate ]*/
            var input = $('.validate-input .input100');
            
            $('.validate-form').on('submit', function() {
                var check = true;
                
                for (var i = 0; i < input.length; i++) {
                    if (validate(input[i]) == false) {
                        showValidate(input[i]);
                        check = false;
                    }
                }                        
                return check;
            });
            
            $('.validate-form .input100').each(function() {
                $(this).focus(function() {
                    hideValidate(this);
                });
            });
            
            function validate(input) {
                if ($(input).attr('type') == 'email' || $(input).attr('name') == 'email') {
                    if ($(input).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/) == null) {
                        return false;
                    }
                } else {
                    if ($(input).val().trim() == '') {
                        return false;
                    }
                }
            }
            
            function showValidate(input) {
                var thisAlert = $(input).parent();                        
                $(thisAlert).addClass('alert-validate');
            }
            
            function hideValidate(input) {
                var thisAlert = $(input).parent();                        
                $(thisAlert).removeClass('alert-validate');
            }
            
            /*==================================================================
            [ Show pass ]*/
            var showPass = 0;
            $('.btn-show-pass').on('click', function() {
                if (showPass == 0) {
                    $(this).next('input').attr('type', 'text');
                    $(this).find('i').removeClass('zmdi-eye');
                    $(this).find('i').addClass('zmdi-eye-off');
                    showPass = 1;
                } else {
                    $(this).next('input').attr('type', 'password');
                    $(this).find('i').addClass('zmdi-eye');
                    $(this).find('i').removeClass('zmdi-eye-off');
                    showPass = 0;
                }
            });
        })(jQuery); 
        </script>        
        ";
        return($sx);
    }    
    
    function session($provider)
    {
        $CI = &get_instance();
        $this->load->helper('url_helper');
        //facebook
        if ($provider == 'facebook') {
            //$app_id = $this -> config -> item('fb_appid');
            $app_id = $this->face_id;
            //$app_secret = $this -> config -> item('fb_appsecret');
            $app_secret = $this->face_app;
            $provider = $this->oauth2->provider($provider, array('id' => $app_id, 'secret' => $app_secret,));
        }
        //google
        else if ($provider == 'google') {
            
            //$app_id = $this -> config -> item('googleplus_appid');
            $app_id = $this->google_key;
            
            //$app_secret = $this -> config -> item('googleplus_appsecret');
            $app_secret = $this->google_key_client;
            $provider = $this->oauth2->provider($provider, array('id' => $app_id, 'secret' => $app_secret,));
        }
        
        //foursquare
        else if ($provider == 'foursquare') {
            
            $app_id = $this->config->item('foursquare_appid');
            $app_secret = $this->config->item('foursquare_appsecret');
            $provider = $this->oauth2->provider($provider, array('id' => $app_id, 'secret' => $app_secret,));
        }
        if (!$this->input->get('code')) {
            // By sending no options it'll come back here
            $provider->authorize();
            redirect('social?erro=ERRO DE LOGIN');
        } else {
            // Howzit?
            try {
                $token = $provider->access($_GET['code']);
                $user = $provider->get_user_info($token);
                
                /* Ativa sessão ID */
                $ss_user = $user['name'];
                $ss_email = trim($user['email']);
                $ss_image = $user['image'];
                $ss_nome = $user['name'];
                $ss_link = $user['urls']['Facebook'];
                $ss_nivel = 0;
                
                $sql = "select * from users where us_email = '$ss_email' ";
                $query = $CI->db->query($sql);
                $query = $query->result_array();
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
                    $CI->db->query($sql);
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
                            $CI->db->query($sql);
                            
                            $c = 'us';
                            $c1 = 'id_' . $c;
                            $c2 = $c . '_codigo';
                            $c3 = 7;
                            $sql = "update users set us_codigo = lpad($c1,$c3,0) where $c2='' ";
                            $rlt = $CI->db->query($sql);
                            
                            $sql = "select * from users where us_email = '$ss_email' ";
                            $query = $CI->db->query($sql);
                            $query = $query->result_array();
                            $line = $query[0];
                            $id = $line['id_us'];
                        }
                        $ss_perfil = $line['us_perfil'];
                        
                        /* Salva session */
                        $data = array('id' => $id, 'user' => $ss_user, 'email' => $ss_email, 'image' => $ss_image, 'nivel' => $ss_nivel, 'perfil' => $ss_perfil);
                        $this->session->set_userdata($data);
                        
                        if ($this->uri->segment(3) == 'google') {
                            //Your code stuff here
                        } elseif ($this->uri->segment(3) == 'facebook') {
                            //your facebook stuff here
                            
                        } elseif ($this->uri->segment(3) == 'foursquare') {
                            // your code stuff here
                        }
                        
                        $this->session->set_flashdata('info', $message);
                        redirect('social?tabindex=s&status=sucess');
                    } catch (OAuth2_Exception $e) {
                        show_error('That didnt work: ' . $e);
                    }
                }
            }
            
            function cp($id)
            {
                $cp = array();
                array_push($cp, array('$H8', 'id_us', '', False, True));
                array_push($cp, array('$S80', 'us_nome', 'Nome', True, True));
                if ($id == 0) {
                    array_push($cp, array('$S80', 'us_email', 'login/email', True, True));
                    array_push($cp, array('$P20', '', 'Senha', True, True));
                    $pass = $this->password_cripto(get("dd3"), 'T');
                    array_push($cp, array('$HV', 'us_password', $pass, True, True));
                    array_push($cp, array('$HV', 'us_login', get("dd2"), True, True));
                }
                array_push($cp, array('$O 1:SIM&0:NÃO', 'us_ativo', 'Ativo', True, True));
                return ($cp);
            }
            
            function create_admin_user()
            {
                $dt = array();
                $dt['us_nome'] = 'Super User Admin';
                $dt['us_email'] = 'admin';
                $dt['us_password'] = md5('admin');
                $dt['us_autenticador'] = 'MD5';
                $this->insert_new_user($dt);
            }
            
            function row($id = '')
            {
                $CI = &get_instance();
                $form = new form;
                
                $form->fd = array('id_us', 'us_nome', 'us_login');
                $form->lb = array('id', msg('us_name'), msg('us_login'));
                $form->mk = array('', 'L', 'L', 'L');
                
                $form->tabela = $this->table;
                $form->see = true;
                $form->novo = true;
                $form->edit = true;
                
                $form->row_edit = base_url(PATH . 'social/user_edit');
                $form->row_view = base_url(PATH . 'social/perfil');
                $form->row = base_url(PATH . 'social/users');
                
                $sx = '<div class="container">';
                $sx .= '<div class="row">';
                $sx .= '<div class="col-12">';
                $sx .= row($form, $id);
                $sx .= '</div>';
                $sx .= '</div>';
                $sx .= '</div>';
                $data['content'] = $sx;
                $CI->load->view('content', $data);
                return ("");
            }
            
            function editar($id, $chk)
            {
                $CI = &get_instance();
                $form = new form;
                $form->id = $id;
                $cp = $this->cp($id);
                $data['title'] = '';
                $data['content'] = $form->editar($cp, $this->table);
                $CI->load->view('content', $data);
                
                if ($form->saved > 0) {
                    redirect(base_url(PATH . 'social/users'));
                }
                return ($form->saved);
            }
            
            function insert_new_user($data)
            {
                $CI = &get_instance();
                $email = $data['us_email'];
                $nome = $data['us_nome'];
                $senha = $data['us_password'];
                $auth = $data['us_autenticador'];
                $inst = '';
                if (isset($data['us_institution'])) {
                    $inst = $data['us_institution'];
                }
                
                $sql = "select * from " . $this->table . " where us_email = '$email' ";
                $rlt = $CI->db->query($sql);
                $rlt = $rlt->result_array();
                if (count($rlt) == 0) {
                    $sql = "insert into " . $this->table . " 
                    (us_nome, us_email, us_password, us_ativo, us_autenticador,
                    us_perfil, us_perfil_check, us_institution,
                    us_login, us_badge)
                    values
                    ('$nome','$email','$senha','1', '$auth',
                    '','','$inst',
                    '$email','')
                    ";
                    $CI->db->query($sql);
                    $this->updatex();
                    $this->update_perfil_check($data);
                    return (1);
                } else {
                    return (-1);
                }
            }
            
            function resend()
            {
                $email = get("dd0");
                $chk = get("chk");
                $chk2 = md5($email . date("Ymd") . $email);
                if ($chk2 == $chk) {
                } else {
                    $data['content'] = 'Erro de checagem dos dados!';
                    $this->load->view('error', $data);
                }
            }
            
            function le($id, $fld = 'id')
            {
                $CI = &get_instance();
                $sql = "select * from " . $this->table;
                switch ($fld) {
                    case 'id':
                        $sql .= ' where id_us = ' . round($id);
                    break;
                    case 'login':
                        $sql .= " where us_email = '$id' ";
                    break;
                    default:
                    $sql .= ' where id_us = ' . round($id);
                break;
            }
            $rlt = $CI->db->query($sql);
            $rlt = $rlt->result_array();
            if (count($rlt) == 0) {
                return (array());
            } else {
                return ($rlt[0]);
            }
        }
        
        function updatex()
        {
            $CI = &get_instance();
            $sql = "update " . $this->table . " set us_badge = lpad(id_us,5,0) where us_badge = '' or us_badge is null ";
            $CI->db->query($sql);
        }
        
        function update_perfil_check($data)
        {
            $CI = &get_instance();
            if (isset($data['us_email'])) {
                $usr = $this->le($data['us_email'], 'login');
                $id = $usr['id_us'];
                $pass = $usr['us_password'];
                $perfil = $usr['us_perfil'];
                $check = md5($id . $perfil);
                $sql = "update " . $this->table . " set us_perfil_check = '$check' where id_us = $id ";
                $rlt = $CI->db->query($sql);
                return ('1');
            }
            if (isset($data['id_us'])) {
                $usr = $this->le($data['id_us'], 'id');
                $id = $usr['id_us'];
                $pass = $usr['us_password'];
                $perfil = $usr['us_perfil'];
                $check = md5($id . $perfil);
                $sql = "update  " . $this->table . " set us_perfil_check = '$check' where id_us = $id ";
                $rlt = $CI->db->query($sql);
                return ('1');
            }
        }
        
        /****************** Security login ****************/
        function security()
        {
            $ok = 0;
            if (isset($_SESSION['id'])) {
                $id = round($_SESSION['id']);
                if ($id > 0) {
                    return ('');
                }
            }
            
            redirect(base_url('index.php/social/login'));
        }
        
        function security_logout()
        {
            $CI = &get_instance();
            $data = array('id' => '', 'user' => '', 'email' => '', 'image' => '', 'perfil' => '');
            $CI->session->set_userdata($data);
            return ('');
        }
        
        function xxx_action($path, $d1, $d2)
        {
            $CI = &get_instance();
            $path = troca($path, 'form', 'signin');
            
            switch ($path) {
                case 'login':
                    $user = get("user_login");
                    $pass = get("user_password");
                    $ok = $this->security_login($user, $pass);
                    if ($ok != 1) {
                        redirect(base_url(PATH . 'social/form'));
                    } else {
                        redirect(base_url(PATH));
                    }
                break;
                case 'logout':
                    $this->logout();
                    redirect(base_url(PATH));
                    default:
                    echo 'Método não implementado "' . $path . '"';
                    exit;
                }
            }
            
            function security_login($login = '', $pass = '')
            {
                $CI = &get_instance();
                /****************************** FORCA LOGIN **************/
                $forced = 0;
                
                $sql = "select * from " . $this->table . " 
                        where (us_email = '$login' OR us_login = '$login')
                        OR (1=$forced) 
                        limit 1";
                $rlt = $CI->db->query($sql);
                $rlt = $rlt->result_array();
                
                if (count($rlt) > 0) {
                    $line = $rlt[0];
                    
                    $dd2 = $this->password_cripto($pass, $line['us_autenticador']);
                    $dd3 = trim($line['us_password']);
                    
                    if (($dd2 == $dd3) or ($pass == $dd3) or ($forced == 1)) {
                        /* Salva session */
                        
                        $ss_id = $line['id_us'];
                        $ss_user = $line['us_nome'];
                        $ss_email = $line['us_email'];
                        $ss_image = $line['us_image'];
                        $ss_perfil = $this->perfil_load($ss_id,$line);
                        $ss_nivel = $line['us_nivel'];
                        $data = array('id' => $ss_id, 'user' => $ss_user, 'email' => $ss_email, 'image' => $ss_image, 'perfil' => $ss_perfil, 'nivel' => $ss_nivel);
                        $CI->session->set_userdata($data);
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

            function perfil_load($id,$line)
                {
                    $CI = &get_instance();
                    $this->check_sql_tables();
                    $perfil = $line['us_perfil'];
                    $sql = "select * from users_group_members
                            INNER JOIN users_group 
                            where grm_user = $id 
                            and grm_library = '".LIBRARY."'";
                    $rlt = $CI->db->query($sql);
                    $rlt = $rlt->result_array();
                    for ($r=0;$r < count($rlt);$r++)
                        {
                            $line = $rlt[$r];
                            $p = $line['gr_hash'];
                            if (strpos(' '.$perfil,$p) ==0)
                                {
                                    $perfil .= $p;
                                }
                        }
                    return($perfil);
                }
            
            function my_account($id)
            {
                $CI = &get_instance();
                $this->load->model('user_drh');
                
                $data1 = $this->le($id);
                $data2 = $this->user_drh->le($id);
                $data = array_merge($data1, $data2);
                
                $tela = $this->load->view('auth_social/myaccount', $data, true);
                return ($tela);
            }
            
            function password_cripto($pass, $tipo)
            {
                $pass = trim($pass);
                switch ($tipo) {
                    case 'TXT':
                        $dd2 = trim($pass);
                    break;
                    
                    default:
                    $dd2 = md5($pass);
                break;
            }
            return ($dd2);
        }
        
        function change_password($id, $new = 0)
        {
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
            $tela .= $form->editar($cp, '');
            
            /* REGRAS DE VALIDACAO */
            $data = $this->le($id);
            $pass = get("dd1");
            $dd3 = $data['us_password'];
            $p1 = get("dd2");
            $p2 = get("dd3");
            $dd2 = get("dd2");
            
            if ($form->saved > 0) {
                if (($dd2 == $dd2) and (strlen($dd2) > 0)) {
                    if (($p1 == $p2) or ($new == 1)) {
                        $sql = "update " . $this->table . " set us_password = '" . md5($p1) . "', us_autenticador = 'MD5' where id_us = " . $id;
                        $CI->db->query($sql);
                        redirect(base_url(PATH . 'social/perfil/' . $id));
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
            $CI->load->view('content', $d);
            return ('');
        }
        
        function user_id()
        {
            if (!isset($_SESSION['id'])) {
                return (0);
            }
            
            $us = round($_SESSION['id']);
            return ($us);
        }
        
        function user_email_send($para, $nome, $code)
        {
            $CI = &get_instance();
            $anexos = array();
            $texto = $this->email_cab();
            $de = 0;
            switch ($code) {
                case 'SIGNUP':
                    $link = base_url(PATH . 'social/npass/?dd0=' . $para . '&chk=' . checkpost_link($para . $para));
                    $assunto = utf8_decode('Cadastro de novo usuários - ' . LIBRARY_NAME);
                    $texto .= utf8_decode('<p>' . msg('Dear') . ' <b>' . $nome . ',</b></p>');
                    $texto .= utf8_decode('<p>Para ativar seu cadastro é necessário clicar no link abaixo:');
                    $texto .= '<br><br>';
                    $texto .= '<a href="' . $link . '" target="_new">' . $link . '</a></p>';
                    $de = 1;
                break;
                case 'PASSWORD':
                    $this->le_user_id($para);
                    $link = base_url(PATH . 'social/user_password_new/?dd0=' . $para . '&chk=' . checkpost_link($para . date("Ymd")));
                    $assunto = msg('Cadastro de nova senha') . ' - ' . LIBRARY_NAME;
                    $texto .= '<p>' . msg('Dear') . ' ' . $this->line['us_nome'] . '</p>';
                    $texto .= '<p>' . utf8_decode(msg('change_new_password')) . '</p>';
                    $texto .= '<br><br>';
                    $texto .= '<a href="' . $link . '" target="_new">' . $link . '</a>';
                    $de = 1;
                break;
                
                case 'FORGOT':
                    $data = $this->le_email($para);
                    $link = base_url(PATH . 'social/user_password_new/?dd0=' . $para . '&chk=' . checkpost_link($para . date("Ymd")));
                    $assunto = msg('Cadastro de nova senha') . ' - SignIn';
                    $texto .= '<p>' . msg('Dear') . ' ' . $data['us_nome'] . '</p>';
                    $texto .= '<p>' . utf8_decode(msg('change_new_password')) . '</p>';
                    $texto .= '<br><br>';
                    $texto .= '<a href="' . $link . '" target="_new">' . $link . '</a>';
                    $de = 1;
                break;
                
                default:
                $assunto = 'Enviado de e-mail';
                $texto .= ' Vinculo não informado ' . $code;
                $de = 1;
            break;
        }
        $texto .= $this->email_foot();
        if ($de > 0) {
            $CI->load->helper('email');
            enviaremail($para, $assunto, $texto, $de);
        } else {
            echo 'e-mail não enviado - ' . $code;
        }
    }
    
    /***** EMAIL */
    function email_cab()
    {
        $sx = '<table width="600" align="center"><tr><td>';
        $sx .= '<font style="font-family: Tahoma, Verdana, Arial; font-size: 14px;">' . cr();
        $sx .= '<font style="font-size: 24px; color: #1D0E9B; font-family: Tahoma, Arial">' . LIBRARY_NAME . '</font>' . cr();
        $sx .= '<br>';
        $sx .= utf8_decode('<font style="color: #1D0E9B; font-size: 12px;">Base de dados em Ciência da Informação</font>') . cr();
        $sx .= '<hr>';
        return ($sx);
    }
    
    function email_foot()
    {
        $sx = '';
        $sx .= '<hr>';
        $sx .= '</td></tr></table>';
        return ($sx);
    }
    
    function signup()
    {
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
            $rs = $this->insert_new_user($dt);
            //$rs = 1;
            $data['erro'] = $rs;
            
            if ($rs == 1) {
                $code = 'SIGNUP';
                $this->user_email_send($email, $name, $code);
                $CI->load->view('social/login/login_signup_sucess', $dt);
                return ("");
            }
        }
        
        $CI->load->view('social/login/login_signup', $data);
    }
    
    function forgot()
    {
        $CI = &get_instance();
        $data = array();
        $email = get("user_login");
        
        if (strlen($email) > 0) {
            $dt = array();
            $rs = 1;
            if ($rs == 1) {
                $dt = $this->le_email($email);
                $code = 'FORGOT';
                if (count($dt) > 0) {
                    $this->user_email_send($email, '', $code);
                    $CI->load->view('social/login/login_signup_sucess', $dt);
                    return ("");
                } else {
                    $sx = 'Email nao localizado';
                    return ("");
                }
            }
        }
        
        $CI->load->view('social/login/login_forgot', $data);
    }
    
    
    function le_email($e)
    {
        $CI = &get_instance();
        $sql = "select * from users where us_email = '$e'";
        $rlt = $CI->db->query($sql);
        $rlt = $rlt->result_array();
        if (count($rlt) > 0) {
            $line = $rlt[0];
            return ($line);
        } else {
            return (array());
        }
    }
    function token($t)
    {
        $CI = &get_instance();
        $t = troca($t, "'", '´');
        $sql = "select * from users where us_password = '$t' ";
        $rlt = $CI->db->query($sql);
        $rlt = $rlt->result_array();
        if (count($rlt) == 1) {
            $line = $rlt[0];
            return ($line);
        } else {
            return (array());
        }
    }
    
    function attributes($id, $ack)
    {
        $CI = &get_instance();
        if (perfil("#ADM")) {
            $cmd = substr($ack, 0, 1);
            switch ($cmd) {
                case 'R':
                    $sql = "delete from users_perfil_attrib where id_pa = " . sonumero($ack);
                    echo $sql;
                break;
                
                case 'A':
                    $idf = sonumero($ack);
                    $sql = "insert into users_perfil_attrib ( pa_user, pa_perfil ) values ( $id,$ipf )";
                    echo $sql;
                break;
            }
        }
    }
    
    function show_attri($id)
    {
        $CI = &get_instance();
        $sql = "select * from users_group_members 
                    inner join users_group ON id_gr = grm_group
                    where grm_user = " . $id." and grm_library = '".LIBRARY."'" ;
        $rlt = $CI->db->query($sql);
        $rlt = $rlt->result_array();
        
        $sx = '';
        $sx .= '<table class="table">';
        $sx .= '<tr>';
        $sx .= '<th>Classe</th>';
        $sx .= '<th>Grupo</th>';
        $sx .= '<th>Atribuído</th>';
        $sx .= '</tr>';
        if (isset($_SESSION['nivel']))
        {
            $nivel = $_SESSION['nivel'];
        } else {
            $_SESSION['nivel'] = 0;
            $nivel = 0;
        }

        for ($r = 0; $r < count($rlt); $r++) {
            $line = $rlt[$r];
            $sx .= '<tr>';
            $sx .= '<td>' . $line['gr_hash'] . '</td>';
            $sx .= '<td>' . $line['gr_name'] . '</td>';
            $sx .= '<td>' . stodbr($line['gr_created']) . '</td>';           
            $sx .= '</tr>';
        }
        $sx .= '</table>';

        if (isset($_SESSION['perfil']))
        {
            $sx .= 'SESSÃO: '.$_SESSION['perfil'];
        }
        
        return ($sx);
    }
    function show_perfil($dt)
    {
        $CI = &get_instance();
        if (!is_array($dt)) {
            $dt = $this->le($dt);
        }
        $sx = '<div class="row">';
        $sx .= '<div class="col-2"></div>';
        $sx .= '<div class="col-8">';
        $sx .= '<h2>' . $dt['us_nome'] . '</h2>';
        $sx .= '<h6>' . msg('login') . ': <b>' . $dt['us_login'] . '</b></h6>';
        $sx .= '</div>';
        
        if (strlen($dt['us_perfil_check']) == 0) {
            $this->update_perfil_check($dt);
        }
        
        $sx .= '<div class="col-2">';
        if ($dt['us_ativo'] == 1) {
            $sx .= '<span class="btn btn-success fluid">' . msg('active') . '</span>';
        }
        $sx .= '</div>';
        /***************** */
        $sx .= '<div class="'.bscol(2).'"></div>';
        $sx .= '<div class="'.bscol(10).'">';
        $sx .= 'API Access: <tt><span style="color: blue; weigth: bold;">' . md5($dt['us_perfil_check']) . '</span></tt>';
        $sx .= '</div>';

        $sx .= '<div class="'.bscol(2).'"></div>';
        $sx .= '<div class="'.bscol(10).'"><cnt/></div>';
        
        $sx .= '</div>';
        return ($sx);
    }
}
