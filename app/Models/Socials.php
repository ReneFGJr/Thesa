<?php
# version: 0.21.07.30

namespace App\Models;

use CodeIgniter\Model;
use \app\Model\MainModel;

class Socials extends Model
{
	protected $DBGroup              = 'default';
	var $table                		= 'users2';
	protected $primaryKey           = 'id_us';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        =
	[
		'id_us', 'us_nome', 'us_email',
		'us_image', 'us_genero', 'us_verificado',
		'us_login', 'us_password', 'us_password_method',
		'us_oauth2', 'us_lastaccess'
	];

	protected $typeFields        = [
		'hi',
		'st100*',
		'st100*',
		'hi', 'hi', 'hi',
		'st50', 'st50', 'hi',
		'hi', 'up'
	];

	// Dates
	protected $useTimestamps        = false;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';
	protected $deletedField         = 'deleted_at';

	// Validation
	protected $validationRules      = [];
	protected $validationMessages   = [];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks       = true;
	protected $beforeInsert         = [];
	protected $afterInsert          = [];
	protected $beforeUpdate         = [];
	protected $afterUpdate          = [];
	protected $beforeFind           = [];
	protected $afterFind            = [];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];

	function user()
	{
		$sx = '';
		if (isset($_SESSION['user']['name'])) {
			$user = $_SESSION['user']['name'];
			$sx = '
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						' . $user . '
						</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdown">
						<a class="dropdown-item" href="' . PATH . MODULE . 'social/perfil' . '">Perfil</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="' . PATH . MODULE . 'social/logout' . '">Logout</a>
						</div>
					</li>				
				';
		}
		return ($sx);
	}

	function index($cmd = '', $id = '', $dt = '', $cab = '')
	{
		$sx = '';
		if (strlen($cmd) == 0) {
			$cmd = get("cmd");
		}

		switch ($cmd) {
			case 'test':
				if ($_SERVER['CI_ENVIRONMENT'] == 'development') {
					$_SESSION['id'] = 99999999;
					$_SESSION['user'] = 'social_teste';
					$_SESSION['email'] = 'Usuário Teste';
					echo metarefresh(PATH);
					exit;
				}
				$sx = bsmessage('Usuário não pode ser ativado no ambiente de produção');
				break;
			case 'login':
				$sx = $cab;
				$sx .= $this->login();
				break;
			case 'ajax':
				$sx = $this->ajax($id);
				break;
			case 'signin':
				$sx = $this->ajax($cmd);
				break;
			case 'signup':
				$sx = $this->ajax($cmd);
				break;
			case 'perfil':
				$sx .= $this->perfil();
				break;
			case 'profile':
				$sx .= $cab;
				$sx .= $this->perfil();
				break;
			case 'view':
				$sx .= h("Usuários - View", 1);
				$this->id = $id;
				$dt =
					[
						'services' => $this->paginate(3),
						'pages' => $this->pager
					];
				$sx .= tableview($this, $dt);
				break;
			case 'edit':
				$sx .= bs(12);
				$sx .= h("Users - Editar", 1);
				$this->id = $id;
				$sx .= form($this);
				$sx .= bsdivclose(3);
				break;

			case 'delete':
				$sx .= h("Serviços - Excluir", 1);
				$this->Social->id = $id;
				$sx .= form_del($this);
				break;
			case 'logout':
				$sx = $this->logout();
				break;
			default:
				$sx = $cab;
				$st =  h(lang('Social'), 1);
				if ($cmd == '') {
					$st .= h('Service not informed', 5);
				} else {
					$st .= h('Service not found - [' . $cmd . ']', 5);
				}
				$st .= anchor(PATH, "Acesso Negado (Page)", ["class" => "btn btn-outline-primary"]);
				$sx .= bs(bsc($st, 12));
				break;
		}
		return $sx;
	}

	function ajax($cmd)
	{
		$rsp = array();
		$cmd = get("cmd");

		$rsp['status'] = '9';
		$rsp['message'] = 'service not found';
		switch ($cmd) {
			case 'test':
				$rsp['status'] = 1;
				$rsp['message'] = 'Teste OK';
				return json_encode($rsp);
				break;
			case 'signin':
				$rsp = $this->signin();
				return $rsp;
				break;
			case 'signup':
				$rsp = $this->signup();
				return $rsp;
				break;
			default:
				$sx = 'Command not found - ' . $cmd;
				$sx .= '<span class="singin" onclick="showLogin()">' . lang('social.return') . '</span>';
				return $sx;
				break;
		}
	}

	function perfil()
	{
		$rsp = 0;
		if (isset($_SESSION['id'])) {
			$id = round($_SESSION['id']);
			if ($id > 0) {
				$dt = $this->Find($id);
				$rsp = view('Pages/profile.php', $dt);
			} else {
				$rsp = metarefresh(base_url());
			}
		}
		return $rsp;
	}

	function signin()
	{
		$sx = '';
		$user = get("user");
		$pwd = get("pwd");
		$dt = $this->user_exists($user);

		if (isset($dt[0])) {
			if ($dt[0]['us_password'] == md5($pwd)) {
				$_SESSION['id'] = $dt[0]['id_us'];
				$_SESSION['user'] = $dt[0]['us_nome'];
				$_SESSION['email'] = $dt[0]['us_email'];
				$sx .= '<h2>' . lang('social.success') . '<h2>';
				$sx .= '<meta http-equiv="refresh" content="2;URL=\'' . PATH . MODULE . '\'">';
			} else {
				$sx .= '<h2>' . lang('ERROR') . '<h2>';
				$sx .= '<span class="singin" onclick="showLogin()">' . lang('social.return') . '</span>';
			}
		} else {
			$sx .= '<h2>' . lang('social.user_error') . '<h2>';
			$sx .= '<span class="singin" onclick="showLogin()">' . lang('social.return') . '</span>';
		}
		return $sx;
	}

	function signup()
	{
		$sx = '';
		$user = get("signup_email");
		$pw1 = get("signup_password");
		$pw2 = get("signup_retype_password");

		$dt = $this->user_exists($user);

		if (!isset($dt[0])) {
			$this->user_add($user, $pw1);
			$sx .= '<h2>' . lang('social.social_user_add_success') . '<h2>';
			$sx .= '<span class="singin" onclick="showLogin()">' . lang('social.return') . '</span>';
		} else {
			$sx .= '<h2>' . lang('social.user_already') . '<h2>';
			$sx .= '<span class="singin" onclick="showLogin()">' . lang('social.return') . '</span>';
		}
		return $sx;
	}

	function user_add($user, $pw1)
	{
		$data = [
			'us_email' => $user,
			'us_password'  => md5($pw1),
			'us_password_method' => 'MD5'
		];
		$this->insert($data);
	}

	function user_exists($email = '')
	{
		$dt = array();
		if (strlen($email) > 0) {
			$dt = $this->where('us_email', $email)->findAll();
		}
		return $dt;
	}


	function logout()
	{
		$session = \Config\Services::session();
		$url = \Config\Services::url();
		$session->destroy();

		return (redirect()->to('/'));
	}

	function nav_user()
	{
		$sx = '';
		if ($this->loged()) {
			$email = $_SESSION['email'];
			$sx = '';
			$sx .= '<ul class="navbar-nav ml-auto" >';
			$sx .= '        <li class="nav-item dropdown ml-auto">' . cr();
			$sx .= '          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">' . cr();
			$sx .= '            ' . $email . cr();
			$sx .= '          </a>' . cr();
			$sx .= '          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">' . cr();
			$sx .= '            <li><a class="dropdown-item" href="' . (PATH . MODULE . 'social/?cmd=perfil') . '">' . lang('social.perfil') . '</a></li>' . cr();
			$sx .= '            <li><a class="dropdown-item" href="' . (PATH . MODULE . 'social/?cmd=logout') . '">' . lang('social.logout') . '</a></li>' . cr();
			$sx .= '          </ul>' . cr();
			$sx .= '        </li>' . cr();
			$sx .= '</ul>' . cr();
		} else {
			$sx .= '<li class="nav-item d-flex align-items-center">';
			$sx .= '
              		<a href="' . (PATH . MODULE . 'social/login') . '" class="nav-link text-body font-weight-bold px-0">
                	<i class="fa fa-user me-sm-1"></i>
                	<span class="d-sm-inline d-none">' . lang('social.social_sign_in') . '</span></a>';
			$sx .= '</li>';
		}
		return $sx;
	}
	function loged()
	{
		if ((isset($_SESSION['id'])) and ($_SESSION['id'] != '')) {
			$id = $_SESSION['id'];
			return ($id);
		} else {
			return (0);
		}
	}

	function login($err = '')
	{
		global $msg;
		if ($this->loged()) {
			$sx = '<div>';
			$sx .= 'LOGADO';
			$sx .= '</div>';
			return $sx;
		}

		$err = get("erro");

		$bk = '#0093DD';
		$bknav = '#FFFFFF';

		$sx = '';
		$sx .= '
		<style>
		* {
		  box-sizing: border-box;
		}
		
		body {
		  font-family: Tahoma, Verdana, Segoe, sans-serif;
		  font-size: 14px;
		  text-align: center;
		}
		
		.wrapper {
		  width: 250px;
		  height: 350px;
		  margin: 30px auto;
		  perspective: 600px;
		  text-align: left;
		}
		
		.rec-prism {
		  width: 100%;
		  height: 100%;
		  position: relative;
		  transform-style: preserve-3d;
		  transform: translateZ(-100px);
		  transition: transform 0.5s ease-in;
		}
		
		.face {
		  position: absolute;
		  width: 250px;
		  height: 350px;
		  padding: 20px;
		  background: rgba(250, 250, 250, 0.96);
		  border: 3px solid ' . $bk . ';
		  border-radius: 3px;
		}
		.face .content {
		  color: #666;
		}
		.face .content h2 {
		  font-size: 1.2em;
		  color: ' . $bk . ';
		}
		.face .content .field-wrapper {
		  margin-top: 30px;
		  position: relative;
		}
		.face .content .field-wrapper label {
		  position: absolute;
		  pointer-events: none;
		  font-size: 0.85em;
		  top: 40%;
		  left: 0;
		  transform: translateY(-50%);
		  transition: all ease-in 0.25s;
		  color: #999999;
		}
		.face .content .field-wrapper input[type=text], .face .content .field-wrapper input[type=password], .face .content .field-wrapper input[type=submit], .face .content .field-wrapper textarea {
		  -webkit-appearance: none;
		  appearance: none;
		}
		.face .content .field-wrapper input[type=text]:focus, .face .content .field-wrapper input[type=password]:focus, .face .content .field-wrapper input[type=submit]:focus, .face .content .field-wrapper textarea:focus {
		  outline: none;
		}
		.face .content .field-wrapper input[type=text], .face .content .field-wrapper input[type=password], .face .content .field-wrapper textarea {
		  width: 100%;
		  border: none;
		  background: transparent;
		  line-height: 2em;
		  border-bottom: 1px solid ' . $bk . ';
		  color: #666;
		}
		.face .content .field-wrapper input[type=text]::-webkit-input-placeholder, .face .content .field-wrapper input[type=password]::-webkit-input-placeholder, .face .content .field-wrapper textarea::-webkit-input-placeholder {
		  opacity: 0;
		}
		.face .content .field-wrapper input[type=text]::-moz-placeholder, .face .content .field-wrapper input[type=password]::-moz-placeholder, .face .content .field-wrapper textarea::-moz-placeholder {
		  opacity: 0;
		}
		.face .content .field-wrapper input[type=text]:-ms-input-placeholder, .face .content .field-wrapper input[type=password]:-ms-input-placeholder, .face .content .field-wrapper textarea:-ms-input-placeholder {
		  opacity: 0;
		}
		.face .content .field-wrapper input[type=text]:-moz-placeholder, .face .content .field-wrapper input[type=password]:-moz-placeholder, .face .content .field-wrapper textarea:-moz-placeholder {
		  opacity: 0;
		}
		.face .content .field-wrapper input[type=text]:focus + label, .face .content .field-wrapper input[type=text]:not(:placeholder-shown) + label, .face .content .field-wrapper input[type=password]:focus + label, .face .content .field-wrapper input[type=password]:not(:placeholder-shown) + label, .face .content .field-wrapper textarea:focus + label, .face .content .field-wrapper textarea:not(:placeholder-shown) + label {
		  top: -35%;
		  color: #42509e;
		}
		.face .content .field-wrapper input[type=submit] {
		  -webkit-appearance: none;
		  appearance: none;
		  cursor: pointer;
		  width: 100%;
		  background: ' . $bk . ';
		  line-height: 2em;
		  color: #fff;
		  border: 1px solid ' . $bk . ';
		  border-radius: 3px;
		  padding: 5px;
		}
		.face .content .field-wrapper input[type=submit]:hover {
		  opacity: 0.9;
		}
		.face .content .field-wrapper input[type=submit]:active {
		  transform: scale(0.96);
		}
		.face .content .field-wrapper textarea {
		  resize: none;
		  line-height: 1em;
		}
		.face .content .field-wrapper textarea:focus + label, .face .content .field-wrapper textarea:not(:placeholder-shown) + label {
		  top: -25%;
		}
		.face .thank-you-msg {
		  position: absolute;
		  width: 200px;
		  height: 130px;
		  text-align: center;
		  font-size: 2em;
		  color: ' . $bk . ';
		  left: 50%;
		  top: 50%;
		  -webkit-transform: translate(-50%, -50%);
		}
		.face .thank-you-msg:after {
		  position: absolute;
		  content: "";
		  width: 50px;
		  height: 25px;
		  border: 10px solid ' . $bk . ';
		  border-right: 0;
		  border-top: 0;
		  left: 50%;
		  top: 50%;
		  -webkit-transform: translate(-50%, -50%) rotate(0deg) scale(0);
		  transform: translate(-50%, -50%) rotate(0deg) scale(0);
		  -webkit-animation: success ease-in 0.15s forwards;
		  animation: success ease-in 0.15s forwards;
		  animation-delay: 2.5s;
		}
		.face-front {
		  transform: rotateY(0deg) translateZ(125px);
		}
		.face-top {
		  height: 250px;
		  transform: rotateX(90deg) translateZ(125px);
		}
		.face-back {
		  transform: rotateY(180deg) translateZ(125px);
		}
		.face-right {
		  transform: rotateY(90deg) translateZ(125px);
		}
		.face-left {
		  transform: rotateY(-90deg) translateZ(125px);
		}
		.face-bottom {
		  height: 250px;
		  transform: rotateX(-90deg) translateZ(225px);
		}
		
		.nav {
		  padding: 0;
		  text-align: center;
		}

		.nav li {
		  display: inline-block;
		  list-style-type: none;
		  font-size: 1em;
		  margin: 0 10px;
		  color: ' . $bknav . ';
		  position: relative;
		  cursor: pointer;
		}
		.nav li:after {
		  content: "";
		  position: absolute;
		  bottom: 0;
		  left: 0;
		  width: 20px;
		  border-bottom: 1px solid ' . $bknav . ';
		  transition: all ease-in 0.25s;
		}
		.nav li:hover:after {
		  width: 100%;
		}
		
		.psw, .signup, .singin {
		  display: block;
		  margin: 10px 0;
		  font-size: 0.75em;
		  text-align: center;
		  color: #42509e;
		  cursor: pointer;
		}
		
		small {
		  font-size: 0.7em;
		}
		
		@-webkit-keyframes success {
		  from {
			-webkit-transform: translate(-50%, -50%) rotate(0) scale(0);
		  }
		  to {
			-webkit-transform: translate(-50%, -50%) rotate(-45deg) scale(1);
		  }
		}
		</style>
		
		<script>
		  window.console = window.console || function(t) {};
		  if (document.location.search.match(/type=embed/gi)) {
			window.parent.postMessage("resize", "*");
		  }
		</script>		
		<ul class="nav center" style="margin: 0% 20%; display: none;">
		<li onclick="showLogin()">' . lang('social.social_login') . '</li>
		<li onclick="showSignup()">' . lang('social.social_sign_up') . '</li>
		<li onclick="showForgotPassword()">' . lang('social.social_forgot_password') . '</li>
		<li onclick="showSubscribe()">' . lang('social.social_subscrime') . '</li>
		<li onclick="showContactUs()">' . lang('social.social_contact_us') . '</li>
		</ul>
		
		<div class="wrapper">
		  <div class="rec-prism">
		    <!--- BOARD ----------------------------------------------->
			<div class="face face-top">
			  <div class="content">
				<h2>' . lang('social.social_message') . '</h2>
				<small>' . lang('social.social_message_inf') . '</small>
				<h3 style="color: red;">' . $err . '</h3>

				<span class="singin" onclick="showLogin()">' . lang('social.return') . '</span>
			  </div>
			</div>
			<!---- SIGN IN-------------------------------------------->
			<div class="face face-front">
			  <div class="content">
				<h2>' . lang('social.social_sign_in') . '</h2>
				  <div class="field-wrapper">
					<input type="text" id="user_login" placeholder="' . lang('social.user_login') . '" value="' . get("user_login") . '">
					<label>' . lang('social.social_type_login') . '</label>
				  </div>
				  <div class="field-wrapper">
					<input type="password" id="user_password" placeholder="' . lang('social.user_password') . '" autocomplete="new-password">
					<label>' . lang('social.social_type_password') . '</label>
				  </div>
				  <div class="field-wrapper">
				    <button class="btn btn-primary" style="width: 100%;" onclick="action_ajax(\'signin\');">' . lang('social.enter') . '</button>
				  </div>
				  <span class="psw" onclick="showForgotPassword()">' . lang('social.social_forgot_password') . '</span>
				  <span class="signup" onclick="showSignup()">' . lang('social.social_not_user') . '  ' . lang('social.social_sign_up') . '</span>
				  <span class="signup" onclick="showContactUs()">' . lang('social.social_questions') . '</span>	
			  </div>
			</div>
			<!-- FORGOT --------------------------------------------->
			<div class="face face-back">
			  <div class="content">
				<h2>' . lang('social.social_forgot_password') . '</h2>
				<small>' . lang('social.social_forgot_password_info') . '</small>
				<form onsubmit="event.preventDefault()">
				  <div class="field-wrapper">
					<input type="text" name="email" placeholder="email">
					<label>e-mail</label>
				  </div>
				  <div class="field-wrapper">
					<button class="btn btn-primary" style="width: 100%;" onclick="action_ajax(\'signup\');">' . lang('social.enter') . '</button>
				  </div>
				  <span class="singin" onclick="showLogin()">' . lang('social.social_alread_user') . '  ' . lang('social.social_sign_in') . '</span>				  
				</form>
			  </div>
			</div>
			<!-- SIGN UP -------------------------------------------->
			<div class="face face-right">
			  <div class="content">
				<h2>' . lang('social.social_sign_up') . '</h2>				
				  <div class="field-wrapper">
					<input type="text" id="signup_email" placeholder="email">
					<label>e-mail</label>
				  </div>
				  <div class="field-wrapper">
					<input type="password" id="signup_password" placeholder="password" autocomplete="new-password">
					<label>' . lang('social.social_type_password') . '</label>
				  </div>
				  <div class="field-wrapper">
					<input type="password" id="signup_retype_password" placeholder="password" autocomplete="new-password">
					<label>' . lang('social.social_retype_password') . '</label>
				  </div>
				  <div class="field-wrapper">
					<button class="btn btn-primary" style="width: 100%;" onclick="action_ajax(\'signup\');">' . lang('social.enter') . '</button>
				  </div>
				  <span class="singin" onclick="showLogin()">' . lang('social.social_alread_user') . '  ' . lang('social.social_sign_in') . '</span>
			  </div>
			</div>
			<!-- Contact US ------------------------------------------>
			<div class="face face-left">
			  <div class="content">
				<h2>' . lang('social.social_contact_us') . '</h2>
				<form onsubmit="event.preventDefault()">
				  <div class="field-wrapper">
					<input type="text" name="name" placeholder="name">
					<label>' . lang('social.social_name') . '</label>
				  </div>
				  <div class="field-wrapper">
					<input type="text" name="email" placeholder="email">
					<label>e-mail</label>
				  </div>
				  <div class="field-wrapper">
					<textarea placeholder="' . lang('social.social_yourmessage') . '" rows=3></textarea>
					<label>' . lang('social.social_yourmessage') . '</label>
				  </div>
				  <div class="field-wrapper">
					<input type="submit" onclick="showThankYou()">
				  </div>
				  <span class="singin" onclick="showLogin()">' . lang('social.social_alread_user') . '  ' . lang('social.social_sign_in') . '</span>
				</form>
			  </div>
			</div>
			<!-- Contact US ------------------------------------------>
			<div class="face face-bottom">
			  <div class="content">
			  		<div id="board">
					<h2>' . lang('social.conecting') . '</h2>
					</div>
				</div>
			  </div>
			</div>
		  </div>
		</div>
		<script src="https://cpwebassets.codepen.io/assets/common/stopExecutionOnTimeout-8216c69d01441f36c0ea791ae2d4469f0f8ff5326f00ae2d00e4bb7d20e24edb.js"></script>
		
		  
		<script id="rendered-js" >
		let prism = document.querySelector(".rec-prism");

		function await(ms){
			var start = new Date().getTime();
			var end = start;
			while(end < start + ms) {
				end = new Date().getTime();
			}
		}	

		function ajax(cmd)
			{
			var data = new FormData();
			data.append("cmd", cmd);
			if (cmd == "signin")
				{
					data.append("user", document.getElementById("user_login").value);
					data.append("pwd", document.getElementById("user_password").value);					
				}
			if (cmd == "signup")
				{
					data.append("signup_email", document.getElementById("signup_email").value);
					data.append("signup_password", document.getElementById("signup_password").value);
					data.append("signup_retype_password", document.getElementById("signup_retype_password").value);
				}

            var url = "' . PATH . MODULE . 'social/ajax/"+cmd;
            var xhttp = new XMLHttpRequest();
            xhttp.open("POST", url, false);
            xhttp.send(data);

            	document.getElementById("board").innerHTML = xhttp.responseText;
			 	console.log(this.responseText);
			}	
		
		function showSignup() {
		  prism.style.transform = "translateZ(-100px) rotateY( -90deg)";
		}
		function showLogin() {
		  prism.style.transform = "translateZ(-100px)";
		}
		function showForgotPassword() {
		  prism.style.transform = "translateZ(-100px) rotateY( -180deg)";
		}
		
		function showSubscribe() {
		  prism.style.transform = "translateZ(-100px) rotateX( -90deg)";
		}
		
		function showContactUs() {
		  prism.style.transform = "translateZ(-100px) rotateY( 90deg)";
		}
		
		function showThankYou() {
		  prism.style.transform = "translateZ(-100px) rotateX( 90deg)";
		}

		function action_ajax($cmd)
			{
				prism.style.transform = "translateZ(-100px) rotateX( 90deg)";
				ajax($cmd);
			}
		//# sourceURL=pen.js		
		';
		if (strlen($err) > 0) {
			$sx .= '
				(function() {
					await(500); showSubscribe();
				})();
				';
		}
		$sx .= '</script>';

		return ($sx);
	}
}
