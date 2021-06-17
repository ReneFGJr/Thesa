<?php
define("LIBRARY_NAME","Thesa");
define("LIBRARY","4000");
class Thesa extends CI_Controller {
	
	function __construct() {
		global $lang;
		parent::__construct();
		date_default_timezone_set('America/Sao_Paulo');
		//date_default_timezone_set('Asia/Brunei');	
		
		$this -> load -> library('session');
		$this -> load -> helper('language');
		$language = new language;
		$this -> lang -> load("thesa", $language->language());
		$this -> load -> library('form_validation');
		$this -> load -> database();
		$this -> load -> helper('form');
		$this -> load -> helper('form_sisdoc');
		$this -> load -> helper('url');
		$this -> load -> helper('bootstrap');
		
		
		$this -> load -> helper('xml');
		$this -> load -> helper('email');
		$this -> load -> library('email');
		
		/* Security */
		$this -> load -> helper('socials');
		//      $this -> security();
		
		$this -> load -> model('thesa_api');
		
		$this -> load -> model('skoses');
		$this -> load -> model('creativecommons');
	}
	
	function index() {
		$this -> cab(1);
		$data = $this -> skoses -> welcome_resumo();
		$this -> load -> view("thesa/home/welcome", $data);
		$this -> load -> view("thesa/home/spots", $data);
		$this -> footer();
	}
	
	private function cab($navbar = 1) {
		if (is_array($navbar))
		{
			$data['title'] = 'Thesa - '.msg('th_type_'.$navbar['pa_type']).' '.$navbar['pa_name'];
			$navbar = 1;
		} else {
			$data['title'] = 'Thesa - Tesauro Semântico Aplicado';
		}
		
		$this -> load -> view('thesa/header/header', $data);
		if ($navbar == 1) {
			$this -> load -> view('thesa/header/navbar', null);
		}
	}
	
	private function footer() {
		$data = array();
		$data['content'] = '<br/><br/><br/><br/>';
		$data['title'] = '';
		$this->load->view('content',$data);
		$this -> load -> view('thesa/header/footer', $data);
	}
	
	function apis()
	{
		$this->cab();
		$this->load->view('docummentation/api',null);
		$this->footer();
	}
	
	function api($d1='',$d2='')
	{
		$this->load->model("thesa_api");		
		$this->thesa_api->index($d1,$d2);
	}
	
	function file_import()
	{
		$this->load->model("skoses");
		$this->cab();
		$this->skoses->import_file();
		$this->footer();
	}
	
	function thesaurus_open() {
		$this -> load -> model('skoses');
		$this -> cab(1);
		
		$data['content'] = '<h1 style="color: white;">' . msg('open_thesaurus') . '</h1>';
		$this -> load -> view('thesa/home/parallax', $data);
		
		$tela = $this -> skoses -> myskoses(0);
		$data['content'] = $tela;
		
		$total = $this -> skoses -> myskoses_total(0);
		
		$data['title'] = '';
		$this -> load -> view('content', $data);
		
		$this -> footer();
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
			
			redirect(base_url('index.php/thesa/thesaurus_my/'));
		}
		
	}
	
	function th_edit($id = '', $chk = '') {
		/* Load model */
		if (checkpost($id,$chk) == 1)
		{
			$this -> load -> model("skoses");
			$this -> skoses -> th_assosiation_users();
			
			$this -> cab(1);
			
			if ($id != '') {
				$_SESSION['skos'] = $id;
			}
			$id = $_SESSION['skos'];
			$data = $this -> skoses -> le_skos($id);
			$data['editar'] = 1;
			
			$this -> load -> view('thesa/view/thesaurus', $data);
			$this -> load -> view('thesa/header/navbar_tools', null);
			
			if ((count($data) > 0)) {
				if ((isset($_SESSION['id']) and ($_SESSION['id']) == $data['pa_creator']) and ($data['id_pa'] == $id)) {
					$cp = $this -> thesa_api -> cp_th($id);
					$form = new form;
					$form -> table = $this -> skoses -> table_thesaurus;
					$form -> cp = $cp;
					$form -> id = $id;
					
					$data['content'] = $form -> editar($cp, $this -> skoses -> table_thesaurus);
					$data['title'] = msg('Thesaurus');
					$this -> load -> view('content', $data);
					
					if ($form -> saved > 0) {
						if (strlen($id) > 0) {
							redirect(base_url('index.php/thesa/select/' . $id . '/' . checkpost_link($id)));
						} else {
							redirect(base_url('index.php/thesa/thesaurus_my'));
						}
					}
					
					$msg = '';
					
					/* ICONE */
					$data['title'] = msg('icones');
					$sx = '<div class="content"><div class="row">';
					$sx .= '<div class="col-md-2">';
					$sx .= '<h1>'.msg('Icone').'</h1>';
					$sx .= $this -> skoses -> show_icone($data['pa_icone'],$data);
					$sx .= '</div>';
					
					$sx .= '<div class="col-md-2">';
					$sx .= '<br><span onclick="newxy(\'' . base_url('index.php/thesa/icone/') . '\',600,600);" class="btn btn-outline-primary">' . msg('alter_icone') . '</span>';
					$sx .= '</div>';
					
					$sx .= '</div></div>';
					$data['title'] = '';
					$data['content'] = $sx;
					$this -> load -> view('content', $data);
					
					/* Colaboradores */
					$msg = $this -> skoses -> th_collaborators($id) . $msg;
					$data['title'] = msg('authors');
					$data['content'] = $msg;
					$this -> load -> view('content', $data);
				} else {
					$tela = '<div class="alert alert-danger" role="alert">
					' . msg('Unauthorized_access') . '
					</div>';
					$data['content'] = '<br>'.$tela;
					$data['title'] = '';
					$this -> load -> view('content', $data);
				}
			}
			$this -> load -> view('header/footer', null);
		} else {
			$tela = message(msg('Unauthorized_access'),3);
			$data['content'] = $tela;
			$this -> load -> view('content', $data);
		}
	}
	
	function thesaurus_my() {		
		if (!isset($_SESSION['id']) or (strlen($_SESSION['id'])==0))
		{
			redirect(base_url(PATH));
		}
		$this -> load -> model('skoses');
		$this -> cab(1);
		$data['content'] = '<h1 style="color: white;">' . msg('my_thesaurus') . '</h1>';
		$this -> load -> view('thesa/home/parallax', $data);
		
		/****** user id **********/
		$socials = new socials;
		$us = $socials -> user_id();
		
		$tela = $this -> skoses -> myskoses($us);
		$data['content'] = $tela;
		
		$total = $this -> skoses -> myskoses_total($us);
		
		if (($total == 0) or (perfil("#ADM"))) {
			$data['content'] .= $this -> load -> view('skos/thesa_btn_new', $data, true);
			$data['content'] .= '<br><br><br>';
		}
		
		$data['title'] = '';
		$this -> load -> view('content', $data);
		
		$this -> footer();
	}
	
	function show_404() {
		$this -> cab(0);
		$this -> load -> view('thesa/404', null);
	}
	
	function icone($id = '', $sel = '', $chk = '') {
		$this -> load -> model('skoses');
		$this -> cab(0);
		
		$th = $this -> skoses -> th();
		$data = $this -> skoses -> le_th($th);
		
		if (strlen($sel) > 0) {
			$chk2 = checkpost_link('icone' . $sel);
			if ($chk == $chk2) {
				$this -> skoses -> icone_remove($th);
				$this -> skoses -> icone_update($th, $sel);
				$txt = '<script> window.opener.location.reload(); close(); </script>';
				echo $txt;
				exit ;
			}
		}
		
		
		
		/******************
		* icone selected
		*/
		$img = $this -> skoses -> show_icone($data['pa_icone'],$data);
		$s = '<div class="container"><div class="row">';
		$s .= '<div class="col-sm-3">'.$img.'</div>';
		$s .= '<div class="col-sm-9"><h2>'.$data['pa_name'].'</h2></div>';
		$s .= '</div></div>';
		$s .= '<hr>';
		$data['content'] = $s;
		$data['title'] = '';
		$this -> load -> view('content', $data);
		
		
		/********************
		* icone_upload
		*/
		$s = $this->skoses->icone_upload($th,$data);
		$data['content'] = $s.'<hr>';
		$data['title'] = '';
		$this -> load -> view('content', $data);
		
		/********************
		* icone selected
		*/
		
		$s = msg('click_icone_to_select');		
		/******************/
		$s .= $this -> skoses -> icones_select($th);
		$data['content'] = $s;
		$data['title'] = '';
		$this -> load -> view('content', $data);
		
		
		
	}
	
	function imagem($tp) {
		$sx = '';
		$this -> load -> model('skoses');
		$th = $this -> skoses -> th();
		$data = $this -> skoses -> le_th($th);
		
		$this -> cab();
		
		/******************/
		$target_dir = "img/background/";
		$target_file = $target_dir . 'background_thema_' . $th . '.jpg';
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
		// Check if image file is a actual image or fake image
		if (isset($_FILES["fileToUpload"]["tmp_name"])) 
		{
			if (file_exists($_FILES["fileToUpload"]["tmp_name"]))
			{
				$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
				if ($check !== false) 
				{
					$sx = "File is an image - " . $check["mime"] . ".";
					$uploadOk = 1;
				} else {
					$sx = "File is not an image.";
					$uploadOk = 0;
				}
			}
		}
		
		// Allow certain file formats
		$err = '';
		if (!isset($_FILES["fileToUpload"]["tmp_name"])) {
			$uploadOk = 0;
		}
		if ($imageFileType != "jpg" && $imageFileType != "jpeg") {
			$err = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			$uploadOk = 0;
		}
		
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			$err = "Sorry, your file was not uploaded.";
			// if everything is ok, try to upload file
		} else {
			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
				$err = "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
				$sx .= message(msg('image_uploaded').' => '.$target_file,2);
			} else {
				$err = "Sorry, there was an error uploading your file.";
				$err .= '<hr>';
			}
		}
		/**********************/
		
		$html = '';
		$html .= '<hr>';
		$html .= $err;
		$html .= '<form action="upload.php" method="post" enctype="multipart/form-data">
		Select image to upload:
		<input type="file" name="fileToUpload" id="fileToUpload">
		<input type="submit" value="Upload Image" name="submit">
		</form>';
		$html .= '<hr>';
		$html .= '<img src="' . $data['image_bk'] . '" width="100%">';
		$html .= $sx;
		$data['content'] = $html;
		$data['title'] = 'THESA: ' . $data['pa_name'];
		$this -> load -> view('content', $data);
		
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

	function reports($id='',$cmd='')
		{
		$this -> load -> model('skoses');
		if (strlen($id) == 0) {
			$id = $this->skoses->th();
		}
		$data = $this -> skoses -> le_skos($id);
		
		$this -> cab($data);				
		/*********************************************************************** PART 1 **/
		$this -> load -> view('thesa/view/thesaurus', $data);
		
		/*********************************************************************** PART 2 **/
		$tela = '';
		$tela .= $this -> load -> view('thesa/header/navbar_tools', null, true);
		$tela .= '<h1>'.msg("Reports").'</h1>';
		switch($cmd)
			{
				case '1':
					$tela .= $this->skoses->reports($id);
					break;
				default:
				$tela .= '<ul>';
				$tela .= '<li><a href="'.base_url(PATH.'reports/'.$id.'/1').'">'.msg('management_reporting_1').'</a></li>';
				$tela .= '</ul>';
			}


		/*********************************************************************** FOOTER 2 **/
		$data['content'] = $tela;
		$data['title'] = '';
		$this -> load -> view('content', $data);
		
		$this->footer();			
		}


	
	function terms($id = '', $ltr = '') {
		$this -> load -> model('skoses');
		if (strlen($id) == 0) {
			$id = $this->skoses->th();
		}
		$data = $this -> skoses -> le_skos($id);
		
		$this -> cab($data);				
		/*********************************************************************** PART 1 **/
		$this -> load -> view('thesa/view/thesaurus', $data);
		
		/*********************************************************************** PART 2 **/
		$tela = '';
		$tela .= $this -> load -> view('thesa/header/navbar_tools', null, true);
		
		/*********************************************************************** PART 3 **/
		$tela .= $this -> skoses -> termos_pg($id);
		$tela .= msg('Export_to') . ': ' . $this -> skoses -> export_format($id);
		
		$tela .= '<div class="row">';
		
		$tela .= '  <div class="col-md-9">';
		if ($ltr == '')
		{
			$tela .= $this-> skoses->about($id);
		} else {
			$tela .= $this -> skoses -> termos_show_letter($id, $ltr);	
		}
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
		
		$this->footer();
		
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
			$data['title'] = '';
			$this -> load -> view('content', $data);
			
			$this -> load -> view('header/footer', null);
		}
		
		function term_edit($id = '', $idt = '') {
			$this -> term_edit2($id, $idt);
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
			$this -> load -> view('thesa/header/navbar_tools', null);
			
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
				redirect(base_url('index.php/thesa/term/' . $th.'/'.$id));
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
				$data['action'] .= '<br/><br/><a href="' . base_url('index.php/thesa/term_edit/' . $data['id_rl'] . '/' . checkpost_link($data['lt_thesauros']) . '/' . $data['id_rl']) . '" class="btn btn-warning" style="width: 100%;">' . msg('Term_edit_concept') . '</a></li>';
				$data['action'] .= '<br/><br/><a href="' . base_url('index.php/thesa/term_delete/' . $data['lt_thesauros'] . '/' . checkpost_link($data['lt_thesauros']) . '/' . $data['id_rl']) . '" class="btn btn-danger" style="width: 100%;">' . msg('Term_delete_concept') . '</a></li>';
			}
			$this -> load -> view('thesa/header/navbar_tools', null);
			$this -> load -> view('thesa/view/term', $data);
			
			$tela = '';
			$data['title'] = '';
			$data['content'] = $tela . '<br><br><br>';
			$this -> load -> view('content', $data);
			
			$this -> footer();
			
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
			$data['content'] = $tela . '<br><br>';
			$this -> load -> view('content', $data);
			
			$this -> footer();
			
		}
		
		/***************************************************************************** Conecpt */
		function c($c = '', $proto = '', $proto2 = '') 
		{
			$socials = new socials;
			$this -> load -> model("skoses");
			$this -> load -> model("frbrs");
			
			$data = $this -> skoses -> le($c);
			if (count($data) == 0) 
			{ redirect(base_url('index.php/thesa/error/c')); }
			$data = $this -> skoses -> le_c($data['id_c'], $data['ct_th']);
			if (count($data) == 0) 
			{ redirect(base_url('index.php/thesa/error/c')); }
			
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
				$datask = $this -> skoses -> le_skos($data['c_th']);
				$datask['pa_name'] .= ' - #'.$data['rl_value'];
				$this -> cab($datask);
				$this -> load -> view('thesa/view/thesaurus', $datask);
				/* menu */
				$this -> load -> view('thesa/header/navbar_tools', null);
				
				/* user allow ****************************************************/
				$user_id = $socials -> user_id();
				$data['edit'] = '';
				if ($user_id > 0) {
					$user_id = $socials -> user_id();
					$data['edit'] = isset($data['allow'][$user_id]);
				}
				$data['grapho'] = $this->frbrs->vis($data);
				$this -> load -> view("thesa/view/schema", $data);
				$this -> load -> view("thesa/view/concept", $data);
				$sx = $this->skoses->th_linkdata_show($c);			
				$data['content'] = $sx;
				$data['title'] = '';
				$this->load->view('content',$data);
			break;
		}
		
		//redirect(base_url('index.php/thesa/myskos'));
		$this -> footer();
	}
	
	function cedit($c = '', $th = '') {
		$socials = new socials;
		/* Load model */
		$edit = true;
		$this -> load -> model("skoses");
		$this -> cab();
		
		/* RECUPERA SCHEMA */
		if (strlen($th) == 0) {
			if (!isset($_SESSION['skos']))
			{
				redirect(base_url(PATH));
			}
			$th = $_SESSION['skos'];
		}
		
		/* LE DADOS SOBRE O SCHEMA */
		$data = $this -> skoses -> le_skos($th);
		$this -> load -> view('thesa/view/thesaurus', $data);
		$data['pg'] = 4;
		
		/* user allow ****************************************************/
		$user_id = $socials -> user_id();
		$data['edit'] = '';
		if ($user_id > 0) {
			$user_id = $socials -> user_id();
			$data['edit'] = isset($data['allow'][$user_id]);
		}
		
		/* menu */
		$this -> load -> view('thesa/header/navbar_tools', null);
		
		/* Recupera informações sobre o Concecpt */
		$data2 = $this -> skoses -> le_c($c, $th);
		$this->skoses->th_update($th);
		
		if (count($data2) == 0) {
			redirect(base_url('index.php/thesa/terms'));
			exit ;
		}
		
		$data2['editar'] = $data['edit'];
		$data2['edit'] = 1;
		
		$this -> load -> view("thesa/view/schema", $data2);
		$this -> load -> view("thesa/edit/concept", $data2);
		
		//$this -> load -> view('thesa/view/thesaurus_concept', $data2);
		
		//$this -> load -> view('skos/thesa_concept_tools', $data2);
		
		$data['content'] = $data2['logs'];
		$data['title'] = '';
		$this -> load -> view('content', $data);
		
		$this -> footer();
		
	}
	
	function cremove($id = '', $chk = '') {
		/* Load model */
		$edit = true;
		$this -> load -> model("skoses");
		$this -> cab(0);
		
		$ok = get("confirm");
		if ($ok == '1') {
			$this -> skoses -> c_remove($id);
			$this -> load -> view('wclose');
		} else {
			$data['content'] = '<br><br><h1>' . msg('remove_concept') . '</h1>';
			$data['content'] .= msg('content');
			$data['link'] = base_url('index.php/thesa/cremove/' . $id . '/' . $chk);
			$this -> load -> view('confirm.php', $data);
		}
	}
	
	function ntedit($id = '', $chk = '') {
		/* Load model */
		
		if ($chk != checkpost_link($id))
		{
			$this -> load -> view('wclose');  
		}
		
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
	
	function ntremove($id = '',$chk = '',$conf='')
	{
		/* Load model */
		$edit = true;
		$this -> load -> model("skoses");
		$this -> cab(0);
		
		if ($chk != checkpost_link($id))
		{
			$this -> load -> view('wclose');  
		}
		
		$data['content'] = $this -> skoses -> edit_nt_confirm($id);
		$data['title'] = '';
		$this -> load -> view('content', $data);
		
		if ($conf == 'confirm')
		{
			$data['content'] = '';
			$this -> skoses -> edit_nt_remove($id);
		}
		
		if (strlen($data['content']) == 0) {
			$this -> load -> view('wclose');
		}   
	}
	
	/* LOGIN */
	function social($act = '',$d1='',$d2='',$d3='') {
		$this->cab();
		$socials = new socials;
		$socials->social($act,$d1,$d2);
		$this -> footer();
		return('');
		
		$socials = new socials;
		$sx = $socials->social($act,$id1,$id2,$id3);
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
			
			$this->skoses->th_update($th);
			
			//$this->skoes->
		}
	}
	
	function te_remove($id = '', $chk = '') {
		$this -> load -> model("skoses");
		$this -> cab(2);
		
		$ok = get("confirm");
		if ($ok == '1') {
			$th = $_SESSION['skos'];
			$this->skoses->th_update($th);
			$this -> skoses -> te_remove($id);
			$this -> load -> view('wclose');
		} else {
			$data['content'] = '<br><br><h1>' . msg('remove_propriety') . '</h1>';
			$data['content'] .= msg('content');
			$data['link'] = base_url('index.php/thesa/te_remove/' . $id . '/' . $chk);
			$this -> load -> view('confirm.php', $data);
		}
	}
	
	function tr_remove($id = '', $chk = '') {
		$this -> load -> model("skoses");
		$this -> cab(2);
		
		$ok = get("confirm");
		if ($ok == '1') {
			$th = $_SESSION['skos'];
			$this->skoses->th_update($th);
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
		$th = $this->skoses->th();
		
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
			$ac = get("action");
			$this -> skoses -> assign_as_propriety($c, $th, $tr, $desc);
			if ($ac == msg('save')) {
				$this -> load -> view('header/close', null);
			} else {
				redirect(base_url('index.php/thesa/tz/' . $c));
			}
			$th = $_SESSION['skos'];
			$this->skoses->th_update($th);
			//$this->skoes->
		}
	}
	
	/******************************************************* Termo preferencial */
	function tp($c = '') {
		$c = round($c);
		$th = $this->skoses->th();
		
		/* Load model */
		$this -> load -> model("skoses");
		$this -> cab(0);
		
		$data = $this -> skoses -> le($c);
		$data['form'] = $this -> skoses -> form_concept($th, $c, 'LABEL');
		$this -> load -> view('thesa/view/concept_mini', $data);
		
		$action = get("action");
		$desc = get("tm");
		$tr = get("tr");
		
		if ((strlen($action) > 0) and (strlen($tr) > 0) and (strlen($desc) > 0)) {
			$ac = get("action");
			$this -> skoses -> assign_as_propriety($c, $th, $tr, $desc);
			if ($ac == msg('save')) {
				$th = $_SESSION['skos'];
				$this->skoses->th_update($th);			
				$this -> load -> view('header/close', null);
			} else {
				redirect(base_url('index.php/thesa/tp/' . $c));
			}
			
			//$this->skoes->
		}
	}	
	
	/***************************************************************************** TG Geral */
	function tg($c = '') {
		
		/***************************** TERMO GERAL ************/
		$c = round($c);
		$th = $_SESSION['skos'];
		
		$dt = $this->thesa_api->le($th);
		
		switch($dt['pa_type'])
		{
			case '95':
				$c = round($c);
				$th = $_SESSION['skos'];
				
				/* Load model */
				$this -> load -> model("skoses");
				$this -> cab(0);
				
				$data = $this -> skoses -> le($c);
				$data['form'] = $this -> skoses -> form_concept($th, $c, 'TG');
				$this -> load -> view('thesa/view/concept_mini', $data);
				
				$action = get("action");
				$desc = get("tm");
				$tr = get("tr");
				
				if ((strlen($action) > 0) and (strlen($tr) > 0) and (strlen($desc) > 0)) {
					
					echo 'SAVED';
					$th = $_SESSION['skos'];
					$this->skoses->th_update($th);				
					
					$this -> skoses -> assign_as_propriety($c, $th, $tr, $desc);
					$this -> load -> view('header/close', null);
					
					$line2 = $this -> skoses -> le_term($desc, $th);
					$line3 = $this -> skoses -> le_propriety($tr);
					$desc = msg('associated') . ' ' . msg($line3['rs_propriety']) . ' - "<b>' . $line2['rl_value'] . '</b>" ' . ' (<i>' . $tr . '</i>)';
					$this -> skoses -> log_insert($c, $th, 'ADDT', $desc);
					
					//$this->skoes->
				}		
				
			break;
			
			default:
			/* Load model */
			$this -> load -> model("skoses");
			$this -> cab(0);
			
			$data = $this -> skoses -> le($c);
			$data['form'] = $this -> skoses -> form_concept_tg($th, $c, 'TG');
			$this -> load -> view('thesa/view/concept_mini', $data);
			
			$action = get("action");
			$tg = get("tg");
			if ((strlen($action) > 0) and (strlen($tg) > 0)) {
				$this -> skoses -> assign_as_narrower($tg, $c, $th, 0);
				$this -> load -> view('header/close', null);
				//$this->skoes->
			}
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
		$tela = '<h1>'.msg('image_upload').'</h1>';
		$tela .= '<p>'.msg('image_upload_info').'</p>';
		$tela .= form_open_multipart() . cr();
		$tela .= '<!-- MAX_FILE_SIZE deve preceder o campo input -->
		<input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
		<!-- O Nome do elemento input determina o nome da array $_FILES -->
		Enviar esse arquivo: <input name="userfile" type="file" class="control-form" />
		<input type="submit" value="Enviar arquivo" />' . cr();
		$tela .= '</form>' . cr();
		
		if (isset($_FILES['userfile']['tmp_name'])) {
			$fl = $_FILES['userfile']['name'];
			
			/* ACERVO */
			$temp = '_acervo';
			dircheck($temp);
			
			/* IMAGES */
			$temp = '_acervo/image';
			dircheck($temp);
			
			/* IMAGES - DATA */
			$temp = '_acervo/image/' . date("Y");
			dircheck($temp);
			
			/* IMAGES - DATA */
			$temp = '_acervo/image/' . date("Y") . '/' . date("m") . '/';
			dircheck($temp);
			
			$img = strzero($c, 7);
			$ext = substr($fl, strlen($fl) - 4, 5);
			$ext = troca($ext, '.', '');
			
			$uploadfile = $temp . 'img-' . $img . '-' . substr(checkpost_link($img), 2, 10) . '.' . $ext;
			$ext = strtolower($ext);
			switch($ext) {
				case 'png' :
					$ok = 1;
				break;
				case 'jpg' :
					$ok = 1;
				break;
				default :
				$ok = 0;
				echo message(msg('ERRO #515').' <b>"'. $ext.'"</b>',3);
			}
			if ($ok == 1) {
				if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
					echo "Arquivo válido e enviado com sucesso.\n";
					$this -> skoses -> image_concept($c, $uploadfile);
					$tela = 'Sucesso!';
					$tela .= $this -> load -> view('wclose');
				} else {
					echo "Possível ataque de upload de arquivo!\n";
				}
			}
			
		}
		
		$data['content'] = $tela;
		$data['title'] = '';
		$this -> load -> view('content', $data);
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
				$this->skoses->th_update($th);
			}
			$id = $this -> skoses -> concept_create($t, $th);
			$this -> skoses -> log_insert($id, $th, $act, $desc);
		}
		redirect(base_url('index.php/thesa/c/' . $id));
	}
	
	function concept_add() {
		$this -> cab();
		$id = $_SESSION['skos'];		
		$data = $this -> skoses -> le_skos($id);
		
		/* Checa idiomas de entrada */
		$this->skoses->check_language_setup($id);
		
		$this -> load -> view('thesa/view/thesaurus', $data);
		$data['pg'] = 2;
		//$this -> load -> view('skos/skos_nav', $data);
		$this -> load -> view('thesa/header/navbar_tools', null);
		
		$form = new form;
		$cp = array();
		array_push($cp, array('$A3', '', msg('terms_add'), False, False));
		array_push($cp, array('$T80:8', '', msg('terms'), True, False));
		array_push($cp, array('$M', '', '<font class="small">' . msg('terms_info') . '</font>', False, False));
		$sql = $this->skoses->skoes_th_idiomas($id);
		array_push($cp, array('$QC lg_code:lg_language:'.$sql, '', msg('language'), True, False));
		array_push($cp, array('$C', '', msg('Lowercase'), False, False));
		array_push($cp, array('$B8', '', msg('save'), False, False));
		$tela = $form -> editar($cp, '');
		
		if ($form -> saved > 0) {
			$lc = splitx(';',get("dd3"));
			$data['content'] = '';
			for($r=0;$r < count($lc);$r++)
			{
				$data['content'] .= $this -> skoses -> incorpore_terms(get("dd1"), $id, $lc[$r], '');
			}
			$data['title'] = '';
			$this -> load -> view('content', $data);
		} else {
			$data['content'] = '<div class="row">';
			$data['content'] .= '<div class="col-md-8">'.$tela.'</div>';
			$data['content'] .= '<div class="col-md-3"><br/><h4>'.msg('help_info').'</h4>'.msg('concept_add_infor').'</div>';
			$data['content'] .= '</div>';
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
				$this->skoses->th_update($th);
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
		
		$sx = '<h1>' . $data['rl_value'] . '<sup>('.$data['rl_lang'].')</sup></h1>';
		$sx .= '<form method="post">';
		$sx .= '<ul style="list-style-type: none;">';
		$terms = array_merge($data['terms_pref'],$data['terms_al'], $data['terms_hd'], $data['terms_ge']);
		
		for ($r = 0; $r < count($terms); $r++) {
			$line = $terms[$r];
			$rd = '<input type="radio" name="dd5" value="' . $line['ct_term'] . '">';
			$sx .= '<li>' . $rd . ' ' . $line['rl_value'] . '<sup>('.$line['rl_lang'].')</sup></li>';
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
				$tela .= redirect2($url, 2);
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
	
	function terms_from_to($id = '', $exp = '', $p = '') {
		$this -> load -> model('skoses');
		if (strlen($id) == 0) {
			$id = $_SESSION['skos'];
		}
		
		$tp = 1;
		
		switch ($exp) {
			case 'pdf' :
				switch($p) {
					case '' :
						redirect(base_url(PATH . 'terms_from_to/' . $id . '/' . $exp . '/1'));
					break;
					
					/******************************************************** 11111111111111111111 */
					case '1' :
						$this -> cab();
						$html = '<h1>' . msg('phase') . ' 1</h1>';
						$html .= '<div class="alert alert-info" role="alert">
						Aguarde! Processando registros
						<hr>
						Processando Glossário Html
						<hr>
						</div>
						<br>
						<br>
						';
						$html .= '<meta http-equiv="refresh" content="2; url=' . base_url(PATH . 'terms_from_to/' . $id . '/' . $exp . '/2') . '">';
						
						$txt = $this -> skoses -> glossario_html($id, 'txt');
						$this -> skoses -> save($id, '1', $txt);
						
						$data['content'] = $html;
						$data['title'] = 'Export to PDF';
						$this -> load -> view('content', $data);
						$this -> footer();
					break;
					
					/*************************************************************** 2 */
					case '2' :
						$this -> cab();
						$html = '<h1>' . msg('phase') . ' 2</h1>';
						$html .= '<div class="alert alert-info" role="alert">
						Aguarde! Processando registros
						<hr>
						Processando Glossário Html
						<hr>                                              											  
						</div>
						<br><br>';
						$html .= '<meta http-equiv="refresh" content="2; url=' . base_url(PATH . 'terms_from_to/' . $id . '/' . $exp . '/3') . '">';
						
						$txt = $this -> skoses -> glossario_alfabetico_html($id, 'txt');
						$this -> skoses -> save($id, '2', $txt);
						
						$data['content'] = $html;
						$data['title'] = 'Export to PDF';
						$this -> load -> view('content', $data);
						$this -> footer();
					break;
					case '3' :
						$this -> cab();
						$html = '<h1>' . msg('phase') . ' 3</h1>';
						$html .= '<div class="alert alert-info" role="alert">
						Aguarde! Processando registros
						<hr>
						Processando Glossario Alfabetico Hhtml
						<hr>                                                                                            
						</div>
						<br><br>';
						$html .= '<meta http-equiv="refresh" content="2; url=' . base_url(PATH . 'terms_from_to/' . $id . '/' . $exp . '/4') . '">';
						
						$txt = $this -> skoses -> ficha_terminologica_html($id, 'txt');
						$this -> skoses -> save($id, '3', $txt);
						
						$data['content'] = $html;
						$data['title'] = 'Export to PDF';
						$this -> load -> view('content', $data);
						$this -> footer();
					break;
					
					/************************************************************************************/
					case '4' :
						$this -> cab();
						$html = '<h1>' . msg('phase') . ' 4</h1>';
						$html .= '<div class="alert alert-info" role="alert">
						Aguarde! Processando registros - Mapa conceitual
						</div>';
						
						$html .= $this -> skoses -> le_tree($id, 'txt');
						//$this -> skoses -> save($id, '4', $txt);
						if (!strpos($html,'ERROR: ['))
						{
							$html .= '<meta http-equiv="refresh" content="2; url=' . base_url(PATH . 'terms_from_to/' . $id . '/' . $exp . '/9') . '">';				
						} else {
							$html = '<h2>Erros</h2>
							Foram localizados erros no relacionamento de termos
							<hr>
							<a class="btn btn-warning" href="' . base_url(PATH . 'terms_from_to/' . $id . '/' . $exp . '/9').'">Continuar</a>'.'<hr>'.$html;
						}
						
						$txt = $this -> skoses -> le_tree_sistematic($id);
						$this -> skoses -> save($id, '5', $txt);
						
						$data['content'] = $html;
						$data['title'] = 'Export to PDF';
						$this -> load -> view('content', $data);
						$this -> footer();
					break;
					
					
					case '9' :
						$this -> cab();
						$html = $this -> skoses -> make_pdf($id);
						$data['content'] = $html;
						$data['title'] = '';
						$this -> load -> view('content', $data);
					break;
					
					default:
					/* fim */
				}
			break;
			
			case 'csv' :
				$arquivo = "thesa_" . strzero($id, 4) . '_' . date("Ymd") . ".csv";
				// Configurações header para forçar o download
				header("Expires: Mon, " . gmdate("D,d M YH:i:s") . " GMT");
				header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
				header("Cache-Control: no-cache, must-revalidate");
				header("Pragma: no-cache");
				header("Content-type: application/x-msexcel");
				header("Content-Disposition: attachment; filename=\"{$arquivo}\"");
				header("Content-Description: PHP Generated Data");
				echo utf8_decode($this -> skoses -> from_to($id, ';', '"'));
				return ('');
			break;
			
			case 'txt' :
				$arquivo = "thesa_" . strzero($id, 4) . '_' . date("Ymd") . ".txt";
				// Configurações header para forçar o download
				header("Expires: Mon, " . gmdate("D,d M YH:i:s") . " GMT");
				header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
				header("Cache-Control: no-cache, must-revalidate");
				header("Pragma: no-cache");
				header("Content-type: text/html");
				header("Content-Disposition: attachment; filename=\"{$arquivo}\"");
				header("Content-Description: PHP Generated Data");
				echo($this -> skoses -> from_to($id, '=>', ''));
			break;

			case 'txtb' :
				$arquivo = "thesa_" . strzero($id, 4) . '_' . date("Ymd") . ".txt";
				// Configurações header para forçar o download
				header("Expires: Mon, " . gmdate("D,d M YH:i:s") . " GMT");
				header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
				header("Cache-Control: no-cache, must-revalidate");
				header("Pragma: no-cache");
				header("Content-type: text/html");
				header("Content-Disposition: attachment; filename=\"{$arquivo}\"");
				header("Content-Description: PHP Generated Data");
				echo($this -> skoses -> from_to($id, '=>', '', True));
			break;			
			
			case 'skos' :
				$arquivo = "thesa_" . strzero($id, 4) . '_' . date("Ymd") . ".xml";
				// Configurações header para forçar o download
				header("Expires: Mon, " . gmdate("D,d M YH:i:s") . " GMT");
				header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
				header("Cache-Control: no-cache, must-revalidate");
				header("Pragma: no-cache");
				header("Content-type: text/xml");
				header("Content-Disposition: attachment; filename=\"{$arquivo}\"");
				header("Content-Description: PHP Generated Data");
				echo($this -> skoses -> skos_xml($id));
			break;			
			
			case 'json' :
				$arquivo = "thesa_" . strzero($id, 4) . '_' . date("Ymd") . ".txt";
				// Configurações header para forçar o download
				//header("Expires: Mon, " . gmdate("D,d M YH:i:s") . " GMT");
				//header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
				//header("Cache-Control: no-cache, must-revalidate");
				//header("Pragma: no-cache");
				//header("Content-type: text/html");
				//header("Content-Disposition: attachment; filename=\"{$arquivo}\"");
				//header("Content-Description: PHP Generated Data");
				$rst = $this -> skoses -> from_to($id, ':', '"');
				$rst = '{' . troca($rst, chr(13) . chr(10), '};{') . '"fim":"fim"}';
				$rst = troca($rst, '};{', '},' . cr() . '{');
					echo $rst;
				break;
				case 'xml' :
					$arquivo = "thesa_" . strzero($id, 4) . '_' . date("Ymd") . ".xml";
					// Configurações header para forçar o download
					//header("Expires: Mon, " . gmdate("D,d M YH:i:s") . " GMT");
					//header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
					//header("Cache-Control: no-cache, must-revalidate");
					//header("Pragma: no-cache");
					//header("Content-type: text/html");
					//header("Content-Disposition: attachment; filename=\"{$arquivo}\"");
					//header("Content-Description: PHP Generated Data");
					$rst = $this -> skoses -> from_to_xml($id);
					echo $rst;
				break;                
				default :
				echo mst($this -> skoses -> from_to($id, '=>', ''));
				return ('');
			break;
		}
	}
	
	function ajax($id = '', $id2 = '', $refresh = '0') {
		$this -> load -> model('skoses');
		
		switch($id) {
			case 'collaborators_add' :
				$refresh = '0';
				$email = get("dd1");
				$type = get("dd2");
				$th = $_SESSION['skos'];
				$refresh = $this -> skoses -> th_collabotors_add($email, $th, $type);
			break;
			
			case 'collaborators_del':
				$this->cab(0);
				$data['content'] = $this->skoses->th_collabotors_del($id2,$refresh);
				$data['title'] = '';
				$this->load->view('content',$data);
			break;
		}
		if ($refresh == '1') {
			echo ' <meta http-equiv="refresh" content="0">';
		}
	}
	
	function tools($fc = '') {
		$this -> load -> model("tools");
		$id = $this->skoses->th();
		$data = $this -> skoses -> le_skos($id);
		$this -> cab($data);		
		$this -> load -> view('thesa/view/thesaurus', $data);
		$this -> load -> view('thesa/header/navbar_tools', null);
		$tela = $this -> tools -> form();
		
		$data['content'] = $tela;
		$data['title'] = '';
		$data['fluid'] = true;
		$this -> load -> view('content', $data);
		
		$this -> footer();
	}
	
	function search($id = '') {
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
		$tela .= $this -> load -> view('thesa/header/navbar_tools', null, true);
		$term = get("form_search");
		if (strlen($term) > 0) {
			$tela .= $this -> skoses -> search_term($term, $id);
		}
		
		$data['content'] = $tela;
		$data['title'] = '';
		$this -> load -> view('content', $data);
		
	}
	
	function contact() {
		$this -> cab();
		$this -> load -> view('thesa/contact', null);
		$this -> footer();
	}
	
	function term_grapho($th) {
		$this -> cab();
		
		$data['id'] = $th;
		//$this->load->view('grapho/mind_tree',null);
		$this -> load -> view('grapho/mind_map', $data);
		//$this -> load -> view('grapho/mind_map_full', $data);
	}
	
	function admin_thesauros($id='')
	{
		if (perfil("#ADM") < 1)
		{
			redirect(base_url('index.php/main'));
		}
		$this->load->model("skoses");
		$this -> cab();
		
		$data['content'] = $this->skoses->th_list();
		$this->load->view('content',$data);
		$this->footer();
	}
	
	function languages()
	{
		$this->cab();
		$id = $_SESSION['skos'];		
		$data = $this -> skoses -> le_skos($id);
		$this -> load -> view('thesa/view/thesaurus', $data);
		$data['pg'] = 2;
		//$this -> load -> view('skos/skos_nav', $data);
		$this -> load -> view('thesa/header/navbar_tools', null);
		
		
		/********************
		* SEGURANÇA
		*/
		if (!((isset($_SESSION['id']) and ($_SESSION['id']) == $data['pa_creator']) and ($data['id_pa'] == $id)))
		{		
			$tela = message(msg('Unauthorized_access'),3);
			$data['title'] = '';
			$data['content'] = $tela;
			$this -> load -> view('content', $data);
			$this->footer();
			return("");
		}
		
		if (isset($_SESSION['skos']))
		{
			$th = $_SESSION['skos'];
			
			$data['title'] = '';
			$data['content'] = $this->skoses->th_languages($th);
			$this->load->view('content',$data);					
			$this->footer();
		} else {
			redirect(base_url(PATH));
		}
		
	}
	function admin($act='',$d1='',$d2='',$d3='')
	{
		$this->cab();
		$id = $_SESSION['skos'];
		$data = $this -> skoses -> le_skos($id);
		$this -> load -> view('thesa/view/thesaurus', $data);
		$data['pg'] = 2;
		//$this -> load -> view('skos/skos_nav', $data);
		$this -> load -> view('thesa/header/navbar_tools', null);
		
		$data['title'] = '';
		$data['content'] = $this->skoses->admin($act,$d1,$d2,$d3);
		$this->load->view('content',$data);	
		$this->footer();
	}
}
?>