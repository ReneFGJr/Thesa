<?php
class foaf extends CI_Controller
	{
function __construct() {
		parent::__construct();
		$this -> lang -> load("foaf", "portuguese");
		$this -> load -> library('form_validation');
		$this -> load -> database();
		$this -> load -> helper('form');
		$this -> load -> helper('form_sisdoc');
		$this -> load -> helper('url');
		$this -> load -> library('session');
		$this -> load -> helper('xml');
		$this -> load -> helper('email');

		date_default_timezone_set('America/Sao_Paulo');
		/* Security */
		//		$this -> security();
	}

	function index() {
		$this -> cab(2);

		$this -> load -> view("foaf/github_fork", null);
		$this -> load -> view("foaf/welcome", null);

		$this -> load -> view("foaf/foaf_home_pt", null);
		//redirect(base_url('index.php/foaf/myfoaf'));
	}

	function cab($nb = 1) {
		$this -> load -> view('foaf/header/foaf_header', null);
		if ($nb > 0) {
			$this -> load -> view("foaf/header/foaf_cab", null);
			$this -> load -> view('foaf/header/foaf_menu');
			if ($nb != 2) {
				$this -> load -> view("foaf/foaf_search");
			}
		}
	}

	function show_404() {
		$this -> cab(0);
		$this -> load -> view('foaf/404', null);
	}

	function foot() {
		$this -> load -> view('foaf/header/footer', null);
	}

	function security() {
		$user = $this -> session -> userdata('user');
		$email = $this -> session -> userdata('email');
		$nivel = $this -> session -> userdata('nivel');
		if (round($nivel) < 1) {
			redirect(base_url('index.php/social/login'));
		}
	}		
	}
?>
