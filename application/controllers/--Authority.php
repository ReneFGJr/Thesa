<?php
class Authority extends CI_Controller {

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
		$this -> cab(2);

		$this -> load -> view("ca/welcome", null);

		$this -> load -> view("ca/ca_home", null);
		//redirect(base_url('index.php/skos/myskos'));
	}
	
	function find() {
		$this -> cab();

		$this -> load -> view("ca/welcome", null);

		$this -> load -> view("ca/ca_home", null);
		//redirect(base_url('index.php/skos/myskos'));
	}	

	function cab($nb = 1) {
		$this -> load -> view('ca/ca_header', null);
		if ($nb > 0) {
			$this -> load -> view("ca/ca_cab", null);
			$this -> load -> view('ca/ca_menu');
			if ($nb != 2) {
				$this -> load -> view("ca/ca_search");
			}
		}
	}

	function show_404() {
		$this -> cab(0);
		$this -> load -> view('skos/404', null);
	}

	function foot() {
		$this -> load -> view('header/footer', null);
	}

	function security() {
		$user = $this -> session -> userdata('user');
		$email = $this -> session -> userdata('email');
		$nivel = $this -> session -> userdata('nivel');
		if (round($nivel) < 1) {
			redirect(base_url('index.php/social/login'));
		}
	}

	/* LOGIN */
	function login($act = '') {
		$this -> cab(2);
		$data['email_ok'] = '';
		if (strlen($act) > 0) {
			$rs = $this -> validate($act);
			if ($rs == 1) {

				redirect(base_url('index.php/skos/'));
			}
		}

		$ok = $this -> validate();
		if ($ok == 1) {
			redirect(base_url('index.php/skos/myth'));
			exit ;
		} else {
			if (strlen(get("userName")) > 0)
				switch($ok) {
					case -1 :
						$data['email_ok'] = '<span class="btn alert-danger">' . msg("user_not_validaded") . '#' . $ok . '</span>';
						$link = ' | <a href="' . base_url('index.php/skos/user_revalid/?dd0=' . get("userName") . '&chk=' . checkpost_link(get("userName"))) . '" class="btn alert-danger">';
						$link .= msg('resend_validation');
						$link .= '</a>';
						$link .= '<br/><br/>';
						$data['email_ok'] .= $link;
						break;
					default :
						$data['email_ok'] = '<span class="btn alert-danger">' . msg("user_invalid_password") . '#' . $ok . '</span><br/><br/>';
						break;
				}
		}
		$this -> load -> view('skos/thesa_login', $data);

	}

	/* LOGIN SIGN UP */
	function login_sign_up() {
		$this -> load -> model('Authorities');
		$this -> cab(2);

		$nome = get("fullName");
		$email = get("email");
		$data = array();
		$data['email_ok'] = '';
		if (strlen($email) > 0) {
			$ok = validaemail($email);
			if ($ok == 1) {
				$ok = $this -> Authorities -> user_exist($email);
				if ($ok == 1) {
					$data['email_ok'] = '<span class="btn alert-danger">' . msg("email_already_inserted") . '</span>';
				} else {
					$data['email_ok'] = '<span class="btn alert-success">' . msg("email_inserted") . '</span>';
					$this -> Authorities -> user_insert_temp($email, $nome);
					$this -> Authorities -> user_email_send($email, $nome, 'SIGNUP');
				}
			} else {
				$data['email_ok'] = '<span class="btn alert-danger">' . msg("email_error") . '</span>';
			}
		}

		$this -> load -> view('skos/thesa_sign_up', $data);
	}

	/* EDIcaO DAS CLASSE */
	function classe() {
		$this -> load -> model('Authorities');

		$this -> cab();
		$tela = $this -> Authorities -> form(1, 1);
		$data['content'] = $tela;
		$data['title'] = '';
		$this -> load -> view('content', $data);
	}

	function myth() {
		$this -> load -> model('Authorities');
		$this -> cab(1);
		$us = $_SESSION['id'];
		$tela = $this -> Authorities -> myAuthorities($us);
		$data['content'] = $tela;
		$data['content'] .= $this -> load -> view('skos/thesa_btn_new', $data, true);

		$data['title'] = msg('my_thesauros');
		$this -> load -> view('content', $data);
	}

	function th_new() {
		$us = $_SESSION['id'];

		/* Load model */
		$this -> load -> model("Authorities");

		$this -> cab(1);

		$cp = $this -> Authorities -> cp_th_new($us);
		$form = new form;
		$form -> table = $this -> Authorities -> table_thesaurus;
		$form -> cp = $cp;
		$form -> id = 0;

		$data['content'] = $form -> editar($cp, $this -> Authorities -> table_thesaurus);
		$data['title'] = msg('my_thesauros');
		$this -> load -> view('content', $data);

		if ($form -> saved > 0) {

			$this -> Authorities -> th_assosiation_users();

			redirect(base_url('index.php/skos/myth/'));
		}

	}

	function thesa() {
		$this -> load -> model('Authorities');

		$this -> cab();
		$us = 1;
		$tela = $this -> Authorities -> myAuthorities(0);
		$data['content'] = $tela;
		$data['title'] = msg('my_thesauros');
		$this -> load -> view('content', $data);
	}

	function select($id = 0, $chk = '') {
		$this -> load -> model('Authorities');
		if (checkpost_link($id) == $chk) {
			$this -> Authorities -> Authorities_select($id);
		}
		redirect(base_url('index.php/skos/terms/' . $id));
	}

	function show($id = '', $chk = '', $ltr = '') {
		$this -> load -> model('Authorities');
		$this -> cab();

		if (strlen($id) == 0) {
			$id = $_SESSION['skos'];
		}

		$data = $this -> Authorities -> le_skos($id);
		$this -> load -> view('skos/view', $data);

		$this -> load -> view('skos/skos_nav', $data);

		$tela = $this -> Authorities -> termos_pg($id);
		$tela = '<div class="row">';
		$tela = '	<div class="col-md-9">';

		/* Tree */
		$tela .= $this -> load -> view('skos/search', null, true);

		$tela .= '	</div>';

		$tela .= '	<div class="col-md-3">';
		$tela .= $this -> Authorities -> thesaurus_resume($id);
		$tela .= '<a href="' . base_url('index.php/skos/thes/') . '" class="btn btn-secondary" style="width: 100%;">' . msg('Conceitual map') . '</a>' . cr();
		$tela .= '<br><br>';
		$tela .= '<a href="' . base_url('index.php/skos/thrs/') . '" class="btn btn-secondary" style="width: 100%;">' . msg('Report Thesaurus') . '</a>' . cr();
		$tela .= '	</div>';
		$tela .= '</div>';
		$data['content'] = $tela;
		$this -> load -> view('content', $data);
	}

	function concept_add() {
		$this -> load -> model('Authorities');
		$this -> cab();
		$id = $_SESSION['skos'];
		$data = $this -> Authorities -> le_skos($id);
		$this -> load -> view('skos/view', $data);
		$data['pg'] = 2;
		$this -> load -> view('skos/skos_nav', $data);

		$form = new form;
		$cp = array();
		array_push($cp, array('$A3', '', msg('terms'), False, False));
		array_push($cp, array('$T80:8', '', msg('terms'), True, False));
		array_push($cp, array('$M', '', '<font class="small">' . msg('terms_info') . '</font>', False, False));
		array_push($cp, array('$Q lg_code:lg_language:select * from language where lg_active = 1 order by lg_order', '', msg('language'), True, False));
		array_push($cp, array('$C', '', msg('Lowercase'), False, False));
		array_push($cp, array('$B8', '', msg('save'), False, False));
		$tela = $form -> editar($cp, '');

		if ($form -> saved > 0) {
			$lc = get("dd4");
			$data['content'] = $this -> Authorities -> incorpore_terms(get("dd1"), $id, get("dd3"), $lc);
			$data['title'] = '';
			$this -> load -> view('content', $data);
		} else {
			$data['content'] = $tela;
			$data['title'] = '';
			$this -> load -> view('content', $data);
		}
		$this -> load -> view('header/footer', null);
	}

	function concept_subordinate($th = '', $chk = '', $idt) {

		$this -> load -> model('Authorities');
		$this -> cab();
		if (strlen($th) == 0) {
			$th = $_SESSION['skos'];
		}

		$data = $this -> Authorities -> le_skos($th);
		$this -> load -> view('skos/view', $data);

		$data = $this -> Authorities -> le_term($idt, $th);
		$data['pg'] = 1;
		$this -> load -> view('skos/skos_nav_term', $data);
		if (strlen(trim(trim($data['ct_concept']))) == 0) {
			$data['action'] = '<a href="' . base_url('index.php/skos/concept_create/' . $data['lt_thesauros'] . '/' . checkpost_link($data['lt_thesauros']) . '/' . $data['id_lt']) . '" class="btn btn-primary">' . msg('Term_create_concept') . '</a></li>';
		}
		$this -> load -> view('skos/view_term', $data);

		$this -> Authorities -> th_concept_subordinate($idt, $th);
		$tela = '';
		$data['content'] = $tela;
		$this -> load -> view('content', $data);

	}

	/***************************************************************************** Termo equivalente */
	function te($c = '') {
		$c = round($c);
		$th = $_SESSION['skos'];

		/* Load model */
		$this -> load -> model("Authorities");
		$this -> cab(0);

		$data = $this -> Authorities -> le($c);
		$data['form'] = $this -> Authorities -> form_concept($th, $c, 'TE');
		$this -> load -> view('skos/view_concept_mini', $data);

		$action = get("action");
		$desc = get("tm");
		$tr = get("tr");

		if ((strlen($action) > 0) and (strlen($tr) > 0) and (strlen($desc) > 0)) {
			echo 'SAVED';
			$this -> Authorities -> assign_as_propriety($c, $th, $tr, $desc);
			$this -> load -> view('header/close', null);
			//$this->skoes->
		}

	}

	/***************************************************************************** Definição de conceito */
	function tf($c = '') {
		$c = round($c);
		$th = $_SESSION['skos'];

		/* Load model */
		$this -> load -> model("Authorities");
		$this -> cab(0);

		$data = $this -> Authorities -> le($c);
		$data['form'] = $this -> Authorities -> form_concept_tf($th, $c);
		$this -> load -> view('skos/view_concept_mini', $data);

		$action = get("action");
		$texto = get("nt");
		$idioma = get("tt");
		$tr = get("tr");

		if ((strlen($action) > 0) and (strlen($tr) > 0) and (strlen($idioma) > 0) and (strlen($tr) > 0)) {

			$this -> Authorities -> assign_as_note($c, $th, $tr, $texto, $idioma);
			$this -> load -> view('header/close', null);
			//$this->skoes->
		}

	}

	/***************************************************************************** TG Geral */
	function tg($c = '') {
		$c = round($c);
		$th = $_SESSION['skos'];

		/* Load model */
		$this -> load -> model("Authorities");
		$this -> cab(0);

		$data = $this -> Authorities -> le($c);
		$data['form'] = $this -> Authorities -> form_concept_tg($th, $c);
		$this -> load -> view('skos/view_concept_mini', $data);

		$action = get("action");
		$tg = get("tg");
		if ((strlen($action) > 0) and (strlen($tg) > 0)) {
			echo $this -> TG;
			$this -> Authorities -> assign_as_narrower($tg, $c, $th, 0);
			$this -> load -> view('header/close', null);
			//$this->skoes->
		}

	}

	/***************************************************************************** Conecpt */
	function th($th = '') {
		if (strlen($th) == 0) {
			$th = $_SESSION['skos'];
		}
		/* Load model */
		$this -> load -> model("Authorities");
		$this -> cab();
		$data['file'] = $th;
		//$this->load->view('grapho/mind_tree',null);
		//$this->load->view('grapho/mind_map',null);
		$this -> load -> view('grapho/mind_map_full', $data);
		//

	}

	function th_edit($id = '', $chk = '') {
		/* Load model */
		$this -> load -> model("Authorities");

		$this -> Authorities -> th_assosiation_users();

		$this -> cab(1);

		$data = $this -> Authorities -> le_skos($id);

		if (count($data) > 0) {
			if ((isset($_SESSION['id']) and ($_SESSION['id']) == $data['pa_creator'])) {
				$cp = $this -> Authorities -> cp_th($id);
				$form = new form;
				$form -> table = $this -> Authorities -> table_thesaurus;
				$form -> cp = $cp;
				$form -> id = $id;

				$data['content'] = $form -> editar($cp, $this -> Authorities -> table_thesaurus);
				$data['title'] = 'my_thesauros';
				$this -> load -> view('content', $data);

				if ($form -> saved > 0) {
					if (strlen($id) > 0) {
						redirect(base_url('index.php/skos/myth/' . $id));
					} else {
						redirect(base_url('index.php/skos/myth'));
					}
				}

				$msg = '';

				/* Incluir colaboradores */
				$emailCollaborator = get("emailCollaborator");
				$btn_emailCollaborator = get("btn_emailCollaborator");
				if (strlen($btn_emailCollaborator) > 0) {
					$ok = validaemail($emailCollaborator);
					if ($ok == 1) {
						$ok = $this -> Authorities -> user_exist($emailCollaborator);
						if ($ok == 0) {
							$msg .= '<span class="btn alert-danger">' . msg('email_not_exist') . '</span>';
						} else {
							$user_c = $this -> Authorities -> line['id_us'];
							$msg .= '<span class="btn alert-success">' . msg('collaborator_insered') . '</span>';
							$this -> Authorities -> user_thesa($id, $user_c, 'INS');
						}
					} else {
						$msg .= '<span class="btn alert-danger">' . msg('email_invalid') . '</span>';
					}

				}

				/* Colaboradores */
				$msg = $this -> Authorities -> th_collaborators($id) . $msg;
				$data['content'] = $msg;
				$this -> load -> view('skos/thesa_users', $data);
			}
		}
		$this -> load -> view('header/footer', null);
	}

	/***************************************************************************** TR Relacionado */
	function tr($c = '') {
		$c = round($c);
		$th = $_SESSION['skos'];

		/* Load model */
		$this -> load -> model("Authorities");
		$this -> cab(0);

		$data = $this -> Authorities -> le($c);
		$data['form'] = $this -> Authorities -> form_concept_tr($th, $c);
		$this -> load -> view('skos/view_concept_mini', $data);

		$action = get("action");
		$tg = get("tg");
		$tgr = get("tgr");
		if ((strlen($action) > 0) and (strlen($tg) > 0) and (strlen($tgr) > 0)) {
			echo $this -> TG;
			$this -> Authorities -> assign_as_relation($tg, $c, $th, $tgr);
			$this -> load -> view('header/close', null);
			//$this->skoes->
		}

	}

	/***************************************************************************** Termo oculto */
	function tz($c = '') {
		$c = round($c);
		$th = $_SESSION['skos'];

		/* Load model */
		$this -> load -> model("Authorities");
		$this -> cab(0);

		$data = $this -> Authorities -> le($c);
		$data['form'] = $this -> Authorities -> form_concept($th, $c, 'TH');
		$this -> load -> view('skos/view_concept_mini', $data);

		$action = get("action");
		$desc = get("tm");
		$tr = get("tr");

		if ((strlen($action) > 0) and (strlen($tr) > 0) and (strlen($desc) > 0)) {
			echo 'SAVED';
			$this -> Authorities -> assign_as_propriety($c, $th, $tr, $desc);
			$this -> load -> view('header/close', null);
			//$this->skoes->
		}
	}

	/***************************************************************************** Termo oculto */
	function tx($c = '') {
		$c = round($c);
		$th = $_SESSION['skos'];

		/* Load model */
		$this -> load -> model("Authorities");
		$this -> cab(0);

		$data = $this -> Authorities -> le($c);
		$data['form'] = $this -> Authorities -> form_concept($th, $c, 'FE');
		$this -> load -> view('skos/view_concept_mini', $data);

		$action = get("action");
		$desc = get("tm");
		$tr = get("tr");

		if ((strlen($action) > 0) and (strlen($tr) > 0) and (strlen($desc) > 0)) {
			echo 'SAVED';
			$this -> Authorities -> assign_as_propriety($c, $th, $tr, $desc);
			$this -> load -> view('header/close', null);
			//$this->skoes->
		}

	}

	/***************************************************************************** Termo oculto */
	function glossario($th = '') {
		$th = round($_SESSION['skos']);

		/* Load model */
		$this -> load -> model("Authorities");
		$this -> cab(0);

		$data['content'] = $this -> Authorities -> glossario($th);
		$data['title'] = '';
		$this -> load -> view('content', $data);
	}

	/***************************************************************************** Conecpt */
	function json($id = '') {
		/* Load model */
		$this -> load -> model("Authorities");
		$this -> cab();

		$this -> Authorities -> to_json($id);

	}

	/***************************************************************************** Conecpt */
	function ti($c = '', $th = '') {
		/* Load model */
		$this -> load -> model("Authorities");
		$this -> cab();
		$t = troca(get("dd1"), chr(13), ';');
		$t = troca($t, chr(10), '');
		$t = splitx(';', $t);
		$lang = get("dd2");
		if (strlen($lang) == 0) { $lang = 'por';
		}
		for ($r = 0; $r < count($t); $r++) {
			$ta = $t[$r];
			$type = 27;
			$idt = $this -> Authorities -> terms_add($ta, $th, $lang);
			$this -> Authorities -> association_term_th($ta, $lang, $th);
			$this -> Authorities -> association_term_concept($idt, $c, $th, $type);
		}
		redirect(base_url('index.php/skos/c/' . $c));
	}

	/***************************************************************************** Conecpt */
	function c($c = '', $proto = '') {
		$this -> load -> model("Authorities");

		$data = $this -> Authorities -> le($c);
		if (count($data) == 0) { redirect(base_url('index.php/skos/error/c'));
		}
		$data = $this -> Authorities -> le_c($data['id_c'], $data['ct_th']);
		if (count($data) == 0) { redirect(base_url('index.php/skos/error/c'));
		}

		switch ($proto) {
			case 'xml' :
				header('Content-type: text/xml');
				$this -> Authorities -> xml($data);
				break;
			default :
				/* CAB */
				$this -> cab();

				$this -> load -> view('skos/thesa_thesaurus', $data);
				$this -> load -> view('skos/thesa_breadcrumb', $data);

				$this -> load -> view("skos/thesa_schema", $data);
				$this -> load -> view("skos/thesa_concept", $data);
				break;
		}

		//redirect(base_url('index.php/skos/myskos'));
	}

	function te_remove($id = '', $chk = '') {
		$this -> load -> model("Authorities");
		$this -> Authorities -> te_remote($id);
	}

	function cedit($c = '', $th = '') {

		/* Load model */
		$edit = true;
		$this -> load -> model("Authorities");
		$this -> cab();

		/* RECUPERA SCHEMA */
		if (strlen($th) == 0) {
			$th = $_SESSION['skos'];
		}

		/* LE DADOS SOBRE O SCHEMA */
		$data = $this -> Authorities -> le_skos($th);
		$this -> load -> view('skos/view', $data);
		$data['pg'] = 4;
		$data['editar'] = 1;

		/* menu */
		$this -> load -> view('skos/skos_nav', $data);

		/* Recupera informações sobre o Concecpt */
		$data2 = $this -> Authorities -> le_c($c, $th);
		$this -> load -> view('skos/view_concept', $data2);

	}

	function c2($c = '', $th = '') {

		/* Recupera informações sobre o Concecpt */
		$data2 = $this -> Authorities -> le_c($c, $th);
		$data['th'] = $this -> Authorities -> le_tree_js($c, $data);

		$data['tree'] = $this -> load -> view('skos/skos_concept_tree_view', $data, true);
		/* assigned */
		$dd10 = get("dd10");
		$dd11 = get("dd11");
		$dd12 = get("dd12");
		switch($dd11) {
			case 'assign_as_narrower' :
				if ($dd12 == $c) {
					$tm = 0;
					$this -> Authorities -> assign_as_narrower($c, $dd10, $th, $tm);
				}
				break;
		}

		print_r($data2);
		$tela = $this -> load -> view('skos/view_concept', $data2, true);
		$sx = '';
		$sx .= $this -> Authorities -> concept_show($c);

		if ($edit) {
			$dt = $this -> Authorities -> le_tree_not_assign($th, $data);

			/* ADD NEW TERM */
			$sx .= $this -> load -> view('skos/skos_term_add', $data2, true);

			/* BROADER */
			$sx .= '<form action="' . base_url('index.php/skos/c/' . $c) . '">';
			$sx .= '<font class="middle">' . msg('select_a_concept_to_assign') . '</font>';
			$sx .= '<input type="hidden" value="assign_as_narrower" name="dd11">';
			$sx .= '<input type="hidden" value="' . $c . '" name="dd12">';
			$sx .= '<select name="dd10" size="15" class="middle" style="width: 100%;">';
			for ($r = 0; $r < count($dt); $r++) {
				$line = $dt[$r];
				if ($line['c1'] != $c) {
					$sx .= '<option value="' . $line['c1'] . '">' . $line['rl_value'] . '</option>' . cr();
				}
			}
			$sx .= '</select>' . cr();
			$sx .= '<br>' . cr();
			$sx .= '<br>' . cr();
			$sx .= '<input type="submit" value="' . msg('assign_as_narrower') . ' >>>" class="btn btn-primary" style="width: 100%">' . cr();
			$sx .= '</form>';

			/* */

			$data['form_terms'] = $sx;
			//$tela .= $this -> load -> view('skos/view_concept_add_broader', $data, true);
		}

		$data['content'] = $tela;
		$this -> load -> view('content', $data);
	}

	/***************************************************************************** term */
	function t($term = '') {

	}

	function edc($idc = 0, $tp = '') {
		/* Load model */
		$this -> load -> model("Authorities");
		$this -> load -> view('header/header');
		$form = new form;

		$cp = array();
		array_push($cp, array('$H8', '', '', False, True));
		array_push($cp, array('$S80', '', msg('Term'), True, True));
		array_push($cp, array('$Q id_lang:lang_name:select * from wese_language order by lang_ordem', '', msg('Language'), True, True));
		array_push($cp, array('$Q id_sh:sh_name:select * from wese_scheme order by sh_name', '', msg('sh_name'), True, True));
		array_push($cp, array('$B8', '', msg('add') . ' >>', False, True));

		$tela = $form -> editar($cp, '');

		if ($form -> saved > 0) {
			$dominio = get("dd3");
			$concept = $idc;
			$term = $this -> Authorities -> insere_termo(get("dd1"), get("dd2"));
			$this -> Authorities -> create_concept($term, $dominio);

			echo '
					<script>
							window.opener.location.reload();
							close(); 
					</script>
					';
		}

		$data['content'] = $tela;
		$this -> load -> view('content', $data);
	}

	function edn($idn = 0, $tp = '') {
		/* Load model */
		$this -> load -> model("Authorities");
		$this -> load -> view('header/header');
		$form = new form;
		$form -> id = $idn;
		$cp = array();
		array_push($cp, array('$H8', 'id_wn', '', False, True));
		array_push($cp, array('$T80:12', 'wn_note', msg('Comment'), True, True));
		array_push($cp, array('$B8', '', msg('save') . ' >>', False, True));

		$tela = $form -> editar($cp, 'wese_note');

		if ($form -> saved > 0) {
			echo '
					<script>
							window.opener.location.reload();
							close(); 
					</script>
					';
		}

		$data['content'] = $tela;
		$this -> load -> view('content', $data);
	}

	function error($er = '') {
		$this -> cab();
		$data['title'] = 'Erro 510';
		$data['content'] = msg('concept_not_found');
		$this -> load -> view('skos/510', $data);
	}

	function ed($idc = 0, $tp = '') {
		/* Load model */
		$this -> load -> model("Authorities");
		$this -> load -> view('header/header');
		$form = new form;

		switch ($tp) {
			case 'ALT' :
				$cp = array();
				array_push($cp, array('$H8', '', '', False, True));
				array_push($cp, array('$S80', '', msg('Term'), True, True));
				array_push($cp, array('$Q id_lang:lang_name:select * from wese_language order by lang_ordem', '', msg('Language'), True, True));
				array_push($cp, array('$B8', '', msg('add') . ' >>', False, True));
				array_push($cp, array('$HV', '', 'ALT', True, True));
				break;
			case 'PREF' :
				$cp = array();
				array_push($cp, array('$H8', '', '', False, True));
				array_push($cp, array('$S80', '', msg('Term'), True, True));
				array_push($cp, array('$Q id_lang:lang_name:select * from wese_language order by lang_ordem', '', msg('Language'), True, True));
				array_push($cp, array('$B8', '', msg('add') . ' >>', False, True));
				array_push($cp, array('$HV', '', 'PREF', True, True));
				break;
			case 'HIDDEN' :
				$cp = array();
				array_push($cp, array('$H8', '', '', False, True));
				array_push($cp, array('$S80', '', msg('Term'), True, True));
				array_push($cp, array('$Q id_lang:lang_name:select * from wese_language order by lang_ordem', '', msg('Language'), True, True));
				array_push($cp, array('$B8', '', msg('add') . ' >>', False, True));
				array_push($cp, array('$HV', '', 'HIDDEN', True, True));
				break;
			case 'NOTE' :
				$cp = array();
				array_push($cp, array('$H8', '', '', False, True));
				array_push($cp, array('$T80:8', '', msg('Note'), True, True));
				array_push($cp, array('$B8', '', msg('add') . ' >>', False, True));
				array_push($cp, array('$HV', '', 'NOTE', True, True));
				break;
		}
		$tela = $form -> editar($cp, '');

		if ($form -> saved > 0) {
			$type = get("dd4");
			$concept = $idc;
			if (($tp == 'ALT') or ($tp == 'PREF') or ($tp == 'HIDDEN')) {
				$term = $this -> Authorities -> insere_termo(get("dd1"), get("dd2"));
				$idt = $this -> Authorities -> association_term($term, $concept, $type);
			}
			if (($tp == 'NOTE')) {
				$dd1 = get("dd1");
				$sql = "insert into wese_note (wn_id_c, wn_note) value ($idc,'$dd1')";
				$this -> db -> query($sql);
			}
			echo '
					<script>
							window.opener.location.reload();
							close(); 
					</script>
					';
		}

		$data['content'] = $tela;
		$this -> load -> view('content', $data);

		$this -> load -> view('header/footer', null);
	}

	/* Narrowed */
	function narrows_add($id = '') {
		/* Load model */
		$this -> load -> model("Authorities");

		$dd1 = utf8_decode($this -> input -> post('link'));
		$txt = '/thema/';
		$dd1 = substr($dd1, strpos($dd1, $txt) + strlen($txt), strlen($dd1));
		$dd1 = troca($dd1, '/', ';');
		$it = splitx(';', $dd1);

		$c1 = $this -> Authorities -> recupera_id_concept($it[1]);
		$c2 = $this -> Authorities -> recupera_id_concept($it[2]);

		if (($c1 > 0) and ($c2 > 0)) {
			$this -> Authorities -> narrows_link($c1, $c2);
		}
		$sx = '';
		$sx .= '
			<script>
				location.reload();
			</script>
			';
		echo $sx;
	}

	/* Concept */
	function concept_create($id, $chk = '', $t) {
		/* Load model */
		$this -> load -> model('Authorities');
		$th = $_SESSION['skos'];
		if ($id != $th) {
			echo 'OPS-402';
			exit ;
		}
		$this -> cab();

		$is_active = $this -> Authorities -> is_concept($t, $th);

		if ($is_active == 0) {
			$id = $this -> Authorities -> concept_create($t, $th);
			redirect(base_url('index.php/skos/c/' . $id));
		}

	}

	/* Terms */
	function term($th = '', $idt = '') {

		$this -> load -> model("Authorities");

		/* CAB */
		$this -> cab();

		$data = $this -> Authorities -> le_th($th);

		$data2 = $this -> Authorities -> le_term($idt, $th);
		$data = array_merge($data, $data2);

		$this -> load -> view('skos/thesa_thesaurus', $data);
		$this -> load -> view('skos/thesa_breadcrumb', $data);
		/******************************/

		if (strlen(trim(trim($data['ct_concept']))) == 0) {
			$data['action'] = '<a href="' . base_url('index.php/skos/concept_create/' . $data['lt_thesauros'] . '/' . checkpost_link($data['lt_thesauros']) . '/' . $data['id_rl']) . '" class="btn btn-primary" style="width: 100%;">' . msg('Term_create_concept') . '</a></li>';
			$data['action'] .= '<br/><br/><a href="' . base_url('index.php/skos/term_edit/' . $data['lt_thesauros'] . '/' . checkpost_link($data['lt_thesauros']) . '/' . $data['id_rl']) . '" class="btn btn-warning" style="width: 100%;">' . msg('Term_edit_concept') . '</a></li>';
			$data['action'] .= '<br/><br/><a href="' . base_url('index.php/skos/term_delete/' . $data['lt_thesauros'] . '/' . checkpost_link($data['lt_thesauros']) . '/' . $data['id_rl']) . '" class="btn btn-danger" style="width: 100%;">' . msg('Term_delete_concept') . '</a></li>';
		}
		$this -> load -> view('skos/view_term', $data);

		$tela = '';
		$data['content'] = $tela;
		$this -> load -> view('content', $data);

	}

	function term_delete($th = '', $chk = '', $idt = '', $act = '', $chk2 = '') {
		$this -> load -> model('Authorities');
		$this -> cab();

		if (strlen($th) == 0) {
			$th = $_SESSION['skos'];
		}
		$data = $this -> Authorities -> le_th($th);

		$data2 = $this -> Authorities -> le_term($idt, $th);

		if (count($data2) == 0) {
			redirect(base_url('index.php/skos/terms/' . $th));
		}

		$data = array_merge($data, $data2);

		$this -> load -> view('skos/thesa_thesaurus', $data);
		$this -> load -> view('skos/thesa_breadcrumb', $data);
		/******************************/

		$this -> load -> view('skos/view_term', $data);

		$chk3 = checkpost_link($th . 'DEL' . $idt);
		if ($chk2 == $chk3) {

			$rs = $this -> Authorities -> delete_term_from_th($th, $idt);

			if ($rs == 1) {
				$tela = $this -> load -> view('success', $data, true);
				$data['content'] = $tela;
				$this -> load -> view('content', $data);
			} else {
				$data['content'] = msg('item_already_deleted');
				$tela = $this -> load -> view('success', $data, true);
				$data['content'] = $tela;
				$this -> load -> view('content', $data);
			}

		} else {
			$data['link'] = base_url('index.php/skos/term_delete/' . $th . '/' . $chk . '/' . $idt . '/DEL/' . $chk3);
			$data['content'] = msg('delete_term_confirm');
			$tela = $this -> load -> view('confirm', $data, True);
			$data['content'] = $tela;
			$this -> load -> view('content', $data);
		}

		$this -> load -> view('header/footer', null);
	}

	function term_edit($th = '', $id2 = '', $idt = '') {
		$this -> load -> model('Authorities');
		$this -> cab();

		if (strlen($th) == 0) {
			$th = $_SESSION['skos'];
		}
		$data = $this -> Authorities -> le_th($th);

		$data2 = $this -> Authorities -> le_term($idt, $th);
		$data = array_merge($data, $data2);

		$this -> load -> view('skos/thesa_thesaurus', $data);
		$this -> load -> view('skos/thesa_breadcrumb', $data);
		/******************************/

		$this -> load -> view('skos/view_term', $data);

		$form = new form;
		$form -> id = $idt;
		$cp = $this -> Authorities -> cp_term();
		$data['content'] = $form -> editar($cp, $this -> Authorities -> table_terms);
		$data['title'] = '';
		$this -> load -> view('content', $data);

		$tela = '';
		$data['content'] = $tela;
		$this -> load -> view('content', $data);

		if ($form -> saved) {
			redirect(base_url('index.php/skos/term/' . $th . '/' . $idt));
		}
		$this -> load -> view('header/footer', null);
	}

	function terms($id = '', $ltr = '') {
		$this -> load -> model('Authorities');
		$this -> cab();

		if (strlen($id) == 0) {
			$id = $_SESSION['skos'];
		}

		$data = $this -> Authorities -> le_skos($id);
		$this -> load -> view('skos/thesa_thesaurus', $data);

		$tela = $this -> Authorities -> termos_pg($id);
		$tela .= '<div class="row">';
		$tela .= '	<div class="col-md-9">';
		$tela .= $this -> Authorities -> termos_show_letter($id, $ltr);
		$tela .= '	</div>';

		$tela .= '	<div class="col-md-3">';
		$tela .= $this -> Authorities -> thesaurus_resume($id);
		if ($this -> Authorities -> autho('', $id) == 1) {
			/* TERMO PERDIDOS */
			$tela .= $this -> Authorities -> acao_novos_termos($id);
			$tela .= $this -> Authorities -> acao_visualizar_glossario($id);

			$tela .= $this -> Authorities -> termos_sem_conceito($id, $ltr);
		}
		$tela .= '	</div>';
		$tela .= '</div>';
		$data['content'] = $tela;
		$this -> load -> view('content', $data);

		$this -> load -> view('header/footer', null);

	}

	/* Scheme */
	function thema_edit($scheme = '', $concept = '') {
		/* Load model */

		$this -> load -> model("Authorities");
		$this -> cab_scheme();

		/* Show list Terms */
		$data = array();
		$data['terms'] = $this -> Authorities -> concepts($scheme, $concept);
		$data['terms'] .= '<hr>';
		$data['terms'] .= $this -> Authorities -> concepts_no($scheme, $concept);
		if (strlen($concept) > 0) {
			$data['terms_content'] = $this -> Authorities -> concept($concept);
		} else {
			$data['terms_content'] = '';
		}

		$this -> load -> view('wese/term_drashboard', $data);

	}

	/* Scheme */
	function thes() {

		$th = $_SESSION['skos'];
		if (strlen($th) == 0) {
			redirect(base_url('index.php/skos'));
		}
		/* Load model */

		$this -> load -> model("Authorities");
		$this -> cab();
		$data = $this -> Authorities -> le_tree($th);

		$data = array();

	}

	function thrs() {

		$th = $_SESSION['skos'];
		if (strlen($th) == 0) {
			redirect(base_url('index.php/skos'));
		}
		/* Load model */

		$this -> load -> model("Authorities");
		$this -> cab();
		$data = $this -> Authorities -> le_report($th);

		$data = array();

	}

	function scheme($scheme = '') {
		/* Load model */

		$this -> load -> model("Authorities");
		$this -> cab();

		/* Set Scheme */
		if (strlen($scheme) > 0) {
			if ($this -> Authorities -> scheme_set($scheme) == 1) {
				$tema = $this -> session -> userdata('sh_initials');
				redirect(base_url('index.php/skos/thema/' . $tema));
			}
		}

		/* Show list Scheme */
		$data = array();
		$data['content'] = $this -> Authorities -> schemes();
		$this -> load -> view('content', $data);

	}

	/* autenticacao */
	private function validate($act = '') {

		if ($act == 'out') {
			$ck = array();
			$ck['user'] = null;
			$ck['perfil'] = null;
			$ck['nome'] = null;
			$ck['nivel'] = null;
			$ck['id'] = null;
			$ck['check'] = null;
			$this -> session -> set_userdata($ck);
			return (1);
		}

		$login = get("userName");
		$passw = get("userPassword");

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
					$ck['check'] = md5($line['id_us'] . date("Ymd"));
					$this -> session -> set_userdata($ck);
					return (1);
				} else {
					$ok = $line['us_ativo'];
					if ($ok == 1) { $ok == -9;
					}
					return ($ok);
				}
			} else {
				return (-2);
			}
		} else {
			if (isset($_SESSION['check'])) {
				return (1);
			}
		}
		return (0);
	}

	function user_revalid() {
		/* Load model */

		$this -> load -> model("Authorities");

		$email = get("dd0");
		$check = get("chk");

		$chk = checkpost_link($email);
		if ($check == $chk) {
			$this -> cab();
			$this -> Authorities -> user_exist($email);
			$line = $this -> Authorities -> line;
			$nome = $line['us_nome'];
			$this -> Authorities -> user_email_send($email, $nome, 'SIGNUP');
			$data = array();
			$data['title'] = msg('email');
			$data['content'] = msg('has_send_email_to') . ' ' . $email;
			$this -> load -> view('skos/100', $data);
		} else {
			$this -> cab(0);
			$data = array();
			$data['title'] = 'Error 533';
			$data['content'] = 'Invalid checkpost';
			$this -> load -> view('skos/510', $data);
		}

	}

	function user_valid() {
		/* Load model */

		$this -> load -> model("Authorities");
		$this -> cab();

		$email = get("dd0");
		$check = get("chk") . get("dd4");
		$acao = get("acao");
		$chk2 = '';
		$chk = checkpost_link($email . date("Ymd"));
		if ($chk == $check) {
			/* CHECK #1 */
			if (strlen($acao) > 0) {
				$pw1 = get("dd1");
				$pw2 = get("dd2");
				if (strlen($pw1) < 5) {
					if (strlen($pw1) > 0) {
						$chk2 = '<span class="btn alert-danger">' . msg('password_more_shot') . '</span>';
					} else {
						$chk2 = '<span class="btn alert-danger">' . msg('password_is_requered') . '</span>';
					}
				} else {
					if ($pw1 != $pw2) {
						$chk2 = '<span class="btn alert-danger">' . msg('no_match_password') . '</span>';
					} else {
						$this -> Authorities -> user_set_password($email, $pw1);
						$data = array();
						$data['title'] = msg('change_password');
						$data['content'] = msg('change_password_successful');
						$data['content'] .= '<br/><br/><a href="' . base_url('index.php/skos/login') . '" class="btn btn-secondary">' . msg('return') . '</a>';
						$this -> load -> view('skos/100', $data);
						return (0);
					}
				}
			}

			$this -> Authorities -> user_valid($email, 1);
			$cp = array();
			array_push($cp, array('$HV', '', $email, True, True));
			array_push($cp, array('$P80', '', msg('new_password'), True, True));
			array_push($cp, array('$P80', '', msg('retype') . ' ' . msg('new_password'), True, True));
			array_push($cp, array('$B8', '', msg('save'), False, True));
			array_push($cp, array('$HV', '', $chk, True, True));
			array_push($cp, array('$M', '', $chk2, True, True));
			$form = new form;

			$form -> id = 0;
			$tela = $form -> editar($cp, '');

			$data['title'] = msg('welcome');
			$data['content'] = msg('define_your_password');
			$data['content'] .= $tela;
			$this -> load -> view('skos/100', $data);

			//$this -> Authorities -> user_email_send($email, $nome, 'WELCOME');
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

	function a($id='',$fmt='') {
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
				$this->load->view('ca/ca_name',$data);
				
				$this->load->view('ca/ca_proprieties',$data);

				$data['content'] = $this->Authorities->sugestoes($data['rl_value']);
				$this->load->view('content',$data);		
				break;
			}		
	}
}
?>