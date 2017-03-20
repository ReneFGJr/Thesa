<?php
class find extends CI_controller {
	function __construct() {
		parent::__construct();
		$this -> lang -> load("skos", "portuguese");
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

	function cab($nb = 1) {
		$this -> load -> view('find/header/header', null);
		if ($nb > 0) {
			$this -> load -> view("find/header/cab", null);
			$this -> load -> view('find/header/menu_top');
			$data['content'] = '<br><br><br><br><br>';
			$this -> load -> view('content', $data);
		}
	}

	function foot() {
		$sx = '<hr>Rodape<br><br><br><br><br><br><br><br>';
		$data['content'] = $sx;
		$this -> load -> view('content', $data);
	}

	function index() {
		$this -> load -> model('finds');
		$this -> cab(1);

		$tela = '<img src="' . base_url('img/find/logo_find.png') . '">';

		$data['content'] = $tela;
		$this -> load -> view('content', $data);

	}

	function attribute($id = '') {
		/* Edit attribute */
		$this -> load -> model('finds');
		$this -> cab(1);
		
		$data = $this->finds->le_attr($id);

		$this->load->view('find/class/view',$data);

		$tela2 = $this -> finds -> attr_edit($id);

		$tela = $this -> finds -> attr_class($id) . $tela2;

		$data['content'] = $tela;
		$data['content'] .= $this->finds->list_data_attr($id);
		
		$this -> load -> view('content', $data);
		$this -> foot();
	}

	function classes($id = '') {
		/* Edit attribute */
		$this -> load -> model('finds');
		$this -> cab(1);

		$tela = $this -> finds -> row_classes();
		$data['content'] = $tela;
		$this -> load -> view('content', $data);

		$this -> foot();
	}

	function tp($action = '') {
		/* Technical preparation */
		$this -> load -> model('finds');
		$this -> cab(1);

		switch($action) {
			case 'work' :
				$class = $this -> finds -> work;
				$name = get("name");
				$lang = get("lang");
				$this -> finds -> create_id($name, $lang, $class);
				break;
			case 'frad' :
				$name = get("name");
				$lang = get("lang");
				$class = get("class");
				$this -> finds -> create_id($name, $lang, $class);
				break;

			default :
				break;
		}
		$this -> foot();
	}

	function editvw($i1, $i2, $i3, $i4 = '') {
		$this -> load -> model('finds');
		$this -> cab(0);

		if (strlen($i4) > 0) {
			$this -> finds -> rdf_propriety($i1, $i2, $i3, $i4);
		}

		$tela = $this -> finds -> editvw($i1, $i2, $i3, $i4);
		$data['content'] = $tela;
		$data['title'] = '';
		$this -> load -> view('content', $data);
	}

	function ve($id = '') {
		$this -> load -> model('finds');
		$this -> cab(1);

		$data = $this -> finds -> le($id);

		if (count($data) > 0) {

			$tela = $this -> load -> view('find/view_work', $data, true);
			$tela .= $this -> finds -> view_propriety($id);

			$data['content'] = $tela;
			$this -> load -> view('content', $data);

			$class = $data['f_class'];

			$tela = $this -> finds -> class_value_edit($class, $id);
			$data['content'] = $tela;
			$this -> load -> view('content', $data);
		}

		$this -> foot();
	}

	function v($id = '') {
		$this -> load -> model('finds');
		$this -> cab(1);

		$data = $this -> finds -> le($id);

		$tela = $this -> load -> view('find/view_work', $data, true);
		$tela .= $this -> finds -> view_propriety($id);

		$data['content'] = $tela;

		$this -> load -> view('content', $data);
		$this -> foot();
	}

	function work() {
		$this -> load -> model('finds');
		$this -> cab(1);

		$data = array();
		$data['dd2'] = get("dd2");
		$this -> load -> view('find/header/search', $data);

		if (strlen($data['dd2']) > 0) {
			$tela = $this -> finds -> literal($data['dd2']);
			$rst = $this -> finds -> result;

			if ($rst == -1) {
				$tela .= $this -> finds -> incorpore_id($data['dd2'], 'work');
			}

			$data['content'] = $tela . $rst;
			$this -> load -> view('content', $data);
		}

	}

	function nomen() {
		$this -> load -> model('finds');
		$this -> cab(1);

		$data['content'] = '';
		$data['title'] = 'FRAD - Group 2';
		$this -> load -> view('content', $data);

		$data = array();
		$data['dd2'] = get("dd2");
		$this -> load -> view('find/header/search', $data);

		if (strlen($data['dd2']) > 0) {
			$tela = $this -> finds -> literal($data['dd2']);
			$rst = $this -> finds -> result;

			if ($rst == -1) {
				$tela .= $this -> finds -> incorpore_id($data['dd2'], 'frad');
			}

			$data['content'] = $tela . $rst;
			$this -> load -> view('content', $data);
		}

	}

	function update() {
		$this -> load -> model('finds');
		$this -> cab(1);
		$file = 'xml/thesa-01.xml';
		$table = 'rdf_resource';
		
		$class = 79;
		$tela = $this->finds->import_xml($file,$class);
		$data['content'] = $tela;
		$this->load->view('content',$data);
	}

}
?>
