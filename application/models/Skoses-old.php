<?php
if (!function_exists('msg')) {
	function msg($x) {
		return ($x);
	}

}
class skoses extends CI_model {
	var $concept = 'wese_concept';

	function form($id = 0, $form = 0) {
		$sql = "select * from rdf_prefil_class
						INNER JOIN th_thesaurus_propriety ON id_rpc = pa_class
						INNER JOIN rdf_resource ON id_rs = pa_propriety
						INNER JOIN rdf_prefix ON id_prefix = rs_prefix
						INNER JOIN rdf_form_rule ON id_rr = pa_rule
						WHERE id_rpc = $form
						ORDER BY pa_ord";
		$cp = array();
		array_push($cp, array('$H8', '', '', False, True));
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			if ($r == 0) {
				$idn = trim($line['rpc_name']);
				if (strlen($idn) > 0) {
					$idn = ': <b>' . $idn . '</b>';
				}
				array_push($cp, array('$A3', '', msg($line['rpc_description']) . $idn, False, False));
			}
			$idr = trim($line['prefix_ref']) . ':' . trim($line['rs_propriety']);
			array_push($cp, array($line['rr_field'], '', msg($idr), False, True));
		}
		array_push($cp, array('$B8', '', msg('save'), False, True));
		$form = new form;
		$tela = $form -> editar($cp, '');
		return ($tela);
	}

	function le_c($concept,$th='') {
		$id = sonumero($concept);
		if (strlen($th) == 0) 
			{ $th = $_SESSION['skos']; }

		$sql = "select * from th_concept
				where id_c = '$id' and c_th = $th ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			$line = $rlt[0];
			$id = $line['id_c'];
			$line['uri'] = base_url('index.php/skos/c/' . $id);

			$sql = "select * from th_concept_term
						INNER JOIN rdf_literal ON id_rl = ct_term 
						WHERE ct_concept = $id and ct_propriety = 25
						order by id_ct";
			$rlt = $this -> db -> query($sql);
			$rlt = $rlt -> result_array();
			$line['terms'] = $rlt;
			$line['pref_term'] = $rlt[0]['rl_value'];
		} else {
			$line = array();
			echo 'OPS';
			exit;
		}
		return ($line);
	}

	function assign_as_narrower($c1, $c2, $th, $tm) {
		/* Verifica */
		$sql = "select * from th_concept_term 
						WHERE (ct_concept_2 = $c2 and ct_propriety = 26)";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) == 0) {
			$sql = "insert into th_concept_term
							(
								ct_concept, ct_th, ct_term, 
								ct_concept_2, ct_propriety
							) values (
								$c1, $th, $tm,
								$c2,26
							)";
		}	$rlt = $this -> db -> query($sql);
	}

	function le_tree($id = '', $data) {
		$sql = "SELECT t1.ct_concept as c1, rl_value FROM th_concept_term AS t1
						LEFT JOIN th_concept_term AS t2 on (t1.ct_concept = t2.ct_concept) AND (t2.ct_propriety = 26)
					    left join rdf_literal ON t1.ct_term = id_rl
					    WHERE t1.ct_propriety = 25 and t2.id_ct is null and t1.ct_th = $id ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		print_r($rlt);
		return ($rlt);
	}

	function le_tree_not_assign($id = '', $data) {
		$sql = "SELECT t1.ct_concept as c1, rl_value FROM 
					(select * from th_concept_term where ct_propriety = 25) as T1
				    LEFT JOIN (select * from th_concept_term where ct_propriety = 26) as T2 on T1.ct_concept =  T2.ct_concept_2
				    left join rdf_literal ON t1.ct_term = id_rl
					WHERE T2.ct_concept IS null AND t1.ct_th = $id ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		return ($rlt);
	}

	function le_tree_js($c = '', $data) {
		$datac = $this -> le_c($c);
		$th = $datac['c_th'];

		/* GERAL */
		$sql = "SELECT t1.ct_concept as c1, rl_value
						FROM (select * from th_concept_term where ct_concept_2 = $c and ct_propriety = 26 and ct_th = $th) as t1
    							LEFT JOIN th_concept_term as T2 On t2.ct_concept = t1.ct_concept and t2.ct_propriety = 25
        						INNER JOIN rdf_literal ON t2.ct_term = id_rl 
  						order by rl_value";

		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$sx = '';
		if (count($rlt) > 0) {
			$datad = $rlt[0];
			$sx = "
			{
			text : ' TG: " . $datad['rl_value'] . "',
			href : '" . base_url('index.php/skos/c/' . $datad['c1']) . "',
			tags : [],
			} ,
			";
		}

		/* atual */
		$line = $datac['terms'][0];
		$sx .= "
				{
				text : ' " . $line['rl_value'] . "',
				href : '" . base_url('index.php/skos/c/' . $line['ct_concept']) . "',
				tags : [''],
				nodes : [ " . cr();

		$sql = "SELECT t2.ct_concept as c1, rl_value
						FROM (select * from th_concept_term where ct_concept = $c and ct_propriety = 26 and ct_th = 1) as t1
						INNER JOIN th_concept_term as T2 On t1.ct_concept_2 = t2.ct_concept
						INNER JOIN rdf_literal ON T2.ct_term = id_rl
						order by rl_value";

		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			if ($r > 0) { $sx .= ', ';
			}
			$sx .= "
				{
				text : ' " . $line['rl_value'] . "',
				href : '" . base_url('index.php/skos/c/' . $line['c1']) . "',
				tags : [''],
				}" . cr();
		}
		//$sx .= "]}" . cr();
		$sx .= "]}" . cr();
		return ($sx);
	}

	function myskoses($user) {
		$sql = "select * from th_thesaurus ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$sx = '';
		$sx .= '<div class="row small">';
		$sx .= '<div class="col-xs-6"><b>' . msg('description') . '</b></div>';
		$sx .= '<div class="col-xs-2"><b>' . msg('date') . '</b></div>';
		$sx .= '<div class="col-xs-2"><b>' . msg('status') . '</b></div>';
		$sx .= '<div class="col-xs-2"><b>' . msg('type') . '</b></div>';
		$sx .= '</div>' . cr();
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$link = '<a href="' . base_url('index.php/skos/select/' . $line['id_pa'] . '/' . checkpost_link($line['id_pa'])) . '" class="link">';
			$sx .= '<div class="row big">';
			$sx .= '<div class="col-xs-6">';
			$sx .= $link . $line['pa_name'] . '</a>';
			$sx .= '</div>';
			$sx .= '<div class="col-xs-2">';
			$sx .= $link . stodbr($line['pa_created']) . '</a>';
			$sx .= '</div>';
			$sx .= '<div class="col-xs-2">';
			$sx .= $link . $line['pa_status'] . '</a>';
			$sx .= '</div>';
			$sx .= '<div class="col-xs-2">';
			$sx .= $link . $line['pa_status'] . '</a>';
			$sx .= '</div>';
			$sx .= '</div>';
		}
		return ($sx);
	}

	function skoses_select($id) {
		$data = array();
		$data['skos'] = $id;
		$data['skos-md5'] = 'skos' . $id;
		$this -> session -> set_userdata($data);
		return (1);
	}

	Function prepara_termo($t = '') {
		$t = utf8_decode($t);
		$t = LowerCase($t);
		$t = UpperCase(substr($t, 0, 1)) . substr($t, 1, strlen($t));
		$t = utf8_encode($t);
		return ($t);
	}

	function concept_terms_add($t = '', $id = '', $lang = '') {
		$th = $_SESSION['skos'];
		$t = troca($t, chr(13), ';');
		$t = troca($t, chr(10), ';');
		$ln = splitx(';', $t);
		$sx = '';
		for ($r = 0; $r < count($ln); $r++) {
			$t = $ln[$r];
			if (strlen($t) > 0) {
				$this -> terms_add($t, $id, $lang);
				$idt = $this -> association_term_th($t, $lang, $id);
				$this -> create_concept($t, $th);
			}
		}
		return ($sx);
	}

	function terms_add($t = '', $id = '', $lang = '') {
		$t = $this -> prepara_termo($t);
		$sql = "select * from rdf_literal where rl_value = '$t' and rl_type = 24 and rl_lang = '$lang' ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$sx = '';
		if (count($rlt) == 0) {
			$sqli = "insert into rdf_literal 
							(
							rl_value, rl_type, rl_lang
							) values (
							'$t',24, '$lang')
							";
			$xrlt = $this -> db -> query($sqli);
			$sx .= $t . '@' . $lang . ' <font style="color: green;">' . msg('inserted') . '</font><br>';
		} else {
			$sx .= $t . '@' . $lang . ' <font style="color: red;">' . msg('already_inserted') . '</font><br>';
		}
		$sql = "select * from rdf_literal where rl_value = '$t' and rl_type = 24 and rl_lang = '$lang' ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$line = $rlt[0];
		$id = $line['id_rl'];
		return ($id);
	}

	function association_term_th($t, $lang, $th) {
		$sql = "select * from rdf_literal where rl_value = '$t' and rl_type = 24 and rl_lang = '$lang' ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			$idt = $rlt[0]['id_rl'];
			$sql = "select * from rdf_literal_th where lt_thesauros = $th and lt_term = $idt ";
			$xrlt = $this -> db -> query($sql);
			$xrlt = $xrlt -> result_array();
			if (count($xrlt) == 0) {
				$sql = "insert into rdf_literal_th (lt_thesauros, lt_term) values ('$th','$idt')";
				$yrlt = $this -> db -> query($sql);
			}
		}
	}

	function termos_show_letter($id, $ltr) {
		if (strleN($ltr) == 0) {
			return ('');
		}

		$sql = "select * from rdf_literal
					INNER JOIN rdf_literal_th ON lt_term = id_rl 
					LEFT JOIN th_concept_term ON ct_term = id_rl and ct_th = $id
					WHERE lt_thesauros = $id and rl_type = 24
						AND rl_value like '$ltr%' and lt_thesauros = $id 
						ORDER BY rl_value
					";
					echo $sql;
		$xrlt = $this -> db -> query($sql);
		$xrlt = $xrlt -> result_array();
		$sx = '<ul>';
		for ($r = 0; $r < count($xrlt); $r++) {
			$line = $xrlt[$r];
			$sa = '';
			$link = '<a href="' . base_url('index.php/skos/term/' . $id . '/' . checkpost_link($id) . '/' . $line['id_rl']) . '">';
			if (round($line['ct_concept']) > 0) {
				$sa = '<span class="glyphicon glyphicon-tag" aria-hidden="true"></span>';
				$link = '<a href="' . base_url('index.php/skos/c/' . $line['ct_concept']) . '/'.$id.'/">';
			}
			$sx .= '<li>' . $link . $sa . ' ' . $line['rl_value'] . '</a></li>';
		}
		$sx .= '</ul>';
		return ($sx);
	}

	function termos_pg($id) {
		$sql = "select substr(rl_value,1,1) as letra from rdf_literal
					INNER JOIN rdf_literal_th ON id_rl = lt_term
					WHERE lt_thesauros = $id and rl_type = 24
					GROUP BY letra
					ORDER BY letra
					";
		$xrlt = $this -> db -> query($sql);
		$xrlt = $xrlt -> result_array();
		$sx = '<nav aria-label="Page navigation text-center"><ul class="pagination">';
		for ($r = 0; $r < count($xrlt); $r++) {
			$line = $xrlt[$r];
			$sx .= '<li><a href="' . base_url('index.php/skos/terms/' . $id . '/' . checkpost_link($id) . '/' . $line['letra']) . '">' . $line['letra'] . '</a></li>';
		}
		$sx .= '</ul></nav>	';
		return ($sx);
	}

	function resume_th($id) {
		$sql = "select count(*) as total from rdf_literal_th where lt_thesauros = $id";
		$xrlt = $this -> db -> query($sql);
		$xrlt = $xrlt -> result_array();
		$sx = '<table class="table" width="100%">';
		for ($r = 0; $r < count($xrlt); $r++) {
			$line = $xrlt[$r];
			$sx .= '<tr>';
			$sx .= '<td align="right">' . msg('terms') . '</td>';
			$sx .= '<td>' . $line['total'] . '</td>';
		}
		/*** TERMS WIDTHOUT CONCEPT ***/

		$xrlt = $this -> db -> query($sql);
		$xrlt = $xrlt -> result_array();
		$sx = '<table class="table" width="100%">';
		for ($r = 0; $r < count($xrlt); $r++) {
			$line = $xrlt[$r];
			$sx .= '<tr>';
			$sx .= '<td align="right">' . msg('terms') . '</td>';
			$sx .= '<td>' . $line['total'] . '</td>';
		}

		/***/
		$sql = "select count(*) as total from th_concept where c_th = $id";
		$xrlt = $this -> db -> query($sql);
		$xrlt = $xrlt -> result_array();

		for ($r = 0; $r < count($xrlt); $r++) {
			$line = $xrlt[$r];
			$sx .= '<tr>';
			$sx .= '<td align="right">' . msg('concepts') . '</td>';
			$sx .= '<td>' . $line['total'] . '</td>';
		}
		$sx .= '</table>';
		return ($sx);
	}

	function le_skos($id) {
		$sql = "select * from th_thesaurus where id_pa = " . $id;
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			$line = $rlt[0];
			return ($line);
		} else {
			return ( array());
		}
	}

	function le($scheme, $concept) {
		$sql = "select * from wese_concept
 					inner join  wese_label on id_c = l_concept_id
 					inner join wese_term on id_t = l_term
 					inner join  wese_scheme on id_sh = c_scheme
				where l_type='PREF' 
 					and sh_initials = '$scheme'	
 					and c_id = '$concept'	
				order by t_name ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			$line = $rlt[0];
		} else {
			$line = array();
		}
		return ($line);
	}

	function le_term($id = '', $th = '') {
		$sql = "select * from rdf_literal 
					INNER JOIN rdf_literal_th ON lt_term = id_rl
					LEFT JOIN th_concept_term ON ct_term = id_rl and ct_propriety = 25 and ct_th = $th
						WHERE lt_thesauros = $th AND id_rl = " . $id;
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			$line = $rlt[0];
		} else {
			$line = array();
		}
		return ($line);

	}

	function alphabetic_list($letter = '') {
		$sx = $this -> show_letters($letter);
		return ($sx);
	}

	function show_letters() {
		$letter = get("dd0");
		$sql = "SELECT letters, count(*) as total 
						FROM ( 
							SELECT substr(t_name,1,1) as letters, 1 FROM wese_concept 
							INNER JOIN wese_label on l_concept_id = id_c 
							INNER JOIN wese_term on l_term = id_t
							) as tabela 
						GROUP BY letters ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();

		$sx = '<center><table align="center">';
		$sx .= '<tr align="center">';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$bg = '';
			if ($line['letters'] == $letter) {
				$bg = ' bg_lblue ';
				$sx .= '';

			}
			$link = '<a href="' . base_url('index.php/skos/?dd0=' . $line['letters']) . '" class="link lt3">';
			$sx .= '<td class="border1 pad5 radius5 ' . $bg . '" style="width: 20px;" >' . $link . $line['letters'] . '</a>' . '</font>';
		}
		$sx .= '</table></center>';
		return ($sx);
	}

	function show_terms_by_letter($letter = '') {
		$sql = "SELECT * 
						FROM wese_concept 
							INNER JOIN wese_label on l_concept_id = id_c 
							INNER JOIN wese_term on l_term = id_t 
						WHERE t_name like '$letter%' 
						ORDER BY t_name ";
		echo $sql;
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();

		$sx = '<div class="column3">';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$link = '<a href="' . base_url('index.php/skos/t/' . strzero($line['id_c'], 7)) . '" class="link lt2">';
			$sx .= $link . $line['t_name'] . '</a>';
			$sx .= '<br>';
		}
		$sx .= '</div>';
		return ($sx);
	}

	function search_term($term, $page = 1) {
		$sql = "select * from wese_term 
					left join  wese_label on id_t = l_term
					left join wese_concept on id_c = l_concept_id
					left join  wese_scheme on id_sh = c_scheme
				where t_name like '%$term%'
					order by t_name ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$sx = '<Div class="column3"><tt>';
		$ft = '<span style="line-height: 150%">';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];

			$type = $line['l_type'];

			switch ($type) {
				case 'PREF' :
					$link = '<a href="' . base_url('index.php/skos/t/' . strzero($line['l_concept_id'], 7)) . '" class="link lt2">';
					$sx .= $link . $ft . $line['t_name'] . '<span></a>';
					$type_term = '<font color="#A0A0A0">(prefTerm)</font>';
					$sx .= ' ' . $type_term;
					$sx .= '<br>';

					break;
				default :
					$link = '<a href="' . base_url('index.php/skos/t/' . strzero($line['l_concept_id'], 7)) . '" class="link lt2">';
					$sx .= $link . $ft . $line['t_name'] . '<span></a>';
					$type_term = '<font color="#A0A0A0">(altTerm)</font>';
					$sx .= ' ' . $type_term;
					$sx .= '<br>';
					break;
			}

		}
		$sx .= '</tt></div>';
		return ($sx);
	}

	/* Conceito */
	function narrows_link($c1, $c2) {
		$scheme = $this -> session -> userdata('scheme_id');
		$sql = "insert into wese_concept_tg
					(tg_conceito_2, tg_conceito_1, tg_scheme)
					values
					($c1,$c2,$scheme)					
					";
		//echo $sql;
		//exit;
		$rlt = $this -> db -> query($sql);
		return (1);
	}

	function recupera_id_concept($tm = '') {
		$sql = "select * from wese_concept where c_id = '$tm' ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			$line = $rlt[0];
			return ($line['id_c']);
		} else {
			return (0);
		}
	}

	/* Terms */
	function concept($id) {
		$data = array();

		$sx = '<table width="100%" class="lt1">';
		$sx .= $this -> prefTerm($id);
		$sx .= $this -> terms_association($id, 'ALT');
		$sx .= $this -> terms_association($id, 'HIDDEN');

		$sx .= $this -> broader($id);
		$sx .= $this -> narrower($id);
		$sx .= '</table>';

		return ($sx);
	}

	function concepts_no($id = '', $concept = '') {
		$sql = "select * from wese_concept
 					inner join  wese_label on id_c = l_concept_id
 					inner join wese_term on id_t = l_term
 					left join (
 							select tg_conceito_1 as c1 from wese_concept_tg
 							union
 							select tg_conceito_2 as c1 from wese_concept_tg 
						) as tabela on l_concept_id = c1
 					where l_type='PREF' and c1 is null
				order by t_name ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();

		$sx = '<h2>' . msg('Alphabetical') . '</h2>';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$sx .= '<a href="' . base_url('index.php/skos/thema/' . $id . '/' . $line['c_id']) . '/' . $concept . '/' . '" class="link lt3">';
			$sx .= $line['t_name'];
			$sx .= '</a>';
			$sx .= '<br>';
		}
		return ($sx);
	}

	function concepts($id = '', $concept = '') {
		$scheme = $this -> session -> userdata('scheme_id');

		$sql = "select * from wese_concept
 					inner join  wese_label on id_c = l_concept_id
 					inner join wese_term on id_t = l_term
 					left join (
 							select tg_conceito_1 as c1 from wese_concept_tg
 							union
 							select tg_conceito_2 as c1 from wese_concept_tg 
						) as tabela on l_concept_id = c1
 					where l_type='PREF' and c1 is not null
 					and c_scheme = $scheme			
				order by t_name ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();

		$sx = '<h2>' . msg('Alphabetical') . '</h2>';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$sx .= '<a href="' . base_url('index.php/skos/thema/' . $id . '/' . $line['c_id']) . '/' . $concept . '/' . '" class="link lt3">';
			$sx .= $line['t_name'];
			$sx .= '</a>';
			$sx .= '<br>';
		}
		return ($sx);
	}

	function pref_le($term) {
		$sql = "select * from  wese_label
						inner join wese_term on l_term = id_t
						where l_concept_id = $term and l_type = 'PREF' 
					order by t_name";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		return ($rlt);
	}

	function used_le($term) {
		$sql = "select * from  wese_label
						inner join wese_term on l_term = id_t
						where l_concept_id = $term and l_type = 'ALT' 
					order by t_name";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		return ($rlt);
	}

	function hidden_le($term) {
		$sql = "select * from  wese_label
						inner join wese_term on l_term = id_t
						where l_concept_id = $term and l_type = 'HIDDEN' 
					order by t_name";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		return ($rlt);
	}

	function broader_le($term) {
		$sql = "select distinct t_name,sh_initials, c_id, tg_conceito_2, tg_conceito_1
					FROM wese_concept_tg
						inner join  wese_scheme on id_sh = tg_scheme
						left join wese_concept on tg_conceito_1 = id_c 
						left join wese_label on l_concept_id = id_c
						left join wese_term on l_term = id_t
						where tg_conceito_2 = $term and l_type = 'PREF' 
					order by t_name";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		return ($rlt);
	}

	function narrow_le($term) {
		$sql = "select distinct t_name,sh_initials, c_id, tg_conceito_2, tg_conceito_1 
					FROM wese_concept_tg
						inner join  wese_scheme on id_sh = tg_scheme
						left join wese_concept on tg_conceito_2 = id_c 
						left join wese_label on l_concept_id = id_c
						left join wese_term on l_term = id_t
						where tg_conceito_1 = $term and l_type = 'PREF' 
					order by t_name";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		return ($rlt);
	}

	function broader($id) {
		$id = $this -> recupera_id_concept($id);
		$sql = "select * from wese_concept_tg
					inner join  wese_label on l_concept_id = tg_conceito_1 and l_type = 'PREF'
					inner join wese_term on l_term = id_t
					inner join wese_concept on id_c = l_concept_id
					inner join  wese_scheme on id_sh = c_scheme 
					where tg_conceito_2 = $id 
					order by t_name";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$tm = '';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$concept = $line['sh_initials'];

			$link = '<a href="' . base_url('index.php/skos/thema/' . $concept . '/' . $line['c_id']) . '" class="link lt3">';
			if ($r > 0) { $tm .= '<br>';
			}
			$tm .= $link . $line['t_name'] . '</a>';
		}
		$sx = '<tr valign="top">';
		$sx .= '<td>' . msg('BROADER CONCEPT') . '</td>';
		$sx .= '<td class="lt3">';
		$sx .= $tm;
		$sx .= '<div class="drop" id="broader" ondrop="drop(event)" ondragover="allowDrop(event)">Drop Here the broader term</div>';
		$sx .= '<hr size=1>';
		$sx .= '</td>';
		$sx .= '<tr>';

		$sx .= '
		<style>
		.drop
			{
				width: 90%; height: 30px; border: 1px solid #111111;
				border-radius: 5px;
				padding: 5px;
			}
		</style>
		';
		return ($sx);
	}

	function narrower($id) {
		$id = $this -> recupera_id_concept($id);
		$sql = "select * from wese_concept_tg
					inner join  wese_label on l_concept_id = tg_conceito_2 and l_type = 'PREF'
					inner join wese_term on l_term = id_t
					inner join wese_concept on id_c = l_concept_id
					inner join  wese_scheme on id_sh = c_scheme
					where tg_conceito_1 = $id 
					order by t_name";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$tm = '';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$concept = $line['sh_initials'];

			$link = '<a href="' . base_url('index.php/skos/thema/' . $concept . '/' . $line['c_id']) . '" class="link lt3">';
			if ($r > 0) { $tm .= '<br>';
			}
			$tm .= $link . $line['t_name'] . '</a>';
		}
		$sx = '<tr valign="top">';
		$sx .= '<td>' . msg('NARROWER CONCEPTS') . '</td>';
		$sx .= '<td class="lt3">';
		$sx .= $tm;
		$sx .= '<div class="drop" id="narrower" ondrop="drop(event)" ondragover="allowDrop(event)">Drop Here the narrower term</div>';
		$sx .= '<hr size=1>';
		$sx .= '</td>';
		$sx .= '<tr>';

		$sx .= '
		<style>
		.drop
			{
				width: 90%; height: 30px; border: 1px solid #111111;
				border-radius: 5px;
				padding: 5px;
			}
		</style>
		';
		return ($sx);
	}

	function prefTerm($id) {
		$sql = "select * from wese_concept
 					inner join  wese_label on id_c = l_concept_id
 					inner join wese_term on id_t = l_term
 					where l_type='PREF' and c_id = '$id' ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$sx = '';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$sx .= '<tr valign="top">';
			$sx .= '<td width="25%">' . msg('PREFERRED TERM') . '</td>';
			$sx .= '<td class="lt4"><b>' . $line['t_name'] . '</b></td>';

			$sx .= '<tr><td colspan=2><hr size=1></td></tr>';
		}
		return ($sx);
	}

	function show_concept($c) {
		$sx = '<table width="100%" class="table">';
		/*** conceito 1 */
		$sql = "SELECT
						t1.ct_concept as c1, t1.ct_concept_2 as c2, 
						prefix_ref, rs_propriety, rs_propriety_inverse,
						l1.rl_value as l1  
						FROM th_concept_term AS t1
			 			LEFT JOIN rdf_literal as l1 ON t1.ct_term = l1.id_rl
			 			LEFT JOIN rdf_resource ON ct_propriety = id_rs
			 			LEFT JOIN rdf_prefix ON id_prefix = rs_prefix
						WHERE t1.ct_concept = $c 
						ORDER BY t1.ct_propriety ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();

		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			if (strlen(trim($line['l1'])) > 0) {
				$sx .= '<tr>';

				$sx .= '<td width="30%" align="right" class="small">';
				$sx .= msg(trim($line['prefix_ref']) . ':' . trim($line['rs_propriety']));
				$sx .= '</td>';

				$sx .= '<td width="70%" class="middle">';
				$sx .= '<b>' . trim($line['l1']) . '</b>';
				$sx .= '</td>';
				$sx .= '</tr>';
			}
		}
		
		/*** conceito 2 */
		$sql = "select 
						t1.ct_concept as c1, t1.ct_concept_2 as c2, 
						prefix_ref, rs_propriety, rs_propriety_inverse,
						rl_value as l1
		 		FROM th_concept_term AS T1
					INNER JOIN th_concept_term AS T2 ON t1.ct_concept_2 = t2.ct_concept AND (t2.ct_propriety = 25)
					INNER JOIN rdf_literal ON T2.ct_term = id_rl
					LEFT JOIN rdf_resource ON t1.ct_propriety = id_rs
					LEFT JOIN rdf_prefix ON id_prefix = rs_prefix
					where t1.ct_concept = $c ";

		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();

		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			if (strlen(trim($line['l1'])) > 0) {
				$link = '<a href="#">';
				if ($line['c2'] > 0)
					{
						$link = '<a href="'.base_url('index.php/skos/c/'.$line['c2']).'">';
					}
				$sx .= '<tr>';

				$sx .= '<td width="30%" align="right" class="small">';
				$sx .= msg(trim($line['prefix_ref']) . ':' . trim($line['rs_propriety_inverse']));
				$sx .= '</td>';

				$sx .= '<td width="70%" class="middle">';
				$sx .= '<b>' . $link. trim($line['l1']) . '</a>'.'</b>';
				$sx .= '</td>';
				$sx .= '</tr>';
			}
		}		

		$sx .= '</table>';
		return ($sx);

	}

	function association_term_concept($idt, $c, $th, $type) {
		$sql = "select * from th_concept_term 
					WHERE ct_term = $idt and ct_propriety = $type and ct_th = $th";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) == 0) {
			$sql = "insert into th_concept_term
							(
							ct_concept, ct_th, ct_concept_2,
							ct_term, ct_propriety
							)
							values
							(
							$c,$th,0,
							$idt,$type
							)";
							echo $sql;
							exit;
			$rlt = $this -> db -> query($sql);
		} else {
		}
	}

	function create_concept($t, $th) {
		$data = date("Y-m-d");

		/* locate next concept ID */
		$sql = "select c_concept from th_concept order by c_concept desc limit 1 ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) == 0) {
			$idc = 0;
		} else {
			$line = $rlt[0];
			$idc = sonumero($line['c_concept']);
		}
		$co = 'c' . strzero(($idc + 1), 3);
		
		/* Insert Concept into the thesaurus */
		$sql = "insert into th_concept (c_th, c_concept) values ($th,'$co')";
		$rrb = $this -> db -> query($sql);

		$sql = "select * from th_concept where c_concept = '$co' ";
		$rrb = $this -> db -> query($sql);
		$rrb = $rrb -> result_array();

		$line = $rrb[0];
		$idc = $line['id_c'];
		$sql = "insert into th_concept_term 
							(ct_concept, ct_th, ct_term, ct_propriety, ct_concept_2) 
									values 
							('$idc','$th','$t','25','0') ";
		$rrb = $this -> db -> query($sql);
		return (1);
	}
	
	function find_term_id($t)
		{
			echo '===>'.$t;
			exit;
		}

	function is_concept($id, $th) {
		$sql = "select * from  th_concept_term where ct_th = '$th' and ct_term = " . round($id);
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			$line = $rlt[0];
			return ($line['ct_concept']);
		} else {
			return (0);
		}
	}

	function th_concept_subordinate($id, $th) {
		$sql = "select * from th_concept_term 
						INNER JOIN rdf_literal ON ct_term = id_rl
						WHERE ct_th = $th AND NOT ct_propriety = 26 
						AND ct_term <> $id";
		echo $sql;

	}

	function is_hidden($id) {
		$sql = "select * from  wese_label where l_type = 'HIDEEN' and  l_term = " . round($id);
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			$line = $rlt[0];
			return ($line['l_concept_id']);
		} else {
			return (0);
		}
	}

	function is_altterm($id) {
		$sql = "select * from  wese_label where l_type = 'ALT' and  l_term = " . round($id);
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			$line = $rlt[0];
			return ($line['l_concept_id']);
		} else {
			return (0);
		}
	}

	function is_prefterm($id) {
		$sql = "select * from  wese_label where l_type = 'HIDDEN' and  l_term = " . round($id);
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			$line = $rlt[0];
			return ($line['concept_pH_id_c']);
		} else {
			return (0);
		}
	}

	function terms_link($id) {
		$sql = "select * from wese_term
				
					where id_t = " . round($id);
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$line = $rlt[0];
		$sx = '<h2>' . $line['t_name'] . '</h2>';
		return ($sx);
	}

	function terms_association($id, $type) {
		$sql = "select * from  wese_label 
			inner join wese_concept on id_c = l_concept_id
			inner join wese_term on id_t = l_term
					where l_type = '$type' and c_id = '$id'
			order by t_name
			";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$sx = '<tr valign="top">';
		if ($type == 'ALT') { $sx .= '<td>' . msg('ALTERNATIVE LABEL') . '</td>';
		}
		if ($type == 'HIDDEN') { $sx .= '<td>' . msg('HIDDEN LABEL') . '</td>';
		}
		$sx .= '<td class="lt2">';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			if ($r > 0) { $sx .= '<br>';
			}
			$sx .= trim($line['t_name']) . ' (' . $line['t_lang'] . ')';
		}
		$sx .= '<hr size=1>';
		$sx .= '</td></tr>';
		return ($sx);
	}

	function association_term($term, $concept, $type) {

		$sql = "select * from wese_label 
					where l_term = $term ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();

		if (count($rlt) == 0) {
			$data = date("Y-m-d");
			$sql = "insert into  wese_label 
						(l_concept_id, l_term, l_type, l_update) 
								values 
							('$concept','$term','$type','$data')";
			$rlt = $this -> db -> query($sql);
		} else {
			echo 'Termo já associado ' . $type;
		}
		return (1);
	}

	function terms() {
		$sql = "select * from wese_term 
					left join  wese_label on l_term = id_t
					where id_l is null 		
						order by t_name ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();

		$sx = '<h2>' . msg('Alphabetical') . '</h2>';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$sx .= '<a href="' . base_url('index.php/skos/terms/' . $line['id_t']) . '" class="link lt3">';
			$sx .= $line['t_name'];
			$sx .= '</a>';
			$sx .= '<br>';
		}
		return ($sx);
	}

	/* SCHEME*/
	function scheme_set($scheme) {
		$sql = "select * from  wese_scheme where sh_initials = '$scheme' ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			$line = $rlt[0];
			$se = array();
			$se['scheme_id'] = $line['id_sh'];
			$se['sh_name'] = $line['sh_name'];
			$se['sh_initials'] = $line['sh_initials'];
			$se['sh_icone'] = $line['sh_icone'];
			$se['sh_link'] = $line['sh_link'];
			$this -> session -> set_userdata($se);
			return (1);
		}
		return (0);

	}

	function schemes() {
		$sql = "select * from  wese_scheme where sh_active = 1 order by sh_name ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$sx = '';
		$sx .= '<table width="1024" align="center">';
		$sx .= '<tr><td>';
		$sx .= '<h1>' . msg('scheme') . '</h1>';
		$sx .= '</td></tr>';

		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$link = '<a href="' . base_url('index.php/skos/scheme/' . $line['sh_initials']) . '" class="link">';
			$sx .= '<tr>';
			$sx .= '<td>';
			$sx .= $link . $line['sh_name'] . '</a>';
			$sx .= '</td>';
			$sx .= '</tr>';
		}
		return ($sx);
	}

	function resumo() {
		$sql = "select count(*) as total from wese_concept ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$line = $rlt[0];
		$concepts = $line['total'];

		$sql = "select count(*) as total from wese_term ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$line = $rlt[0];
		$terms = $line['total'];

		$sx = '<table width="100%">';
		$sx .= '<tr align="center">';
		$sx .= '<td>Concepts</td>';
		$sx .= '<td>Terms</td>';

		$sx .= '<tr align="center">';
		$sx .= '<td>' . $concepts . '</td>';
		$sx .= '<td>' . $terms . '</td>';
		$sx .= '</table>';

		return ($sx);
	}

}
?>
