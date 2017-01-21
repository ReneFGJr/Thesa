<?php
class skos extends CI_Controller {

	var $CO = 25;
	/* Conceito */
	var $TG = 26;
	/* Termo Geral */
	var $BT = 27;
	/* Termo Específico */
	var $TRc = 28;
	/* Termo Coordenado */
	var $TRu = 29;
	/* União com */
	var $TH = 34;
	/* Hidden */

	function __construct() {
		parent::__construct();
		//$this -> lang -> load("app", "portuguese");
		$this -> lang -> load("skos", "portuguese");
		$this -> load -> library('form_validation');
		$this -> load -> database();
		$this -> load -> helper('form');
		$this -> load -> helper('form_sisdoc');
		$this -> load -> helper('url');
		$this -> load -> library('session');

		date_default_timezone_set('America/Sao_Paulo');
		/* Security */
		//		$this -> security();
	}

	function cab($nb = 1) {
		$this -> load -> view('skos/thesa_header', null);
		if ($nb > 0) {
			$this -> load -> view("skos/thesa_cab", null);
			$this -> load -> view('skos/thesa_menu');
			if ($nb != 2) {
				$this -> load -> view("skos/thesa_search");
			}
		}
	}

	function show_404() {
		$this->cab(0);
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

	function index() {
		$this -> cab(2);
		$this -> load -> view("skos/github_fork", null);
		$this -> load -> view("skos/welcome", null);
		$this -> load -> view("skos/thesa_home", null);
		//redirect(base_url('index.php/skos/myskos'));
	}

	/* LOGIN */
	function login($act='') {
		$this -> cab(2);
		
		if (strlen($act) > 0)
		{
			if ($this->validate($act) == 1)
				{
					redirect(base_url('index.php/skos/'));
				}
		}

		$this -> load -> view('skos/thesa_login');

		$ok = $this -> validate();
		if ($ok == 1) {
			redirect(base_url('index.php/skos/myth'));
			exit ;
		}

	}

	/* EDIcaO DAS CLASSE */
	function classe() {
		$this -> load -> model('skoses');

		$this -> cab();
		$tela = $this -> skoses -> form(1, 1);
		$data['content'] = $tela;
		$data['title'] = '';
		$this -> load -> view('content', $data);
	}

	function myth() {
		$this -> load -> model('skoses');

		$this -> cab();
		$us = 1;
		$tela = $this -> skoses -> myskoses($us);
		$data['content'] = $tela;
		$data['title'] = msg('my_thesauros');
		$this -> load -> view('content', $data);
	}

	function thesa() {
		$this -> load -> model('skoses');

		$this -> cab();
		$us = 1;
		$tela = $this -> skoses -> myskoses(0);
		$data['content'] = $tela;
		$data['title'] = msg('my_thesauros');
		$this -> load -> view('content', $data);
	}

	function select($id = 0, $chk = '') {
		$this -> load -> model('skoses');
		if (checkpost_link($id) == $chk) {
			$this -> skoses -> skoses_select($id);
		}
		redirect(base_url('index.php/skos/terms/' . $id));
	}

	function show($id = '', $chk = '', $ltr = '') {
		$this -> load -> model('skoses');
		$this -> cab();

		if (strlen($id) == 0) {
			$id = $_SESSION['skos'];
		}

		$data = $this -> skoses -> le_skos($id);
		$this -> load -> view('skos/view', $data);

		$this -> load -> view('skos/skos_nav', $data);

		$tela = $this -> skoses -> termos_pg($id);
		$tela = '<div class="row">';
		$tela = '	<div class="col-md-9">';

		/* Tree */
		$tela .= $this -> load -> view('skos/search', null, true);

		$tela .= '	</div>';

		$tela .= '	<div class="col-md-3">';
		$tela .= $this -> skoses -> thesaurus_resume($id);
		$tela .= '<a href="' . base_url('index.php/skos/thes/') . '" class="btn btn-default" style="width: 100%;">' . msg('Conceitual map') . '</a>' . cr();
		$tela .= '<br><br>';
		$tela .= '<a href="' . base_url('index.php/skos/thrs/') . '" class="btn btn-default" style="width: 100%;">' . msg('Report Thesaurus') . '</a>' . cr();
		$tela .= '	</div>';
		$tela .= '</div>';
		$data['content'] = $tela;
		$this -> load -> view('content', $data);
	}

	function concept_add() {
		$this -> load -> model('skoses');
		$this -> cab();
		$id = $_SESSION['skos'];
		$data = $this -> skoses -> le_skos($id);
		$this -> load -> view('skos/view', $data);
		$data['pg'] = 2;
		$this -> load -> view('skos/skos_nav', $data);

		$form = new form;
		$cp = array();
		array_push($cp, array('$A3', '', msg('terms'), False, False));
		array_push($cp, array('$T80:8', '', msg('terms'), True, False));
		array_push($cp, array('$M', '', '<font class="small">' . msg('terms_info') . '</font>', False, False));
		array_push($cp, array('$Q lg_code:lg_language:select * from language where lg_active = 1 order by lg_order', '', msg('language'), True, False));
		array_push($cp, array('$B8', '', msg('save'), False, False));
		$tela = $form -> editar($cp, '');

		if ($form -> saved > 0) {
			$data['content'] = $this -> skoses -> incorpore_terms(get("dd1"), $id, get("dd3"));
			$data['title'] = '';
			$this -> load -> view('content', $data);
		} else {
			$data['content'] = $tela;
			$data['title'] = '';
			$this -> load -> view('content', $data);
		}

	}

	function concept_subordinate($th = '', $chk = '', $idt) {

		$this -> load -> model('skoses');
		$this -> cab();
		if (strlen($th) == 0) {
			$th = $_SESSION['skos'];
		}

		$data = $this -> skoses -> le_skos($th);
		$this -> load -> view('skos/view', $data);

		$data = $this -> skoses -> le_term($idt, $th);
		$data['pg'] = 1;
		$this -> load -> view('skos/skos_nav_term', $data);
		if (strlen(trim(trim($data['ct_concept']))) == 0) {
			$data['action'] = '<a href="' . base_url('index.php/skos/concept_create/' . $data['lt_thesauros'] . '/' . checkpost_link($data['lt_thesauros']) . '/' . $data['id_lt']) . '" class="btn btn-primary">' . msg('Terms_create_concept') . '</a></li>';
		}
		$this -> load -> view('skos/view_term', $data);

		$this -> skoses -> th_concept_subordinate($idt, $th);
		$tela = '';
		$data['content'] = $tela;
		$this -> load -> view('content', $data);

	}

	/***************************************************************************** Termo equivalente */
	function te($c = '') {
		$c = round($c);
		$th = $_SESSION['skos'];

		/* Load model */
		$this -> load -> model("skoses");
		$this -> cab(0);

		$data = $this -> skoses -> le($c);
		$data['form'] = $this -> skoses -> form_concept($th, $c, 'TE');
		$this -> load -> view('skos/view_concept_mini', $data);
		
		

		$action = get("action");
		$desc = get("tm");
		$tr = get("tr");

		if ((strlen($action) > 0) and (strlen($tr) > 0) and (strlen($desc) > 0)) {
			echo 'SAVED';
			$this -> skoses -> assign_as_propriety($c, $th, $tr, $desc);
			$this -> load -> view('header/close', null);
			//$this->skoes->
		}

	}

	/***************************************************************************** Definição de conceito */
	function tf($c = '') {
		$c = round($c);
		$th = $_SESSION['skos'];

		/* Load model */
		$this -> load -> model("skoses");
		$this -> cab(0);

		$data = $this -> skoses -> le($c);
		$data['form'] = $this -> skoses -> form_concept_tf($th, $c);
		$this -> load -> view('skos/view_concept_mini', $data);

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

	/***************************************************************************** TG Geral */
	function tg($c = '') {
		$c = round($c);
		$th = $_SESSION['skos'];

		/* Load model */
		$this -> load -> model("skoses");
		$this -> cab(0);

		$data = $this -> skoses -> le($c);
		$data['form'] = $this -> skoses -> form_concept_tg($th, $c);
		$this -> load -> view('skos/view_concept_mini', $data);

		$action = get("action");
		$tg = get("tg");
		if ((strlen($action) > 0) and (strlen($tg) > 0)) {
			echo $this -> TG;
			$this -> skoses -> assign_as_narrower($tg, $c, $th, 0);
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
		$this -> load -> model("skoses");
		$this -> cab();
		$data['file'] = $th;
		//$this->load->view('grapho/mind_tree',null);
		//$this->load->view('grapho/mind_map',null);
		$this -> load -> view('grapho/mind_map_full', $data);
		//

	}
	
	function th_edit($id='',$chk='')
		{
			/* Load model */
			$this -> load -> model("skoses");
					
			$this->cab(2);
			
			$data = $this->skoses->le_skos($id);
			
			if (count($data) > 0)
				{
					if ((isset($_SESSION['id']) and ($_SESSION['id']) == $data['pa_creator']))
						{
						$cp = $this->skoses->cp_th($id);
						$form = new form;
						$form->table = $this->skoses->table_thesaurus;
						$form->cp = $cp;
						$form->id = $id;
						
						$data['content'] = $form->editar($cp,$this->skoses->table_thesaurus);
						$this->load->view('content',$data);

						if ($form->saved > 0)
							{
								if (strlen($id) > 0)
									{
										redirect(base_url('index.php/skos/myth/'.$id));		
									} else {
										redirect(base_url('index.php/skos/myth'));
									}
								
							}							
						}
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
		$this -> load -> view('skos/view_concept_mini', $data);

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

	/***************************************************************************** Termo oculto */
	function tz($c = '') {
		$c = round($c);
		$th = $_SESSION['skos'];

		/* Load model */
		$this -> load -> model("skoses");
		$this -> cab(0);

		$data = $this -> skoses -> le($c);
		$data['form'] = $this -> skoses -> form_concept($th, $c, 'TH');
		$this -> load -> view('skos/view_concept_mini', $data);

		$action = get("action");
		$desc = get("tm");
		$tr = get("tr");

		if ((strlen($action) > 0) and (strlen($tr) > 0) and (strlen($desc) > 0)) {
			echo 'SAVED';
			$this -> skoses -> assign_as_propriety($c, $th, $tr, $desc);
			$this -> load -> view('header/close', null);
			//$this->skoes->
		}

	}

	/***************************************************************************** Termo oculto */
	function tx($c = '') {
		$c = round($c);
		$th = $_SESSION['skos'];

		/* Load model */
		$this -> load -> model("skoses");
		$this -> cab(0);

		$data = $this -> skoses -> le($c);
		$data['form'] = $this -> skoses -> form_concept($th, $c, 'FE');
		$this -> load -> view('skos/view_concept_mini', $data);

		$action = get("action");
		$desc = get("tm");
		$tr = get("tr");

		if ((strlen($action) > 0) and (strlen($tr) > 0) and (strlen($desc) > 0)) {
			echo 'SAVED';
			$this -> skoses -> assign_as_propriety($c, $th, $tr, $desc);
			$this -> load -> view('header/close', null);
			//$this->skoes->
		}

	}

	/***************************************************************************** Termo oculto */
	function glossario($th = '') {
		$th = round($_SESSION['skos']);

		/* Load model */
		$this -> load -> model("skoses");
		$this -> cab(0);

		$data['content'] = $this -> skoses -> glossario($th);
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

	/***************************************************************************** Conecpt */
	function ti($c = '', $th = '') {
		/* Load model */
		$this -> load -> model("skoses");
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
			$idt = $this -> skoses -> terms_add($ta, $th, $lang);
			$this -> skoses -> association_term_th($ta, $lang, $th);
			$this -> skoses -> association_term_concept($idt, $c, $th, $type);
		}
		redirect(base_url('index.php/skos/c/' . $c));
	}

	/***************************************************************************** Conecpt */
	function c($c = '', $th = '') {
		$this -> load -> model("skoses");

		/* CAB */
		$this -> cab();

		$data = $this -> skoses -> le($c);
		$data = $this -> skoses -> le_c($data['id_c'], $data['ct_th']);
		
		$this -> load -> view('skos/thesa_thesaurus', $data);
		$this -> load -> view('skos/thesa_breadcrumb', $data);

		$this -> load -> view("skos/thesa_schema", $data);
		$this -> load -> view("skos/thesa_concept", $data);

		//redirect(base_url('index.php/skos/myskos'));
	}
	
	function te_remove($id='',$chk='')
		{
			$this -> load -> model("skoses");
			$this->skoses->te_remote($id);	
		}

	function cedit($c = '', $th = '') {

		/* Load model */
		$edit = true;
		$this -> load -> model("skoses");
		$this -> cab();

		/* RECUPERA SCHEMA */
		if (strlen($th) == 0) {
			$th = $_SESSION['skos'];
		}

		/* LE DADOS SOBRE O SCHEMA */
		$data = $this -> skoses -> le_skos($th);
		$this -> load -> view('skos/view', $data);
		$data['pg'] = 4;
		$data['editar'] = 1;

		/* menu */
		$this -> load -> view('skos/skos_nav', $data);

		/* Recupera informações sobre o Concecpt */
		$data2 = $this -> skoses -> le_c($c, $th);
		$this -> load -> view('skos/view_concept', $data2);

	}

	function c2($c = '', $th = '') {

		/* Recupera informações sobre o Concecpt */
		$data2 = $this -> skoses -> le_c($c, $th);
		$data['th'] = $this -> skoses -> le_tree_js($c, $data);

		$data['tree'] = $this -> load -> view('skos/skos_concept_tree_view', $data, true);
		/* assigned */
		$dd10 = get("dd10");
		$dd11 = get("dd11");
		$dd12 = get("dd12");
		switch($dd11) {
			case 'assign_as_narrower' :
				if ($dd12 == $c) {
					$tm = 0;
					$this -> skoses -> assign_as_narrower($c, $dd10, $th, $tm);
				}
				break;
		}

		print_r($data2);
		$tela = $this -> load -> view('skos/view_concept', $data2, true);
		$sx = '';
		$sx .= $this -> skoses -> concept_show($c);

		if ($edit) {
			$dt = $this -> skoses -> le_tree_not_assign($th, $data);

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
		$this -> load -> model("skoses");
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
			$term = $this -> skoses -> insere_termo(get("dd1"), get("dd2"));
			$this -> skoses -> create_concept($term, $dominio);

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
		$this -> load -> model("skoses");
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

	function ed($idc = 0, $tp = '') {
		/* Load model */
		$this -> load -> model("skoses");
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
				$term = $this -> skoses -> insere_termo(get("dd1"), get("dd2"));
				$idt = $this -> skoses -> association_term($term, $concept, $type);
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
	}

	/* Narrowed */
	function narrows_add($id = '') {
		/* Load model */
		$this -> load -> model("skoses");

		$dd1 = utf8_decode($this -> input -> post('link'));
		$txt = '/thema/';
		$dd1 = substr($dd1, strpos($dd1, $txt) + strlen($txt), strlen($dd1));
		$dd1 = troca($dd1, '/', ';');
		$it = splitx(';', $dd1);

		$c1 = $this -> skoses -> recupera_id_concept($it[1]);
		$c2 = $this -> skoses -> recupera_id_concept($it[2]);

		if (($c1 > 0) and ($c2 > 0)) {
			$this -> skoses -> narrows_link($c1, $c2);
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
		$this -> load -> model('skoses');
		$th = $_SESSION['skos'];
		if ($id != $th) {
			echo 'OPS-402';
			exit ;
		}
		$this -> cab();

		$is_active = $this -> skoses -> is_concept($t, $th);

		if ($is_active == 0) {
			$id = $this -> skoses -> concept_create($t, $th);
			redirect(base_url('index.php/skos/c/'.$id));
		}

	}

	/* Terms */
	function term($th = '', $idt = '') {

		$this -> load -> model("skoses");

		/* CAB */
		$this -> cab();


		$data = $this -> skoses -> le_th($th);
		
		$data2 = $this->skoses->le_term($idt,$th);
		$data = array_merge($data,$data2);
		
		$this -> load -> view('skos/thesa_thesaurus', $data);
		$this -> load -> view('skos/thesa_breadcrumb', $data);
		/******************************/

		if (strlen(trim(trim($data['ct_concept']))) == 0) {
			$data['action'] = '<a href="' . base_url('index.php/skos/concept_create/' . $data['lt_thesauros'] . '/' . checkpost_link($data['lt_thesauros']) . '/' . $data['id_rl']) . '" class="btn btn-primary">' . msg('Terms_create_concept') . '</a></li>';
		}
		$this -> load -> view('skos/view_term', $data);

		$tela = '';
		$data['content'] = $tela;
		$this -> load -> view('content', $data);

	}

	function terms($id = '', $ltr = '') {
		$this -> load -> model('skoses');
		$this -> cab();

		if (strlen($id) == 0) {
			$id = $_SESSION['skos'];
		}

		$data = $this -> skoses -> le_skos($id);
		$this -> load -> view('skos/thesa_thesaurus', $data);

		$tela = $this -> skoses -> termos_pg($id);
		$tela .= '<div class="row">';
		$tela .= '	<div class="col-md-9">';
		$tela .= $this -> skoses -> termos_show_letter($id, $ltr);
		$tela .= '	</div>';

		$tela .= '	<div class="col-md-3">';
		$tela .= $this -> skoses -> thesaurus_resume($id);
		if ($this->skoses->autho('',$id)==1)
			{
				/* TERMO PERDIDOS */
				$tela .= $this->skoses->acao_novos_termos($id);
				$tela .= $this->skoses->termos_sem_conceito($id,$ltr);
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

		$this -> load -> model("skoses");
		$this -> cab_scheme();

		/* Show list Terms */
		$data = array();
		$data['terms'] = $this -> skoses -> concepts($scheme, $concept);
		$data['terms'] .= '<hr>';
		$data['terms'] .= $this -> skoses -> concepts_no($scheme, $concept);
		if (strlen($concept) > 0) {
			$data['terms_content'] = $this -> skoses -> concept($concept);
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

		$this -> load -> model("skoses");
		$this -> cab();
		$data = $this -> skoses -> le_tree($th);

		$data = array();

	}

	function thrs() {

		$th = $_SESSION['skos'];
		if (strlen($th) == 0) {
			redirect(base_url('index.php/skos'));
		}
		/* Load model */

		$this -> load -> model("skoses");
		$this -> cab();
		$data = $this -> skoses -> le_report($th);

		$data = array();

	}

	function scheme($scheme = '') {
		/* Load model */

		$this -> load -> model("skoses");
		$this -> cab();

		/* Set Scheme */
		if (strlen($scheme) > 0) {
			if ($this -> skoses -> scheme_set($scheme) == 1) {
				$tema = $this -> session -> userdata('sh_initials');
				redirect(base_url('index.php/skos/thema/' . $tema));
			}
		}

		/* Show list Scheme */
		$data = array();
		$data['content'] = $this -> skoses -> schemes();
		$this -> load -> view('content', $data);

	}

	/* autenticacao */
	private function validate($act = '') {
		
		if ($act == 'out')
			{
					$ck = array();
					$ck['user'] = null;
					$ck['perfil'] = null;
					$ck['nome'] = null;
					$ck['nivel'] = null;
					$ck['id'] = null;
					$ck['check'] = null;
					$this -> session -> set_userdata($ck);
					return(1);
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
				}
			}
		} else {
			if (isset($_SESSION['check']))
				{
					return(1);
				}
		}
		return (0);
	}

}
?>