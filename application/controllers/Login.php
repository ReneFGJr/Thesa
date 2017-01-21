<?php
class Login extends CI_controller {
	function __construct() {
		parent::__construct();
		$this -> lang -> load("login", "portuguese");
		//$this -> lang -> load("skos", "portuguese");
		//$this -> load -> library('form_validation');
		$this -> load -> database();
		//$this -> load -> helper('form');
		//$this -> load -> helper('form_sisdoc');
		$this -> load -> helper('url');
		$this -> load -> library('session');

		date_default_timezone_set('America/Sao_Paulo');
		/* Security */
		//		$this -> security();
	}

	function cab($nb = 1) {
		$this -> load -> view('header/header', null);
		if ($nb == 1) {
			$this -> load -> view('header/menu_top');
		}
	}

	public function index() {
		$this -> cab();
		$this -> load -> view('login/index', null);

		$this -> validate();

	}

	/* autenticacao */
	private function validate() {
		$login = get("user");
		$passw = get("password");

		if ((strlen($login) > 0) and (strlen($passw) > 0)) {
			$sql = "select * from users where us_login = '$login' ";
			$rlt = $this -> db -> query($sql);
			$rlt = $rlt -> result_array();
			if (count($rlt) > 0) {
				$line = $rlt[0];
				if ($passw == $line['us_password']) {
					$ck = array();
					$ck['user'] = trim($line['us_login']);
					$ck['perfil'] = trim($line['us_perfil']);
					$ck['nome'] = trim($line['us_nome']);
					$ck['nivel'] = trim($line['us_nivel']);
					$ck['id'] = trim($line['id_us']);
					$ck['check'] = md5($line['id_us'].date("Ymd"));
					$this->session->set_userdata($newdata);	
					redirect(base_url('index.php/skos'));				
				}
			}
		}
	}

}
?>
