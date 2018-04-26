<?php
// This file is part of the Brapci Software.
//
// Copyright 2015, UFPR. All rights reserved. You can redistribute it and/or modify
// Brapci under the terms of the Brapci License as published by UFPR, which
// restricts commercial use of the Software.
//
// Brapci is distributed in the hope that it will be useful, but WITHOUT ANY
// WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
// PARTICULAR PURPOSE. See the ProEthos License for more details.
//
// You should have received a copy of the Brapci License along with the Brapci
// Software. If not, see
// https://github.com/ReneFGJ/Brapci/tree/master//LICENSE.txt
/* @author: Rene Faustino Gabriel Junior <renefgj@gmail.com>
 * @date: 2015-12-01
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

class socials extends CI_Model {

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

	function __construct() {
		global $db_public;

		$db_public = 'brapci_publico.';
		parent::__construct();

		$this -> lang -> load("app", "portuguese");
		$this -> load -> library('form_validation');
		$this -> load -> database();
		$this -> load -> helper('form');
		$this -> load -> helper('form_sisdoc');
		$this -> load -> helper('url');
		$this -> load -> library('session');
		$this -> load -> library('Oauth2');
		date_default_timezone_set('America/Sao_Paulo');
	}

	function createDB() {
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
		$this -> db -> query($sql);

		/* insert Super User **********/
		$sql = "INSERT INTO users (id_us, us_nome, us_email, us_cidade, us_pais, us_codigo, us_link, us_ativo, us_nivel, us_image, us_genero, us_verificado, us_autenticador, us_cadastro, us_revisoes, us_colaboracoes, us_acessos, us_pesquisa, us_erros, us_outros, us_last, us_perfil, us_login,us_password) VALUES
                    (1, 'Administrador', 'admin', '', '', '0000001', '', '1', '9', '', 'M', '1', '0', 20140706, 0, 0, 400, 0, 0, 0, 20170715, '#ADM', 'admin','21232f297a57a5a743894a0e4a801fc30'); ";
		$this -> db -> query($sql);
	}

	function logout() {
		/* Salva session */
		$this -> security_logout();
		redirect(base_url('index.php/'));
	}

	function update() {
		$sql = "ALTER TABLE users ADD us_password CHAR(20) NOT NULL AFTER `us_email`;";
		$this -> db -> query($sql);

		$sql = "update users set us_password = '0c499ec0eb533670fff82c60cdf7b049', us_perfil = '#ADM#BIB' where us_email = 'renefgj@gmail.com' ";
		$this -> db -> query($sql);

		redirect(base_url('index.php/social/login'));
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

	function menu_user() {
		if (isset($_SESSION['user']) and (strlen($_SESSION['user']) > 0)) {
			$name = $_SESSION['user'];
			$sx = '
                <a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> ' . $name . ' </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="' . base_url('index.php/thesa/social/perfil') . '">' . msg('user_perfil') . '</a>
                    <a class="dropdown-item" href="' . base_url('index.php/thesa/social/logout') . '">' . msg('user_logout') . '</a>
                </div>                
                ';
		} else {
			$sx = '<A href="#" class="nav-link" data-toggle="modal" data-target="#exampleModalLong">' . msg('sign_in') . '</a>';
			$sx .= $this -> button_login_modal();
		}
		return ($sx);
	}

	function button_login_modal() {
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
                        <form method="post" action="' . base_url('index.php/thesa/login') . '">
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
		$sx = $this -> load -> view('thesa/login/login.php', $data, true);
		return ($sx);
	}

	function login_local() {
		$dd1 = get('dd1') . get("user_login");
		$dd2 = get('dd2') . get("user_password");
		$ok = 0;

		if ((strlen($dd1) > 0) and (strlen($dd2) > 0)) {
			$dd1 = troca($dd1, "'", '´');
			$dd2 = troca($dd2, "'", '´');
			$ok = $this -> security_login($dd1, $dd2);
		}
		return ($ok);
	}

	function login() {
		//$this -> load -> view('auth_social/login_pre', null);
		$this -> load -> view('thesa/login/login_signin', null);
		//$this -> load -> view('auth_social/login_horizontal', null);
	}

	public function session($provider) {

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
				$query = $this -> db -> query($sql);
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
					$this -> db -> query($sql);
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
					$rlt = $this -> db -> query($sql);

					$sql = "select * from users where us_email = '$ss_email' ";
					$query = $this -> db -> query($sql);
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
		}
		array_push($cp, array('$HV', 'us_password', md5(get("dd3")), True, True));
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

		$form -> row_edit = base_url('index.php/admin/user_edit');
		$form -> row_view = base_url('index.php/admin/user');
		$form -> row = base_url('index.php/admin/users');

		return (row($form, $id));
	}

	function editar($id, $chk) {
		$form = new form;
		$form -> id = $id;
		$cp = $this -> cp($id);
		$data['title'] = '';
		$data['content'] = $form -> editar($cp, $this -> table);
		$this -> load -> view('content', $data);
		return ($form -> saved);
	}

	function insert_new_user($data) {
		$email = $data['us_email'];
		$nome = $data['us_nome'];
		$senha = $data['us_password'];
		$auth = $data['us_autenticador'];
        $inst = '';
        if (isset($data['us_institution']))
            {
                $inst = $data['us_institution'];        
            }
        

		$sql = "select * from " . $this -> table . " where us_email = '$email' ";
		$rlt = $this -> db -> query($sql);
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
			$this -> db -> query($sql);
			$this -> updatex();
			$this -> update_perfil_check($data);
			return(1);
		} else {
		    return(-1);
		}
	}

    function resend()
        {
            $email = get("dd0");
            $chk = get("chk");
            $chk2 = md5($email.date("Ymd").$email);
            if ($chk2 == $chk)
                {
                    
                } else {
                    $data['content'] = 'Erro de checagem dos dados!';
                    $this->load->view('error',$data);                                   
                }
        }

	function le($id, $fld = 'id') {
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
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) == 0) {
			return ( array());
		} else {
			return ($rlt[0]);
		}
	}

	function updatex() {
		$sql = "update " . $this -> table . " set us_badge = lpad(id_us,5,0) where us_badge = '' or us_badge is null ";
		$this -> db -> query($sql);
	}

	function update_perfil_check($data) {
		if (isset($data['us_email'])) {
			$usr = $this -> le($data['us_email'], 'login');
			$id = $usr['id_us'];
			$pass = $usr['us_password'];
			$perfil = $usr['us_perfil'];
			$check = md5($id . $perfil);
			$sql = "update " . $this -> table . " set us_perfil_check = '$check' where id_us = $id ";
			$rlt = $this -> db -> query($sql);
			return ('1');
		}
		if (isset($data['id_us'])) {
			$usr = $this -> le($data['id_us'], 'id');
			$id = $usr['id_us'];
			$pass = $usr['us_password'];
			$perfil = $usr['us_perfil'];
			$check = md5($id . $perfil);
			$sql = "update  " . $this -> table . " set us_perfil_check = '$check' where id_us = $id ";
			$rlt = $this -> db -> query($sql);
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
		$data = array('id' => '', 'user' => '', 'email' => '', 'image' => '', 'perfil' => '');
		$this -> session -> set_userdata($data);
		return('');
	}

	function action($path, $d1, $d2) {
		switch ($path) {
			case 'login' :
				//$this->createDB();
				$user = get("user_login");
				$pass = get("user_password");
				$ok = $this -> security_login($user, $pass);
				if ($ok != 1) {
					redirect(base_url('index.php/main/social/form'));
				} else {
					redirect(base_url('index.php/main'));
				}
				break;
			case 'logout' :
				$this -> logout();
				redirect(base_url('index.php/main'));
			default :
				echo 'Método não implementado';
				exit ;
		}
	}

	function security_login($login = '', $pass = '') {
		$sql = "select * from " . $this -> table . " where us_email = '$login' OR us_login = '$login' ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();

		if (count($rlt) > 0) {
			$line = $rlt[0];

			$dd2 = $this -> password_cripto($pass, $line['us_autenticador']);
			$dd3 = trim($line['us_password']);
			if (($dd2 == $dd3) or ($pass == $dd3)) {
				/* Salva session */
				$ss_id = $line['id_us'];
				$ss_user = $line['us_nome'];
				$ss_email = $line['us_email'];
				$ss_image = $line['us_image'];
				$ss_perfil = $line['us_perfil'];
				$data = array('id' => $ss_id, 'user' => $ss_user, 'email' => $ss_email, 'image' => $ss_image, 'perfil' => $ss_perfil);
				$this -> session -> set_userdata($data);
				return (1);
			} else {
				return (0);
			}
		} else {
			return (-1);
		}
	}

	function my_account($id) {
		$this -> load -> model('user_drh');

		$data1 = $this -> le($id);
		$data2 = $this -> user_drh -> le($id);
		$data = array_merge($data1, $data2);

		$tela = $this -> load -> view('auth_social/myaccount', $data, true);
		return ($tela);
	}

	function password_cripto($pass, $tipo) {
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

	function change_password($id) {
		$form = new form;
		$cp = array();
		array_push($cp, array('$H8', '', '', false, false));
		array_push($cp, array('$P20', '', 'Senha atual', True, True));
		array_push($cp, array('$P20', '', 'Nova senha', True, True));
		array_push($cp, array('$P20', '', 'Confirme nova senha', True, True));
		array_push($cp, array('$B', '', 'Alterar senha', True, True));

		$tela = $form -> editar($cp, '');

		/* REGRAS DE VALIDACAO */
		$data = $this -> le($id);
		$pass = get("dd1");
		$dd3 = $data['us_password'];
		$p1 = get("dd2");
		$p2 = get("dd3");

		$dd2 = $this -> password_cripto($pass, $data['us_autenticador']);

		if ($dd2 == $dd3) {
			if ($p1 == $p2) {
				$sql = "update " . $this -> table . " set us_password = '" . md5($p1) . "', us_autenticador = 'MD5' where id_us = " . $id;
				$this -> db -> query($sql);
				redirect(base_url('index.php/home'));
			} else {
				$tela .= '<div class="alert">Senhas não conferem</div>';
			}
		} else {
			$tela .= '<div class="alert">Senhas atual não confere!</div>';
		}

		return ($tela);
	}

	function user_id() {
		if (!isset($_SESSION['id'])) {
			return(0);
		}

		$us = round($_SESSION['id']);
		return($us);

	}

    function user_email_send($para, $nome, $code) {
        $anexos = array();
        $texto = $this -> email_cab();
        $de = 0;
        switch($code) {
            case 'SIGNUP' :
                $link = base_url('index.php/thesa/social/npass/?dd0=' . $para . '&chk=' . checkpost_link($para . $para));
                $assunto = 'Cadastro de novo usuários - Thesa';
                $texto .= '<p>' . msg('Dear') . ' <b>' . $nome . ',</b></p>';
                $texto .= '<p>Para ativar seu cadastro é necessário clicar no link abaixo:';
                $texto .= '<br><br>';
                $texto .= '<a href="' . $link . '" target="_new">' . $link . '</a></p>';
                $de = 1;
                break;
            case 'PASSWORD' :
                $this -> le_user_id($para);
                $link = base_url('index.php/thesa/user_password_new/?dd0=' . $para . '&chk=' . checkpost_link($para . date("Ymd")));
                $assunto = msg('Cadastro de novo senha') . ' - Thesa';
                $texto .= '<p>' . msg('Dear') . ' ' . $this -> line['us_nome'] . '</p>';
                $texto .= '<p>' . msg('change_new_password') . '</p>';
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
            enviaremail($para, $assunto, $texto, $de);
        } else {
            echo 'e-mail não enviado - ' . $code;
        }
    }
    /***** EMAIL */
    function email_cab() {
        $sx = '<table width="600" align="center"><tr><td>';
        $sx .= '<font style="font-family: Tahoma, Verdana, Arial; font-size: 14px;">' . cr();
        $sx .= '<font color="blue" style="font-size: 24px;">THESA</font>' . cr();
        $sx .= '<br>';
        $sx .= '<font color="blue" style="font-size: 12px;"><i>Semantic Thesaurus</i></font>' . cr();
        $sx .= '<hr>';
        return ($sx);
    }

    function email_foot() {
        $sx = '';
        $sx .= '<hr>';
        $sx .= '</td></tr></table>';
        return ($sx);
    }
    function signup()
        {
            $data = array();
            $name = get("fullName");
            $email = get("email");
            $inst = get("Institution");
            
            if ((strlen($name) > 0) and (strlen($email)))
                {
                    $dt = array();
                    $dt['us_nome'] = $name;
                    $dt['us_email'] = $email;
                    $dt['us_institution'] = $inst;
                    $dt['us_password'] = md5(date("YmdHis"));
                    $dt['us_autenticador'] = 'MD5';
                    $rs = $this -> insert_new_user($dt);
                    //$rs = 1;
                    $data['erro'] = $rs;
                    
                    if ($rs == 1)
                        {
                            $code = 'SIGNUP';
                            $this-> user_email_send($email, $name, $code);
                            $this -> load -> view('thesa/login/login_signup_sucess', $dt);
                            return("");   
                        }  
                }
            
            $this -> load -> view('thesa/login/login_signup', $data);
        }
    function le_email($e)
        {
            $sql = "select * from users where us_email = '$e'";
            $rlt = $this->db->query($sql);
            $rlt = $rlt->result_array();
            if (count($rlt) > 0)
                {
                    $line = $rlt[0];
                    return($line);
                } else {
                    return(array());
                }
        }
}
