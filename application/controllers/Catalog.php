<?php
class Catalog extends CI_Controller {
	
	var $work_class = 17;

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

	function index() {
		$this -> cab(1);

	}

	function cab($nb = 1) {
		$this -> load -> view('catalog/cat_header', null);
		if ($nb > 0) {
			$this -> load -> view("catalog/cat_cab", null);
			$this -> load -> view('catalog/cat_menu');
			if ($nb != 2) {
				$this -> load -> view("catalog/cat_search");
			}
		}
	}
	
	function a($id='',$fmt='') {
		$this -> load -> model("frbrs");
		$this -> load -> model("Authorities");
		$id = sonumero($id);
		
		$dd2 = get("dd2");
		$dd3 = get("dd3");
		$acao = get("acao");
		
		if ((strlen($dd2) > 0) and (strlen($dd3) > 0) and (strlen("acao") > 0))
			{
				$this->Authorities->relation_insert($id,$dd2,$dd3);
			}
		
		switch($fmt)
			{
			case 'xml':
				break;
			default:
				$this -> cab();
				$data = $this->Authorities->le_a($id);
				$this->load->view('catalog/ca_name',$data);
				
				$this->load->view('catalog/ca_proprieties',$data);

				$class = $data['class'];
				$class_id = $data['id_ty'];
				switch($class)
					{
					case 'work':
						$class_id = 'WORK-ATT';
						$tela = $this->frbrs->rdf_class_edit($class_id);
						break;
					default:
						$tela = '';
						break;
					}
				$data['content'] = $tela;
				$this->load->view('content',$data);		
				break;
			}		
	}
	
	function w($id = 1) {
		$this -> load -> model("catalogs");
		$this -> cab();
		
		$data = $this->catalogs->le_w($id);
		
		$this->load->view('catalog/cat_work',$data);
		$data['frad'] = $this->load->view('catalog/cat_frbr_frad',$data, true);
		$data['frsad'] = $this->load->view('catalog/cat_frbr_frsad',$data, true);
		
		$data['expression'] = $this->load->view('catalog/cat_frbr_expression',$data, true);
		$data['manifestation'] = $this->load->view('catalog/cat_frbr_manifestation',$data, true);
		$data['item'] = $this->load->view('catalog/cat_frbr_item',$data, true);
		

		
		$this->load->view('catalog/cat_frbr',$data);
		$this->foot();
		
		
	}	
	
	function input($type='',$id='',$propr='',$class='',$chk='')
		{
			$data = array();
			$data['id'] = $id;
			$data['propriety'] = $propr;
			
			$this -> load -> model("catalogs");
			$this -> load -> model("authorities");
			$this->cab(0);
			
			switch ($type)
				{
					case 'workfrad':
						$ida = round(get("dd10"));
						if ($ida > 0)
							{
								$this->catalogs->insert_propriety($id,$propr,$ida);	
							} else {
								$data['content'] = $this->authorities->form_search($data);
								$data['title'] = '';
								$this->load->view('content',$data);
							}
						break;
					case 'workmani':
						$ida = round(get("dd10"));
						if ($ida > 0)
							{
								$this->catalogs->insert_propriety($id,$propr,$ida);	
							} else {
								$data['content'] = $this->authorities->form_search($data,$class);
								$data['title'] = '';
								$this->load->view('content',$data);
							}
						break;
					default:
						$data['title'] = 'Not Implemented';
						$data['content'] = 'Erro 400';
						$this->load->view('catalog/510',$data);
				}
		}

	function literal($id='',$fmt='') {
		$this -> load -> model("Authorities");
		$id = sonumero($id);
		switch($fmt)
			{
			case 'xml':
				break;
			default:
				$this -> cab();
				$data = $this->Authorities->le_l($id);
				if ($data['redirect'] > 0)
					{
						redirect(base_url('index.php/catalog/a/'.$data['redirect']));
						print_r($data);
						exit;
					}
				$this->load->view('ca/ca_literal',$data);
				if ($this->Authorities->is_auth_id($id)==0)
					{
						$data['content'] = $this->Authorities->create_access_point($id);
						$this->load->view('ca/ca_literal_set',$data);
					}
				break;
			}		
	}	

	
	function search($term1='') {
		$this -> load -> model("Authorities");
		$this -> cab();
		$th = '';

		$term = get("dd1").$term1;
		
		$tela = '<h3>'.$term.'</h3>'.cr();
		$tela .= $this->Authorities->search_term($term,$th);
		
		//if ((count($this->Authorities->rlt) == 0) and (strlen($term) > 0))
		if ((strlen($term) > 0) and ($this->Authorities->math == 0))
			{
				$data['name'] = $term;
				$data['tela'] = $this->Authorities->incorporate($term);
				$tela .= $this->load->view('ca/ca_incorporate',$data,true);
			}
		
		$data['content'] = $tela;
		$data['title'] = '';
		$this->load->view('content',$data);
		
	}
	
	function foot() {
		$this -> load -> view('header/footer', null);
	}		

}
?>