<?php
class skoses extends CI_model {

	VAR $table_concept = 'th_concept';
	VAR $table_thesaurus = 'th_thesaurus';
	var $table_terms = 'rdf_literal';
	var $chave = 'pweio23908d09m09e8m';

	var $name = '';
	var $prefix = '';
	var $name_contact = '';
	var $name_contact_email = '';
	var $url = '';

	var $line = array();

	function __construct() {
		$sql = "select * from thesa where id_thesa = 1";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) == 0) {
			$this -> load -> view('skos/510', $data);
		} else {
			$line = $rlt[0];
			$this -> name = $line['thesa_name'];
			$this -> prefix = $line['thesa_prefix'];
			$this -> name_contact = $line['thesa_contact'];
			$this -> name_contact_email = $line['thesa_contact_email'];
			$this -> url = $line['thesa_url'];
		}
	}

	/* Recover ID Thesaurus */
	function th($th = '') {
		if (strlen($th) == 0) {
			if (!isset($_SESSION['skos'])) {
				redirect(base_url('index.php/skos'));
			}
			$th = $_SESSION['skos'];
		}
		return ($th);
	}

	/* Read Term */
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

	/* Read Thesaurus Descriptions */
	function le_skos($th) {
		$th = round(sonumero($th));
		$sql = "select * from " . $this -> table_thesaurus . " 
						WHERE id_pa = $th";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			$line = $rlt[0];
			return ($line);
		} else {
			/* Thesaurus Not Found */
			redirect(base_url('index.php/skos'));
		}
	}

	/* Read Concept */
	function le($id, $th = '') {
		$th = round(sonumero($th));
		$sql = "select * from " . $this -> table_concept . " 
					INNER JOIN th_concept_term ON ct_concept = id_c
					INNER JOIN rdf_literal ON id_rl = ct_term
					WHERE id_c = $id";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			$line = $rlt[0];
			return ($line);
		} else {
			return ( array());
		}
	}

	/* Read Concept */
	function le_c($id, $th = '') {
		$th = round(sonumero($th));
		$sql = "select * from " . $this -> table_concept . " 
					INNER JOIN th_concept_term ON ct_concept = id_c
					INNER JOIN rdf_literal ON id_rl = ct_term
					INNER JOIN th_thesaurus ON id_pa = ct_th
						WHERE ct_concept = $id and ct_th = $th and ct_propriety = 25";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			$line = $rlt[0];

			/* Read BT */
			$line['terms_bt'] = $this -> le_c_broader($id, $th);
			$line['terms_nw'] = $this -> le_c_narrowed($id, $th);
			$line['terms_al'] = $this -> le_c_altlabel($id, $th);
			$line['terms_tr'] = $this -> le_c_associate($id, $th);
			$line['terms_tm'] = $this -> le_c_propriety_group($id, $th, 'TE');
			$line['terms_hd'] = $this -> le_c_hidden($id, $th);
			$line['terms_ge'] = $this -> le_c_propriety($id, 'FE', $th);

			$line['allow'] = $this -> le_c_users($th);

			$line['notes'] = $this -> le_c_note($id, $th);
			$line['logs'] = $this -> log_show($id);

			return ($line);
		} else {
			return ( array());
		}
	}

	/*************************************************************************** FROM */
	function form_concept($th, $id, $gr = 'TE') {
		$th = round(sonumero($th));
		$sx = '';

		/* ASSOCIATION TYPE */
		$sql = "select * from rdf_resource
					inner join rdf_prefix on id_prefix = rs_prefix 
					where rs_group = '$gr' order by id_rs";

		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$sx .= '	<div class="row" style="margin-top: 12px;"><div class="col-md-10 col-md-offset-1">';
		$sx .= '<span class="font-size: 50%">' . msg('relation_type') . '</span>';
		$sx .= '	<select name="tr" size=4 style="width: 100%">';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$op = '';
			if ($line['id_rs'] == get("tr")) { $op = ' selected ';
			}
			$sx .= '	<option value="' . $line['id_rs'] . '" class="middle" ' . $op . '>' . msg($line['prefix_ref'] . ':' . $line['rs_propriety']) . '</option>';
		}
		$sx .= '	</select>';
		$sx .= '</div></div>';

		$sx .= '	<div class="row"><div class="col-md-10 col-md-offset-1">';
		$sx .= '<span class="font-size: 50%">' . msg('select_description') . '</span>';
		$sql = "SELECT * FROM `rdf_literal_th`
			INNER JOIN rdf_literal ON lt_term = id_rl
			LEFT JOIN th_concept_term on  id_rl = ct_term and ct_th = $th
			where lt_thesauros = $th and id_ct is null
			order by rl_value";

		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$sx .= '	<select name="tm" size=8 style="width: 100%">';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$op = '';
			if ($line['id_rl'] == get("tm")) { $op = ' selected ';
			}

			$sx .= '	<option value="' . $line['id_rl'] . '" class="middle" ' . $op . '>' . $line['rl_value'] . '</option>';
		}
		$sx .= '	</select>';
		$sx .= '</div></div>';

		$sx .= '<div class="row" style="margin-top: 12px;"><div class="col-md-10 col-md-offset-1 text-right">';
		$sx .= '	<input type="submit" name="action" class="btn btn-default " value="' . msg('save') . '">';
		$sx .= '</div></div>';
		return ($sx);
		return ($sx);

	}

	/*************************************************************************** FROM */
	function form_concept_tf($th, $id) {
		$th = round(sonumero($th));
		$sx = '	<div class="row"><div class="col-md-10 col-md-offset-1">';
		$sx .= '<span class="font-size: 50%">' . msg('description') . '</span>';
		$sx .= '	<textarea name="nt" rows="8" style="width: 100%">' . get("nt") . '</textarea>';
		$sx .= '</div></div>';

		/* LANGUAGE */
		$sql = "select * from language order by lg_order ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$sx .= '	<div class="row" style="margin-top: 12px;"><div class="col-md-10 col-md-offset-1">';
		$sx .= '	<select name="tt" size=1 style="width: 100%">';
		$sx .= '	<option value="" class="middle">::: ' . msg('select_the_relation') . '</option>';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$op = '';
			if ($line['lg_code'] == get("tt")) { $op = ' selected ';
			}
			$sx .= '	<option value="' . $line['lg_code'] . '" class="middle" ' . $op . '>' . msg($line['lg_language']) . '</option>';
		}
		$sx .= '	</select>';
		$sx .= '</div></div>';

		/* ASSOCIATION TYPE */
		$sql = "select * from rdf_resource
					inner join rdf_prefix on id_prefix = rs_prefix 
					where rs_group = 'NT' order by id_rs";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$sx .= '	<div class="row" style="margin-top: 12px;"><div class="col-md-10 col-md-offset-1">';
		$sx .= '	<select name="tr" size=4 style="width: 100%">';
		$sx .= '	<option value="" class="middle">::: ' . msg('select_the_relation') . '</option>';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$op = '';
			if ($line['id_rs'] == get("tr")) { $op = ' selected ';
			}
			$sx .= '	<option value="' . $line['id_rs'] . '" class="middle" ' . $op . '>' . msg($line['prefix_ref'] . ':' . $line['rs_propriety']) . '</option>';
		}
		$sx .= '	</select>';
		$sx .= '</div></div>';

		$sx .= '<div class="row" style="margin-top: 12px;"><div class="col-md-10 col-md-offset-1 text-right">';
		$sx .= '	<input type="submit" name="action" class="btn btn-default " value="' . msg('save') . '">';
		$sx .= '</div></div>';
		return ($sx);
		return ($sx);

	}

	/*************************************************************************** FROM */
	function form_concept_tg($th, $id) {
		$th = round(sonumero($th));
		$sql = "select rl_value, id_c from " . $this -> table_concept . " 
					INNER JOIN th_concept_term ON ct_concept = id_c
					INNER JOIN rdf_literal ON id_rl = ct_term
						WHERE  ct_th = $th and ct_propriety = 25
								AND id_c <> $id
					ORDER BY rl_value";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$sx = '	<div class="row"><div class="col-md-10 col-md-offset-1">';
		$sx .= '	<select name="tg" size=10 style="width: 100%">';
		$sx .= '	<option value="" class="middle">::: ' . msg('select_a_concept') . '</option>';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$sx .= '	<option value="' . $line['id_c'] . '" class="middle">' . $line['rl_value'] . '</option>';
		}
		$sx .= '	</select>';
		$sx .= '</div></div>';
		$sx .= '<div class="row" style="margin-top: 12px;"><div class="col-md-10 col-md-offset-1 text-right">';
		$sx .= '	<input type="submit" name="action" class="btn btn-default " value="' . msg('save') . '">';
		$sx .= '</div></div>';
		return ($sx);

	}

	/*************************************************************************** FROM */
	function form_concept_tr($th, $id) {
		$th = round(sonumero($th));
		$sql = "select rl_value, id_c from " . $this -> table_concept . " 
					INNER JOIN th_concept_term ON ct_concept = id_c
					INNER JOIN rdf_literal ON id_rl = ct_term
						WHERE  ct_th = $th and ct_propriety = 25
								AND id_c <> $id
					ORDER BY rl_value";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$sx = '	<div class="row"><div class="col-md-10 col-md-offset-1">';
		$sx .= '	<select name="tg" size=10 style="width: 100%">';
		$sx .= '	<option value="" class="middle">::: ' . msg('select_a_concept') . '</option>';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$sx .= '	<option value="' . $line['id_c'] . '" class="middle">' . $line['rl_value'] . '</option>';
		}
		$sx .= '	</select>';
		$sx .= '</div></div>';

		/* ASSOCIATION TYPE */
		$sql = "select * from rdf_resource
					inner join rdf_prefix on id_prefix = rs_prefix 
					where rs_group = 'TR' order by id_rs";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$sx .= '	<div class="row" style="margin-top: 12px;"><div class="col-md-10 col-md-offset-1">';
		$sx .= '	<select name="tgr" size=4 style="width: 100%">';
		$sx .= '	<option value="" class="middle">::: ' . msg('select_the_relation') . '</option>';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$sx .= '	<option value="' . $line['id_rs'] . '" class="middle">' . msg($line['prefix_ref'] . ':' . $line['rs_propriety']) . '</option>';
		}
		$sx .= '	</select>';
		$sx .= '</div></div>';

		$sx .= '<div class="row" style="margin-top: 12px;"><div class="col-md-10 col-md-offset-1 text-right">';
		$sx .= '	<input type="submit" name="action" class="btn btn-default " value="' . msg('save') . '">';
		$sx .= '</div></div>';
		return ($sx);

	}

	function le_c_broader($id = '', $th = '') {
		$th = round(sonumero($th));
		$sql = "select * from th_concept_term as t1
					INNER JOIN th_concept_term as t2 ON t1.ct_concept = t2.ct_concept and t2.ct_propriety = 25 
					INNER JOIN rdf_literal ON id_rl = t2.ct_term
						WHERE t1.ct_concept_2 = $id and t1.ct_th = $th and t1.ct_propriety = 26";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			return ($rlt);
		} else {
			return ( array());
		}
	}

	function le_c_propriety_group($id = '', $th = '', $prop = '') {
		$th = round(sonumero($th));
		$sql = "select * from (
					SELECT ct_concept_2 as idc, ct_propriety as ctp FROM `th_concept_term` 
					INNER JOIN rdf_resource ON id_rs = ct_propriety
					WHERE rs_group = '$prop' and ct_concept = $id and ct_th = $th 
   				 ) as tb1
			 INNER JOIN th_concept_term ON ct_concept = idc and ct_propriety = 25
			 INNER JOIN rdf_literal ON ct_term = id_rl
			 LEFT JOIN rdf_resource ON id_rs = ctp
			 LEFT JOIN rdf_prefix on id_prefix = rs_prefix
			 ORDER BY rl_value";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();

		if (count($rlt) > 0) {
			/* Read TR */
			return ($rlt);
		} else {
			return ( array());
		}

	}

	function le_c_propriety($id = '', $prop, $th = '') {
		$th = round(sonumero($th));
		$sql = "select * from th_concept_term as t1
					INNER JOIN rdf_literal ON id_rl = t1.ct_term
					INNER JOIN rdf_resource ON id_rs = t1.ct_propriety
					INNER JOIN rdf_prefix on id_prefix = rs_prefix
						WHERE t1.ct_concept = $id and t1.ct_th = $th and rs_group = '$prop'
					order by rs_group ";

		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			/* Read BT */
			return ($rlt);
		} else {
			return ( array());
		}
	}

	function le_c_associate($id = '', $th = '') {
		$th = round(sonumero($th));
		$sql = "select * from (
					SELECT ct_concept_2 as idc, ct_propriety as ctp FROM `th_concept_term` 
					INNER JOIN rdf_resource ON id_rs = ct_propriety
					WHERE rs_group = 'TR' and ct_concept = $id and ct_th = $th 
   				 ) as tb1
			 INNER JOIN th_concept_term ON ct_concept = idc and ct_propriety = 25
			 INNER JOIN rdf_literal ON ct_term = id_rl
			 LEFT JOIN rdf_resource ON id_rs = ctp
			 LEFT JOIN rdf_prefix on id_prefix = rs_prefix
			 ORDER BY rl_value";
		$rlt = $this -> db -> query($sql);
		$rlt1 = $rlt -> result_array();

		/* PARTE II */
		$sql = "select * from (
					SELECT ct_concept as idc, ct_propriety as ctp FROM `th_concept_term` 
					INNER JOIN rdf_resource ON id_rs = ct_propriety
					WHERE rs_group = 'TR' and ct_concept_2 = $id and ct_th = $th 
   				 ) as tb1
			 INNER JOIN th_concept_term ON ct_concept = idc and ct_propriety = 25
			 INNER JOIN rdf_literal ON ct_term = id_rl
			 LEFT JOIN rdf_resource ON id_rs = ctp
			 LEFT JOIN rdf_prefix on id_prefix = rs_prefix			 
			 ORDER BY rl_value";

		$rlt = $this -> db -> query($sql);
		$rlt2 = $rlt -> result_array();

		$rlt = array_merge($rlt1, $rlt2);

		if (count($rlt) > 0) {
			/* Read TR */
			return ($rlt);
		} else {
			return ( array());
		}
	}

	function le_c_narrowed($id = '', $th = '') {
		$th = round(sonumero($th));
		$sql = "select * from th_concept_term as t1
					INNER JOIN th_concept_term as t2 ON t1.ct_concept_2 = t2.ct_concept and t2.ct_propriety = 25 
					INNER JOIN rdf_literal ON id_rl = t2.ct_term
						WHERE t1.ct_concept = $id and t1.ct_th = $th and t1.ct_propriety = 26";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			/* Read BT */
			return ($rlt);
		} else {
			return ( array());
		}
	}

	function le_c_note($id = '', $th = '') {
		$th = round(sonumero($th));
		$sql = "select * from rdf_literal_note
					INNER JOIN rdf_resource ON id_rs = rl_type
					INNER JOIN rdf_prefix ON id_prefix = rs_prefix
						WHERE rl_c = " . $id;
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			/* Read BT */
			return ($rlt);
		} else {
			return ( array());
		}
	}

	function le_c_altlabel($id = '', $th = '') {
		$th = round(sonumero($th));
		$sql = "select * from th_concept_term as t1
					INNER JOIN rdf_literal ON id_rl = t1.ct_term
					INNER JOIN rdf_resource ON id_rs = t1.ct_propriety
					LEFT JOIN rdf_prefix ON id_prefix = rs_prefix
						WHERE t1.ct_concept = $id and t1.ct_th = $th and rs_group = 'TE'";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			/* Read BT */
			return ($rlt);
		} else {
			return ( array());
		}
	}

	function le_c_hidden($id = '', $th = '') {
		$th = round(sonumero($th));
		$sql = "select * from th_concept_term as t1
					INNER JOIN rdf_literal ON id_rl = t1.ct_term
						WHERE t1.ct_concept = $id and t1.ct_th = $th and t1.ct_propriety = " . $this -> TH;
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			/* Read BT */
			return ($rlt);
		} else {
			return ( array());
		}
	}

	function le_tree($th = 0) {
		$th = round(sonumero($th));
		$sql = "select l1.id_rl as id1, l1.rl_value as lt1,
					   l2.id_rl as id2, l2.rl_value as lt2
					FROM th_concept_term as t1
					INNER JOIN th_concept_term as t2 ON t1.ct_concept_2 = t2.ct_concept and t2.ct_propriety = " . $this -> CO . "
					INNER JOIN th_concept_term as t3 ON t1.ct_concept = t3.ct_concept and t3.ct_propriety = " . $this -> CO . " 
					INNER JOIN rdf_literal as l1 ON l1.id_rl = t2.ct_term
					LEFT JOIN rdf_literal as l2 ON l2.id_rl = t3.ct_term
						WHERE t1.ct_th = $th and t1.ct_propriety = " . $this -> TG;
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$desc = array();
		$h = array();
		if (count($rlt) > 0) {
			/* Read BT */
			$tr = array();
			for ($r = 0; $r < count($rlt); $r++) {
				$line = $rlt[$r];

				/* Lista dos descritores */
				$id1 = $line['id1'];
				$desc[$id1] = $line['lt1'];
				$id2 = $line['id2'];
				$desc[$id2] = $line['lt2'];

				$h[$id2]['name'] = $line['lt2'];
				$h[$id2][$id1]['name'] = $line['lt1'];

				$tr[$id1] = $id2;
			}

			$tt = 'id,value' . cr();
			$i = 0;
			foreach ($tr as $v1 => $v2) {
				$tx = '';
				$vm = $v2;
				$vb = $v1;
				$tx = '[' . $vm . '][' . $vb . ']';
				$esc = 50;

				while ((isset($tr[$vm])) and ($esc-- > 0)) {
					$vm = $tr[$vm];
					$tx = '[' . $vm . ']' . $tx;
				}
				if ($i == 0) {
					$tt .= $desc[$vm] . cr();
					$i++;
				}
				$tt .= $tx . ',1' . cr();
			}

			$tc = $tt;
			foreach ($desc as $key => $value) {
				$tt = troca($tt, '[' . $key . ']', $value . '.');
			}
			$tt = troca($tt, '.,', ',');
			//$tt = troca($tt,' ','');

			$tt = lowercase($tt);
			$file = fopen('xml/flare_' . $th . '.csv', 'w+');
			fwrite($file, $tt) / fclose($file);

			redirect(base_url('index.php/skos/th'));

			return ($rlt);
		} else {
			return ( array());
		}
	}

	function le_report($th = 0) {
		$th = round(sonumero($th));
		$sql = "select *
					FROM th_concept_term 
					INNER JOIN rdf_literal ON id_rl = ct_term
						WHERE ct_th = $th and ct_propriety = " . $this -> CO . "
					ORDER BY rl_value
				";
		$rlt = $this -> db -> query($sql);
		echo $sql;
		$rlt = $rlt -> result_array();
		$desc = array();
		$h = array();
		for ($r = 0; $r < count($rlt); $r++) {
			$data = $rlt[$r];
			/* Recupera informações sobre o Concecpt */
			$c = $data['ct_concept'];
			$data2 = $this -> skoses -> le_c($c, $th);
			$this -> load -> view("skos/report_conecpt", $data2);
		}
	}

	function le_c_users($th = '') {
		$sql = "select * from th_users where ust_th = $th and ust_status = 1";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$rs = array();
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$id = $line['ust_user_id'];
			$rl = $line['ust_user_role'];
			$rs[$id] = $rl;
		}
		return ($rs);
	}

	function le_tree_js() {
		return ('');
	}

	function le_th($id = 0) {
		$sql = "select * from " . $this -> table_thesaurus . " where id_pa = " . $id;
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			$line = $rlt[0];
			return ($line);
		}
		return ( array());
	}

	/* Show */
	function concepts_show($row) {
		$sx = '<ul>' . cr();
		for ($r = 0; $r < count($row); $r++) {
			$line = $row[$r];

			if ($line['ct_concept'] > 0) {
				$sx .= '<li class="term_item">';
				if (isset($line['rs_propriety'])) {
					$sx .= '<span style="font-size: 75%;">' . msg($line['prefix_ref'] . ':' . $line['rs_propriety']) . ' - </span>';
				}
				$link = '<a href="' . base_url('index.php/skos/c/' . $line['ct_concept']) . '">';
				$sx .= $link . $line['rl_value'] . '</a> <sup class="supersmall">(' . $line['rl_lang'] . ')</sup>';

				$sx .= ' ';
				$link = base_url('index.php/skos/te_remove/' . $line['id_ct'] . '/' . checkpost_link($line['id_ct'] . $this -> chave));
				$sx .= '<a href="#" style="color: red" title="Remove" onclick="newwin(\'' . $link . '\',600,300);">';
				$sx .= '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>';
				$sx .= '</a>';

				$sx .= '</li>' . cr();
			}

		}
		$sx .= '</ul>' . cr();
		return ($sx);
	}

	function te_remote($id) {
		$sql = "delete from th_concept_term where id_ct = " . round($id);
		$this -> db -> query($sql);
	}

	function concepts_show_rp($row, $tp = '') {
		$sx = '<ul>' . cr();
		for ($r = 0; $r < count($row); $r++) {
			$line = $row[$r];

			if ($line['ct_concept'] > 0) {
				$sx .= '<li class="term_item">';
				$sx .= '<b>' . $tp . '</b> ';
				$sx .= '<tt>' . $line['rl_value'] . '</tt>' . ' <sup class="supersmall">(' . $line['rl_lang'] . ')</sup>';
				$sx .= '</li>' . cr();
			}

		}
		$sx .= '</ul>' . cr();
		return ($sx);
	}

	/* Export to json */
	function to_json($id) {
		$id = round($id);
		$data = $this -> le($id);
		print_r($data);
	}

	function notes_show($l) {
		$sx = '';
		for ($r = 0; $r < count($l); $r++) {
			$line = $l[$r];

			$sx .= '<span style="font-size: 75%;">' . msg($line['prefix_ref'] . ':' . $line['rs_propriety']) . '</span>
					<p>' . $line['rl_value'] . '</p>';
			$sx .= '<hr>';
		}
		return ($sx);
	}

	/* MY Thesaurus */
	function th_assosiation_users() {
		$sql = "select *
						FROM th_thesaurus
						LEFT JOIN th_users ON ust_th = id_pa
							where ust_user_id is null ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$th = $line['id_pa'];
			$user = $line['pa_creator'];
			$tipo = 'INS';
			$this -> skoses -> user_thesa($th, $user, $tipo);
		}
	}

	function myskoses($user = 0) {
		if ($user == 0) {
			$sql = "select * from th_thesaurus
					where pa_status = 2 
					order by pa_name ";
		} else {
			$sql = "select id_pa, pa_name, pa_created, pa_status, pa_avaliacao, pa_creator
						FROM th_thesaurus
						LEFT JOIN th_users ON ust_th = id_pa
							where (pa_creator = $user) or (ust_user_id = $user)
							group by  id_pa, pa_name, pa_created, pa_status, pa_avaliacao, pa_creator
							order by pa_status desc, pa_name ";
		}
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$sx = '';
		$sx .= '<table width="100%" class="table">';
		$sx .= '<tr class="small">
					<td>' . msg('description') . '</td>';
		$sx .= '<td>' . msg('last_update') . '</td>';
		$sx .= '<td>' . msg('status') . '</td>';
		$sx .= '<td>' . msg('avaliation') . '</td>';
		$sx .= '</tr>' . cr();
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$link = '<a href="' . base_url('index.php/skos/select/' . $line['id_pa'] . '/' . checkpost_link($line['id_pa'])) . '">';
			$sx .= '<tr>';
			$sx .= '<td>';
			$sx .= $link . $line['pa_name'] . '</a>';
			$sx .= '</td>';
			$sx .= '<td>';
			$sx .= $link . stodbr($line['pa_created']) . '</a>';
			$sx .= '</td>';
			$sx .= '<td>';
			$btn = 'btn-default';
			switch ($line['pa_status']) {
				case '2' :
					$btn = 'btn-warning';
					break;
			}
			$sx .= '<span class="btn ' . $btn . ' small">' . msg('status_' . $line['pa_status']) . '</span>';
			$sx .= '</td>';
			$sx .= '<td align="center">';
			$av = $line['pa_avaliacao'];
			if ($av == 0) {
				$av = "sem avaliação";
			} else {
				$av = '<span class="btn btn-default">' . $av . '</span>';
			}
			$sx .= $link . $av . '</a>';
			$sx .= '</td>';
			//$sx .= '<td>'.$_SESSION['id'];
			if (isset($_SESSION['id'])) {
				if ($line['pa_creator'] == $_SESSION['id']) {
					$sx .= '<td>';
					$sx .= '<a href="' . base_url('index.php/skos/th_edit/' . $line['id_pa'] . '/' . checkpost_link($line['id_pa'] . $this -> chave)) . '" class="btn btn-default">' . msg('edit') . '</a>';
					$sx .= '</td>';
				}
			}
			$sx .= '</tr>';
		}
		if (count($rlt) == 0) {
			$sx .= '<tr><td colspan=6><span class="btn alert-danger">' . msg('not_record') . '</span></td></tr>';
		}
		$sx .= '</table>';
		return ($sx);
	}

	/* Select SKOS */
	function skoses_select($id) {
		$data = array();
		$data['skos'] = $id;
		$data['skos-md5'] = 'skos' . $id;
		$this -> session -> set_userdata($data);
		return (1);
	}

	function termos_pg($id, $ed = '1') {
		$sql = "select substr(rl_value,1,1) as letra 
					FROM th_concept_term 
					INNER JOIN rdf_literal ON id_rl = ct_term
					WHERE ct_th = $id 
					GROUP BY letra
					ORDER BY letra
					";
		$xrlt = $this -> db -> query($sql);
		$xrlt = $xrlt -> result_array();
		$sx = '<nav aria-label="Page navigation text-center"><ul class="pagination">';
		$sx .= '<li><a href="' . base_url('index.php/skos/terms/' . $id . '/') . '">' . msg('all') . '</a></li>';
		for ($r = 0; $r < count($xrlt); $r++) {
			$line = $xrlt[$r];
			$sx .= '<li><a href="' . base_url('index.php/skos/terms/' . $id . '/' . $line['letra']) . '">' . $line['letra'] . '</a></li>';
		}

		if (($ed == 1) and ($this -> autho('', $id))) {
			$sx .= '<li><a href="' . base_url('index.php/skos/concept_add/' . $id . '/') . '">' . msg('add') . '</a></li>';
		}
		$sx .= '</ul></nav>	';
		return ($sx);
	}

	/* RESUMO DO THESAURUS */
	function thesaurus_resume($id) {
		$sql = "select count(*) as total from rdf_literal_th where lt_thesauros = $id";

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
		$sql = "select count(*) as total from th_concept_term 
					where ct_th = $id and ct_propriety = " . $this -> CO;

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

	/* Incorpore terns intro thesaurus */
	function incorpore_terms($t = '', $id = '', $lang = '', $lc = '') {
		$th = $_SESSION['skos'];
		$t = troca($t, chr(13), ';');
		$t = troca($t, chr(10), ';');
		//$t = troca($t, '.', ';');
		$t = troca($t, '"', ' ');
		//$t = troca($t, ',', ';');
		$t = troca($t, '?', ';');
		$ln = splitx(';', $t);
		$sx = '';
		for ($r = 0; $r < count($ln); $r++) {
			$t = $ln[$r];
			$t = $this -> prepara_termo($t, $lc);
			if (strlen($t) > 0) {
				/* Incorpore Term intro Vocabulary Literal */
				$this -> terms_add($t, $id, $lang);

				/* Insert Term intro the Thesaurus */
				$idt = $this -> association_term_th($t, $lang, $id);

				/* Create Concept Into the Thesaurus */
				$this -> concept_create($t, $th);
			}
		}
		return ($sx);
	}

	function delete_term_from_th($th, $idt) {
		$sql = "select * from rdf_literal_th where lt_term = $idt and lt_thesauros = $th";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			$sql = "delete from rdf_literal_th where lt_term = $idt and lt_thesauros = $th";
			$rlt = $this -> db -> query($sql);
			return (1);
		} else {
			return (0);
		}
	}

	function terms_add($t = '', $id = '', $lang = '') {
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

	Function prepara_termo($t = '', $lc = '') {
		$t = utf8_decode($t);
		if (strlen($lc) > 0) {
			$t = LowerCase($t);
			$t = UpperCase(substr($t, 0, 1)) . substr($t, 1, strlen($t));
			if (substr($t, 0, 1) == '#') {
				$t = UpperCase(substr($t, 1, strlen($t)));
			}
		}
		$t = utf8_encode($t);
		return ($t);
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

	function recupera_termo_id($t) {
		$t = round($t);
		$sql = "select * from rdf_literal where id_rl = $t limit 1";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			$line = $rlt[0];
			return ($line['id_rl']);
		} else {
			return (0);
		}
	}

	function recupera_termo($t) {
		$sql = "select * from rdf_literal where rl_value = '$t' limit 1";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			$line = $rlt[0];
			return ($line['id_rl']);
		} else {
			return (0);
		}
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

	function cp_th_new($us = '') {
		$cp = array();
		array_push($cp, array('$H8', 'id_pa', '', false, true));
		array_push($cp, array('$S80', 'pa_name', msg('thesaurus_name'), true, true));
		array_push($cp, array('$T80:5', 'pa_description', msg('thesaurus_description'), false, true));
		array_push($cp, array('$HV', 'pa_status', '1', true, true));
		array_push($cp, array('$HV', 'pa_classe', 1, true, true));
		array_push($cp, array('$HV', 'pa_creator', $us, true, true));

		array_push($cp, array('$B8', '', msg('save'), false, true));
		return ($cp);
	}

	function cp_th($id = '') {
		$cp = array();
		array_push($cp, array('$H8', 'id_pa', '', true, true));
		array_push($cp, array('$S80', 'pa_name', msg('thesaurus_name'), true, true));
		array_push($cp, array('$T80:5', 'pa_description', msg('thesaurus_description'), false, true));

		$ops = '1:' . msg('status_1');
		$ops .= '&2:' . msg('status_2');
		array_push($cp, array('$O ' . $ops, 'pa_status', msg('thesaurus_status'), true, true));
		array_push($cp, array('$B8', '', msg('save'), false, true));
		return ($cp);
	}

	function cp_term($id = '') {
		$cp = array();
		array_push($cp, array('$H8', 'id_rl', '', true, true));
		array_push($cp, array('$S80', 'rl_value', msg('term_name'), true, true));

		$sql = "select * from language order by lg_order";
		array_push($cp, array('$Q  lg_code:lg_language:' . $sql, 'rl_lang', msg('language'), true, true));
		array_push($cp, array('$B8', '', msg('save'), false, true));
		return ($cp);
	}

	function concept_chage($c = '', $t1 = '', $t2 = '', $th = '') {
		$sql = "select * from th_concept_term 
					INNER JOIN rdf_literal ON id_rl = ct_term
						where ct_concept = $c
							AND ct_th = $th
							AND ct_term = $t2 ";
		$rlt1 = $this -> db -> query($sql);
		$rlt1 = $rlt1 -> result_array();
		if (count($rlt1) == 1) {
			$rlt1 = $rlt1[0];
		}

		$sql = "select * from th_concept_term 
					INNER JOIN rdf_literal ON id_rl = ct_term
						where ct_concept = $c
							AND ct_th = $th
							AND ct_term = $t1 ";
		$rlt2 = $this -> db -> query($sql);
		$rlt2 = $rlt2 -> result_array();
		if (count($rlt2) == 1) {
			$rlt2 = $rlt2[0];
		}

		if ((count($rlt2) > 0) and (count($rlt1) > 0)) {
			$sql = "update th_concept_term set ct_term = $t2 where id_ct = " . $rlt2['id_ct'] . ';';
			$rlt = $this -> db -> query($sql);

			$sql = "update th_concept_term set ct_term = $t1 where id_ct = " . $rlt1['id_ct'] . ';';
			$rlt = $this -> db -> query($sql);

			$desc = msg('change') . ' <b>' . $rlt2['rl_value'] . '</b> ' . msg('has_prefTerm') . ', ' . msg('change_old') . ' (<i>' . $rlt1['rl_value'] . '</i>)';
			$this -> skoses -> log_insert($c, $th, 'CHG', $desc);
			return (1);
		}
		return (0);
	}

	function concept_create($t, $th) {
		$data = date("Y-m-d");

		/* Validação */
		if (sonumero($t) == $t) {
			$idt = $this -> skoses -> recupera_termo_id($t);
		} else {
			$idt = $this -> skoses -> recupera_termo($t);
		}

		if ($idt == 0) {
			echo 'OPS 212 - Termo not found - ' . $t;
			return ('');
			exit ;
		}

		/* Verificar se já não existe o conceito no thesaurus */
		$sql = "select * from th_concept_term where ct_term = $idt and ct_th = $th";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) == 0) {

			/* locate next concept ID */
			$sql = "select id_c from th_concept order by id_c desc limit 1 ";
			$rlt = $this -> db -> query($sql);
			$rlt = $rlt -> result_array();
			if (count($rlt) == 0) {
				$idc = 0;
			} else {
				$line = $rlt[0];
				$idc = sonumero($line['id_c']);
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
			return ($idc);
		}
		return (1);
	}

	function concept_show($c) {
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
				if ($line['c2'] > 0) {
					$link = '<a href="' . base_url('index.php/skos/c/' . $line['c2']) . '">';
				}
				$sx .= '<tr>';

				$sx .= '<td width="30%" align="right" class="small">';
				$sx .= msg(trim($line['prefix_ref']) . ':' . trim($line['rs_propriety_inverse']));
				$sx .= '</td>';

				$sx .= '<td width="70%" class="middle">';
				$sx .= '<b>' . $link . trim($line['l1']) . '</a>' . '</b>';
				$sx .= '</td>';
				$sx .= '</tr>';
			}
		}

		$sx .= '</table>';
		return ($sx);

	}

	function autho($id = '', $th) {
		if (strlen($id) == 0) {
			if (isset($_SESSION['id']))
				$id = $_SESSION['id'];
		}
		if (strlen($id) == 0) {
			return (0);
		}

		$sql = "select * from th_users 
						WHERE ust_user_id = $id
						AND ust_th = $th
						AND ust_status = 1";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			return (1);
		} else {
			return (0);
		}
	}

	function termos_show_letter($th, $ltr) {
		if (strleN($ltr) == 0) {
			$wh = '';
		} else {
			$wh = " D1.rl_value like '$ltr%' and ";
		}

		/* IS AUTH TO EDIT */
		$auth = 0;
		if (isset($_SESSION['user_id']) > 0) {
			$auth = $this -> autho($id);
		}
		if ($auth == 1) {
			echo '<br><br><br><br>RULE 1';
			$sql = "select * from rdf_literal
						INNER JOIN rdf_literal_th as D1 ON lt_term = id_rl 
						LEFT JOIN th_concept_term ON ct_term = id_rl and ct_th = $id
						WHERE lt_thesauros = $th and rl_type = 24
							AND $wh lt_thesauros = $th 
							ORDER BY rl_value
						";
		} else {
			//echo '<br><br><br><br>RULE 2';
			$sql = "SELECT id_rl, ct_concept, ct_propriety, rl_value, rl_lang, 
								rs_propriety , rs_public , '' as altTerm
							FROM th_concept_term 
							INNER JOIN rdf_literal AS D1 ON ct_term = id_rl
							INNER JOIN rdf_resource ON ct_propriety = id_rs
							WHERE 
								$wh
								ct_th = $th and rs_public = 1 and ct_propriety = " . $this -> CO . "
						";
			$sql .= " UNION ";
			$sql .= "SELECT D1.id_rl, T1.ct_concept, T1.ct_propriety, D1.rl_value, D1.rl_lang, 
								rs_propriety , rs_public , D2.rl_value as altTerm
							FROM th_concept_term as T1
							INNER JOIN th_concept_term as T2 ON (T1.ct_concept = T2.ct_concept) and (T2.ct_propriety = 25)
						    INNER JOIN rdf_resource ON T1.ct_propriety = id_rs						    
						    INNER JOIN rdf_literal as D1 ON T1.ct_term = D1.id_rl
						    INNER JOIN rdf_literal as D2 ON T2.ct_term = D2.id_rl
						    
						WHERE 
								$wh
								T1.ct_th = $th and 
								T2.ct_th = $th and 
								rs_public = 1 and 
								T1.ct_propriety <> 25 
								
						";
			$sql .= " ORDER BY rl_value";
			//echo '<pre>'.$sql.'</pre>';

		}
		$xrlt = $this -> db -> query($sql);
		$xrlt = $xrlt -> result_array();
		$cols = 1;
		$terms = count($xrlt);
		if ($terms > 2) { $cols = 2;
		}
		if ($terms > 30) { $cols = 3;
		}

		$sx = '<div class="row" style="-moz-column-count: ' . $cols . '; -webkit-column-count: ' . $cols . ';">' . cr();
		$sx .= '<ul class="thesa">';
		$lt = '';
		for ($r = 0; $r < count($xrlt); $r++) {
			$line = $xrlt[$r];
			$lta = substr($line['rl_value'], 0, 1);
			if ($lta != $lt) {
				if (strlen($lt) != '') {
					$sx .= '<li>&nbsp;</li>' . cr();
				}
				$sx .= '<li class="big">' . $lta . '</li>' . chr(13);
				$lt = $lta;
			}
			$sa = '';
			$saf = '';
			$link = '<a href="' . base_url('index.php/skos/term/' . $th . '/' . $line['id_rl']) . '" class="term_word">';
			if (round($line['ct_propriety']) == $this -> CO) {
				$sa = '<span class="glyphicon glyphicon-tag" aria-hidden="true"></span>';
				$link = '<a href="' . base_url('index.php/skos/c/' . $line['ct_concept']) . '/' . $th . '/" class="term">';
			} else {
				if (strlen(trim($line['altTerm'])) > 0) {
					$link = '<a href="' . base_url('index.php/skos/c/' . $line['ct_concept']) . '/' . $th . '/" class="term">';
					$sa = '<i>';
					$saf = '</i> -> ' . $line['altTerm'];
				}
			}
			$sx .= '<li>' . $link . $sa . ' ' . $line['rl_value'];
			//$sx .= ' ('.$line['ct_propriety'].')';
			$sx .= $saf;
			$sx .= '</a></li>';
		}
		$sx .= '</ul>' . cr();
		$sx .= '</div>';
		return ($sx);
	}

	/* ASSIGNS */
	function assign_as_narrower($c1, $c2, $th, $tm) {
		/* Verifica */
		$sql = "select * from th_concept_term 
						WHERE (ct_concept_2 = $c2 and ct_propriety = " . $this -> TG . ")";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) == 0) {
			$sql = "insert into th_concept_term
							(
								ct_concept, ct_th, ct_term, 
								ct_concept_2, ct_propriety
							) values (
								$c1, $th, $tm,
								$c2,'.$this->TG.'
							)";
		}
		$rlt = $this -> db -> query($sql);
	}

	function assign_as_note($c, $th, $type, $texto, $language) {
		$sql = "insert into rdf_literal_note
					(
						rl_type, rl_value, rl_lang, rl_c
					) values (
						$type, '$texto', '$language', $c
					)";
		$rlt = $this -> db -> query($sql);
	}

	function assign_as_propriety($c, $th, $tr, $desc) {
		/* Verifica */
		$c2 = 0;
		$sql = "select * from th_concept_term 
						WHERE (ct_term = $desc and ct_th = $th)";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) == 0) {
			$sql = "insert into th_concept_term
							(
								ct_concept, ct_th, ct_term, 
								ct_concept_2, ct_propriety
							) values (
								$c, $th, $desc,
								$c2,'.$tr.'
							)";
		}	$rlt = $this -> db -> query($sql);
	}

	function assign_as_relation($c1, $c2, $th, $tr) {
		/* Verifica */
		$tm = 0;
		$sql = "select * from th_concept_term 
						WHERE (ct_concept_2 = $c2 and ct_propriety = $tr)";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) == 0) {
			$sql = "insert into th_concept_term
							(
								ct_concept, ct_th, ct_term, 
								ct_concept_2, ct_propriety
							) values (
								$c1, $th, $tm,
								$c2,'.$tr.'
							)";
		}	$rlt = $this -> db -> query($sql);
	}

	function glossario($th = '') {
		$sql = "select r1.rl_value as rl1, r2.rl_value as rl2,note0.rs_propriety as prop,
						t1.ct_concept as c, note.rl_value as note, 
						note2.rs_propriety as prop_note, pre2.prefix_ref as prop_pre
					FROM th_concept_term as t1						
						INNER JOIN rdf_literal AS r1 ON id_rl = t1.ct_term 
						INNER JOIN rdf_resource as note0 ON id_rs = t1.ct_propriety
						LEFT JOIN th_concept_term as t2 on t1.ct_concept = t2.ct_concept and t2.ct_propriety = 25
						LEFT JOIN rdf_literal AS r2 ON r2.id_rl = t2.ct_term 
						LEFT JOIN rdf_literal_note as note ON rl_c = t1.ct_concept
						LEFT JOIN rdf_resource as note2 ON note.rl_type = note2.id_rs
						LEFT JOIN rdf_prefix as pre2 ON note2.rs_prefix = pre2.id_prefix
						WHERE t1.ct_th = $th and 
							(note0.rs_group = 'LABEL' or note0.rs_group = 'TE' or note0.rs_group = 'FE')  
						ORDER BY r1.rl_value";

		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();

		$sx = '<ul style="list-style-type: none;">' . cr();
		$ltr = '';
		$xc = '';
		$ix = 0;
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$xltr = substr($line['rl1'], 0, 1);
			$c = $line['c'];
			if ($xltr != $ltr) {
				if ($ix > 0) { $sx .= '</li>' . cr();
					$ix = 0;
				}
				$ltr = $xltr;
				$sx .= '<span class="big">~' . substr($ltr, 0, 1) . '~</span>';
			}
			$link = '<a href="' . base_url('index.php/skos/c/' . $line['c']) . '">';

			if ($c != $xc) {
				if ($ix > 0) { $sx .= '</li>' . cr();
					$ix = 0;
				}
				$sx .= '<li>';
				$sx .= $link . $line['rl1'] . '</a>';
				if (strlen($line['note']) > 0) {
					$sx .= '<p>' . '<b>' . msg($line['prop_pre'] . ':' . $line['prop_note']) . '</b>: ' . $line['note'] . '</p>';
				}
				// $sx .= '<i>'.$line['prop'].'</i>';
				if ($line['rl1'] != $line['rl2']) {
					$sx .= '<br>';
					$sx .= '<span style="margin-left: 45px;">';
					$sx .= '<i>' . msg('ver') . '</i> ';
					$sx .= '</span>';
					$sx .= $line['rl2'];
				}
				$xc = $c;
				$ix = 1;
			} else {
				if (strlen($line['note']) > 0) {
					$sx .= '<p>' . '<b>' . msg($line['prop_pre'] . ':' . $line['prop_note']) . '</b>: ' . $line['note'] . '</p>';
				}
			}

		}
		if ($ix > 0) { $sx .= '</li>' . cr();
			$ix = 0;
		}
		$sx .= '</lu>';
		return ($sx);
	}

	function acao_novos_termos($th = '') {
		$sx = '<a href="' . base_url('index.php/skos/concept_add/' . $th) . '" class="btn btn-default" style="width: 100%;">' . msg('concept_add') . '</a>';
		return ($sx);
	}

	function acao_visualizar_glossario($th = '') {
		$sx = '';
		$sx .= '<br/>';
		$sx .= '<br/>';
		$sx .= '<a href="' . base_url('index.php/skos/glossario/' . $th) . '" class="btn btn-default" style="width: 100%;">' . msg('glossario') . '</a>';
		$sx .= '<br/>';
		$sx .= '<br/>';
		$sx .= '<a href="' . base_url('index.php/skos/thes/' . $th) . '" class="btn btn-default" style="width: 100%;">' . msg('Conceitual map') . '</a>';
		$sx .= '<br/>';
		$sx .= '<br/>';
		$sx .= '<a href="' . base_url('index.php/skos/thrs/' . $th) . '" class="btn btn-default" style="width: 100%;">' . msg('Report Thesaurus') . '</a>';

		return ($sx);
	}

	function termos_sem_conceito($th = '', $ltr = '') {
		$th = round($th);
		$sql = "select * from rdf_literal_th
						INNER JOIN rdf_literal ON id_rl = lt_term 
						LEFT JOIN th_concept_term on ct_term = id_rl AND ct_th = $th
						WHERE lt_thesauros = $th and ct_th is null
						ORDER BY rl_value";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$sx = '<ul>' . cr();
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$link = '<a href="' . base_url('index.php/skos/term/' . $th . '/' . $line['lt_term']) . '">';
			$sx .= '<li>';
			$sx .= $link;
			$sx .= $line['rl_value'];
			$sx .= '</a>';
			$sx .= '</li>' . cr();
		}
		$sx .= '</ul>' . cr();
		return ($sx);
	}

	function association_term_th2($t, $lang, $th) {
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

	function xml($data) {

		//create a new document
		// 1st param takes version and 2nd param takes encoding;
		$dom = new DomDocument("1.0", "UTF-8");

		// it can also be set later, like below, if you decide not to declare at first line:
		$dom -> version = "1.0";
		$dom -> encoding = "UTF-8";

		$dom -> createElement('NODE_NAME', 'NODE_VALUE');
		// we create a XML Node and store it in a variable called noteElem;
		$noteElem = $dom -> createElement('rdf');

		// createElement takes 2 param also, with 1st param takes the node Name, and 2nd param is node Value
		$toElem = $dom -> createElement('concept', $data['ct_concept']);
		$toElem -> setAttribute('created', $data['c_created']);
		//$toElem->setAttribute('value', $data['rl_value']);
		//$toElem->setAttribute('thesaurus', $data['pa_name']);

		$toElem2 = $dom -> createElement('url', $this -> skoses -> url . 'index.php/c/');
		$toElem3 = $dom -> createElement('prefix', $this -> skoses -> prefix);

		$toElem4 = $dom -> createElement('literal', $data['rl_value']);
		$toElem4 -> setAttribute('language', $data['rl_lang']);

		$toElem5 = $dom -> createElement('thesauros', $data['pa_name']);
		$toElem6 = $dom -> createElement('thesauros_id', $data['id_pa']);
		$toElem7 = $dom -> createElement('uri', $this -> skoses -> prefix . ':' . $data['ct_concept']);
		$toElem8 = $dom -> createElement('class', 'skos');
		$toElem9 = $dom -> createElement('subclass', $data['pa_class']);

		// now, we add $toElem as a child of $noteElem
		$toSKOS = $dom -> createElement('skos', '');
		$toSKOS -> setAttribute('uri', $this -> skoses -> prefix . ':' . $data['ct_concept']);
		$toSKOS -> setAttribute('value', $this -> skoses -> prefix . ':' . $data['rl_value']);
		$toSKOS -> setAttribute('language', $this -> skoses -> prefix . ':' . $data['rl_lang']);

		$toSKOS -> appendChild($toElem7);
		$toSKOS -> appendChild($toElem);
		$toSKOS -> appendChild($toElem2);
		$toSKOS -> appendChild($toElem3);
		$toSKOS -> appendChild($toElem4);
		$toSKOS -> appendChild($toElem5);
		$toSKOS -> appendChild($toElem6);
		$toSKOS -> appendChild($toElem8);
		$toSKOS -> appendChild($toElem9);

		$noteElem -> appendChild($toSKOS);

		// add $noteElem to the main dom
		$dom -> appendChild($noteElem);
		$dom -> createComment('Thesa');

		echo $dom -> saveXML();
		exit ;
		return ($sx);
	}

	function user_email_send($para, $nome, $code) {
		$anexos = array();
		$texto = 'SIGNUP - Thesa<hr>';
		switch($code) {
			case 'SIGNUP' :
				$link = base_url('index.php/skos/user_valid/?dd0=' . $para . '&chk=' . checkpost_link($para . date("Ymd")));
				$assunto = 'Cadastro de novo usuários - Thesa';
				$texto .= 'Para ativar seu cadastro é necessário clicar no link abaixo:';
				$texto .= '<br><br>';
				$texto .= '<a href="' . $link . '" target="_new">' . $link . '</a>';
				$texto = utf8_decode($texto);
				$assunto = utf8_decode($assunto);
				$de = 1;
				break;
			default :
				$assunto = 'Enviado de e-mail';
				$texto = 'texto';
				$de = 1;
				break;
		}

		enviaremail($para, $assunto, $texto, $de);
	}

	function user_insert_temp($email, $name) {
		if ($this -> user_exist($email) == 0) {
			$md5 = md5($email . $name);
			$sql = "insert into users 
							( 	
							us_nome, us_email, us_login,
							us_password, us_autenticador, us_nivel,
							us_ativo
							)
							values
							('$name','$email','$email',
							'$md5','E',0,
							-1
							)";
			$rlt = $this -> db -> query($sql);
			$sql = "update users set us_codigo = lpad(id_us,7,0) where us_codigo = '' or us_codigo is null";
		}
	}

	function user_valid($email, $vlr = 1) {
		$sql = "update users
						set us_ativo = $vlr
						where us_email = '$email' ";
		$rlt = $this -> db -> query($sql);
		return (1);
	}

	function user_set_password($email, $pw) {
		$sql = "update users
						set us_ativo = 1,
						us_password = '$pw'
						where us_email = '$email' ";
		$rlt = $this -> db -> query($sql);
		return (1);
	}

	function user_exist($email) {
		$sql = "select * from users where us_email = '$email' ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			$this -> line = $rlt[0];
			return (1);
		} else {
			return (0);
		}
	}

	function th_collaborators($th) {
		$sql = "select * from th_users 
								INNER JOIN users ON ust_user_id = id_us
								where ust_th = $th
								order by us_nome";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$sx = '<table width="100%" class="table">';
		$sx .= '<tr class="small">';
		$sx .= '<th width="2%">' . msg('#') . '</th>';
		$sx .= '<th width="48%">' . msg('userName') . '</th>';
		$sx .= '<th width="48%">' . msg('userEmail') . '</th>';
		$sx .= '</tr>';
		$id = 0;
		for ($r = 0; $r < count($rlt); $r++) {
			$id++;
			$line = $rlt[$r];
			$sx .= '<tr>';
			$sx .= '<td>';
			$sx .= $id;
			$sx .= '</td>';

			$sx .= '<td>';
			$sx .= $line['us_nome'];
			$sx .= '</td>';
			$sx .= '<td>';
			$sx .= $line['us_email'];
			$sx .= '</td>';
			$sx .= '</tr>';
		}
		$sx .= '</table>';
		return ($sx);
	}

	function user_thesa($th, $user, $tipo) {
		if ($tipo == 'INS') {
			$sql = "select * from th_users 
								where ust_user_id = $user and ust_th = $th";
			$rlt = $this -> db -> query($sql);
			$rlt = $rlt -> result_array();
			if (count($rlt) == 0) {
				$sql = "insert into th_users
									(
									ust_user_id, ust_user_role, ust_th,
									ust_status
									) values (
									$user, 1, $th,
									1)";
				$rlt = $this -> db -> query($sql);
				return (1);
			} else {
				$sql = "update th_users set ust_status = 1 
									where ust_user_id = $user and ust_th = $th";
				$rlt = $this -> db -> query($sql);
			}
			return (1);
		}
	}

	function search_term($t = '', $th = '') {
		$sql = "select * from th_concept_term
						INNER JOIN rdf_literal ON id_rl = ct_term
						INNER JOIN th_thesaurus ON ct_th = id_pa
						where rl_value like '%" . $t . "%'
						order by rl_value";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$sx = '<table width="100%" class="table">';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$link = '<a href="' . base_url('index.php/skos/c/' . $line['ct_concept'] . '/' . $line['ct_th']) . '" class="link">';
			$sx .= '<tr>';
			$sx .= '<td>';
			$sx .= $link;
			$sx .= $line['rl_value'];
			$sx .= '</a>';
			$sx .= '<sup>(';
			$sx .= $line['rl_lang'];
			$sx .= ')</sup>';
			$sx .= '</td>';
			$sx .= '<td>';
			$sx .= $line['pa_name'];
			$sx .= '</td>';
			$sx .= '</tr>';
		}
		$sx .= '</table>';
		return ($sx);
	}

	function log_show($idc) {
		$sql = "select * from  th_log 
						INNER JOIN users ON id_us = lg_user
					WHERE lg_c = $idc
					ORDER BY lg_date desc
					LIMIT 20";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$sx = '';
		$sx .= '<span class="btn btn-default" id="view_log">' . msg('view_log') . '</span>';
		$sx .= '<span class="btn btn-default" id="hidden_log" style="display: none;">' . msg('hidden_log') . '</span>';
		$sx .= '<br><br>';
		$sx .= '<table width="100%" style="display: none;" id="table_log" class="table small">';
		$sx .= '<tr>';
		$sx .= '<th width="5%">' . msg('date') . '</th>';
		$sx .= '<th width="5%">' . msg('Action') . '</th>';
		$sx .= '<th width="65%">' . msg('Descript') . '</th>';
		$sx .= '<th width="25%">' . msg('User') . '</th>';
		$sx .= '</tr>';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$sx .= '<tr>';
			$sx .= '<td>' . stodbr($line['lg_date']) . '</td>';
			$sx .= '<td>' . $line['lg_action'] . '</td>';
			$sx .= '<td>' . $line['lg_descript'] . '</td>';
			$sx .= '<td>' . $line['us_nome'] . '</td>';
			$sx .= '</tr>';
		}
		$sx .= '</table>';
		$sx .= '<script>' . cr();
		$sx .= '	$( "#view_log" ).click(function() {
							$("#table_log").toggle("slow");
							$("#view_log").toggle();
							$("#hidden_log").toggle();
						});';
		$sx .= '	$( "#hidden_log" ).click(function() {
							$("#table_log").toggle("slow");
							$("#view_log").toggle();
							$("#hidden_log").toggle();
						});';
		$sx .= '</script>' . cr();
		return ($sx);
	}

	function log_insert($idc, $th, $act, $desc) {
		$user = $_SESSION['id'];
		$sql = "insert into th_log
					(
						lg_c, lg_user, lg_action, lg_descript, lg_th 
					) values (
						$idc, $user, '$act', '$desc', $th
					)";
		$rlt = $this -> db -> query($sql);
		return (1);
	}

	function export($th, $type) {
		$prefix = array();
		
		$sql = "select * from th_concept						
						INNER JOIN th_concept_term ON ct_concept = id_c
						LEFT JOIN rdf_literal ON ct_term = id_rl
						INNER JOIN rdf_resource ON ct_propriety = id_rs
						INNER JOIN rdf_prefix ON rs_prefix = id_prefix
						INNER JOIN thesa ON c_agency = id_thesa						
						WHERE c_th = $th
						order by thesa_prefix, c_concept";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$sx = '';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			
			/* PREFIX */
			$pre = trim($line['thesa_prefix']);
			if (!isset($prefix[$pre])) { $prefix[$pre] = "url"; } 
			$pre = trim($line['prefix_ref']);
			if (!isset($prefix[$pre])) { $prefix[$pre] = "url"; }
			 
			$lang = '';
			
			$update = $line['c_created'];

			$p1 = $line['thesa_prefix'];
			$p1 .= ':';
			$p1 .= $line['c_concept'];

			$re = $line['prefix_ref'];
			$re .= ':';
			$re .= $line['rs_propriety'];

			if (strlen($line['rl_value']) == 0) {
				$p2 = $line['thesa_prefix'];
				$p2 .= ':';
				$p2 .= 'c' . $line['ct_concept_2'];
			} else {
				$p2 = parseToXML($line['rl_value']);
				$lang = ' language="'.$line['rl_lang'].'" ';
				if ($line['rl_value']=='') {
					$p2 = '#';
				}
			}
			switch ($type) {
				case 'xml' :
					$sx .= '<concept id="' . $p1 . '" propriety="' . $re . '" resource="' . $p2 . '" update="'.$update.'" '.$lang.'/>' . cr();
					break;
				default :
					$sx .= $p1 . chr(9) . $re . chr(9) . '"' . $p2 . '"' . cr();
					break;
			}

		}

		switch ($type) {
			case 'xml' :
				header("Content-type: text/xml");
				$sr = '<?xml version="1.0"?>' . cr();
				$sr .= '<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:si="https://www.w3schools.com/rdf/">' . cr();
				foreach ($prefix as $key => $value) {
					$sr .= '<prefix id="'.$key.'" url="'.$value.'"/>'.cr();					
				}
				$sr .= $sx;
				$sr .= '</rdf:RDF>';
				echo $sr;
				return ('');
				break;
			default :
				echo '<pre>' . $sx . '</pre>';
				break;
		}

	}

}

function parseToXML($htmlStr) {
	$xmlStr = str_replace('<', '&lt;', $htmlStr);
	$xmlStr = str_replace('>', '&gt;', $xmlStr);
	$xmlStr = str_replace('"', '&quot;', $xmlStr);
	$xmlStr = str_replace("'", '&#39;', $xmlStr);
	$xmlStr = str_replace("&", '&amp;', $xmlStr);
	return $xmlStr;
}
?>
