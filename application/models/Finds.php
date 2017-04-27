<?php
class finds extends CI_model {

	var $table_literal = 'find_literal';
	var $result = 0;
	/* */
	var $work = 59;

	var $agency = 1;
	var $prop_class = 1;

	function find_literal($name = '') {
		$sx = $this -> sugestoes($name);
		return ($sx);
	}

	function le_attr($id) {
		$sql = "select * from rdf_resource
						INNER JOIN rdf_prefix ON id_prefix = rs_prefix
						where id_rs = " . $id;
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$line = $rlt[0];
		return ($line);
	}

	function list_data_values($id) {
		$sql = "select * from find_id
						INNER JOIN find_literal ON id_l = f_literal
						where f_class = $id ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$sx = '<ul>';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$sx .= '<li>' . $line['l_value'] . '</li>';
		}
		$sx = '</ul>';
		return ($sx);
	}

	function list_data_attr($id) {
		$sql = "select * from find_id
						INNER JOIN find_literal ON f_literal = id_l 
						WHERE f_class = $id	order by l_value
						LIMIT 210";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$sx = '<table width="100%" class="table">';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$sx .= '<tr>';
			$sx .= '<td>' . $line['l_value'] . ' (' . $line['l_language'] . ')</td>';
			$sx .= '</tr>';
		}
		$sx .= '</table>';
		return ($sx);
	}

	function import_xml($file, $class) {

		$xml = simplexml_load_file($file);
		$sx = '';
		//echo $xml -> getName() . "<br />";
		foreach ($xml->children() as $child) {
			$type = $child -> getName();
			$id = $child['id'];
			$prop = $child['propriety'];
			$reso = trim($child['resource']);
			$update = $child['update'];
			$lang = $child['language'];
			echo "<br /> ->" . $type . ' ' . $id . ' ' . $prop . '==>' . $reso . ' (' . round(sonumero($id)) . ')';

			/* LITERAL */
			if ((($prop == 'skos:prefLabel') OR ($prop == 'skos:altLabel')) AND (round(sonumero($id)) > 0)) {
				if (strlen($reso) > 0) {
					if ($lang == 'por') { $lang = 'pt_BR';
					}
					if ($prop == 'skos:prefLabel') {
						$this -> create_id($reso, $lang, $class);
					}
				}
			}
		}
		return ($sx);

	}

	function row_classes($id = '') {
		$form = new form;

		$form -> fd = array('id_rs', 'prefix_ref', 'rs_propriety', 'rs_type');
		$form -> lb = array('id', msg('prefix_ref'), msg('cap_rs_propriety'), msg('rs_type'));
		$form -> mk = array('', 'L', 'L', 'L');

		$form -> tabela = '(select * from rdf_resource INNER JOIN rdf_prefix ON id_prefix = rs_prefix) as tabela';
		$form -> see = true;
		$form -> novo = true;
		$form -> edit = true;
		$form -> pre_where = " rs_type = 'C' ";

		$form -> row_edit = base_url('index.php/find/classes');
		$form -> row_view = base_url('index.php/find/attribute');
		$form -> row = base_url('index.php/find/classes');

		return (row($form, $id));
	}

	function view_propriety($id) {
		$cp = 'RP1.prefix_ref as pr1, RS1.rs_propriety as rs1, l_value ';
		//$cp .= 'RP2.prefix_ref as pr2, RS2.rs_propriety as rs2 ';
		//$cp .= 'RP2.prefix_ref as pr2, RS2.rs_propriety as rs2 ';
		$sql = "SELECT $cp FROM find_rdf
			
						INNER JOIN find_id as f1 ON fr_id_2 = id_f
						INNER JOIN find_literal ON f_literal = id_l
						
						INNER JOIN rdf_resource as RS1 ON fr_propriety = id_rs
						INNER JOIN rdf_prefix as RP1 ON rs_prefix = id_prefix
												
						WHERE fr_id_1 = $id";

		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$sx = '<table width="100%" class="table">';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];

			$sx .= '<tr>';
			$sx .= '<td>' . $line['pr1'] . ':' . $line['rs1'] . '</td>';
			$sx .= '<td>' . $line['l_value'] . '</td>';
			$sx .= '</tr>';
		}
		$sx .= '</table>';
		return ($sx);
	}

	function attr_class($id) {
		$cp = 'RS1.rs_propriety as rs1, RP1.prefix_ref as rp1';
		$cp .= ', RS2.rs_propriety as rs2, RP2.prefix_ref as rp2';
		$sql = "select $cp from find_class_attributes
							INNER JOIN rdf_resource as RS1 ON fcs_propriety = RS1.id_rs
							INNER JOIN rdf_prefix as RP1 ON RS1.rs_prefix = RP1.id_prefix 
							
							INNER JOIN rdf_resource as RS2 ON fcs_range = RS2.id_rs
							INNER JOIN rdf_prefix as RP2 ON RS2.rs_prefix = RP2.id_prefix 
							
						WHERE fcs_class = $id";

		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();

		$sx = '<table width="100%" class="table">';
		$sx .= '<tr>
						<th width="47%">' . msg('propriety') . '</th>
						<th width="47%">' . msg('range') . '</th>
						<th width="5%">' . msg('act') . '</th>
					</tr>';

		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];

			$sx .= '<tr>';
			$sx .= '<td>' . $line['rp1'] . ':' . $line['rs1'] . '</td>';
			$sx .= '<td>' . $line['rp2'] . ':' . $line['rs2'] . '</td>';
			$link = '<span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="color: red;"></span>';
			$link = '<a href="#">' . $link . '</a>';
			$sx .= '<td align="center">' . $link . '</td>';
			$sx .= '</tr>';
		}
		$sx .= '</table>';
		return ($sx);
	}

	function attr_edit($class = '', $chk = '') {
		$table = 'find_class_attributes';

		$form = new form;
		$form -> id = '';
		$cp = $this -> cp_attr($class);
		$tela = $form -> editar($cp, $table);

		if ($form -> saved > 0) {
			$tela .= '=== SAVED ===';
		}
		return ($tela);
	}

	function cp_attr($id) {
		$cp = array();
		array_push($cp, array('$H8', 'id_fcs', '', false, true));
		array_push($cp, array('$HV', 'fcs_class', $id, true, true));
		/* propriety */
		$sql = "select id_rs, concat(prefix_ref,'|',rs_propriety) as propr FROM rdf_resource
						INNER JOIN rdf_prefix ON rs_prefix = id_prefix
						WHERE rs_type = 'P' ";
		array_push($cp, array('$Q id_rs:propr:' . $sql, 'fcs_propriety', msg('propriety'), true, true));

		/* range */
		$sql = "select id_rs, concat(prefix_ref,'|',rs_propriety) as propr FROM rdf_resource
						INNER JOIN rdf_prefix ON rs_prefix = id_prefix
						WHERE rs_type = 'C' ";
		array_push($cp, array('$Q id_rs:propr:' . $sql, 'fcs_range', msg('range'), true, true));

		array_push($cp, array('$B8', '', msg('save'), false, true));
		return ($cp);
	}

	function rdf_propriety($r0, $r1, $r2, $r3, $r4) {
		$agency = $this -> agency;

		$sql = "select * from find_rdf
						WHERE
						fr_id_1 = $r2 AND
						fr_id_2 = $r4 AND
						fr_literal = $r0 AND
						fr_propriety = $r3";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();

		if (count($rlt) == 0) {

			$sql = "insert into find_rdf
				(
					fr_id_1, fr_id_2, fr_literal, fr_propriety,
					fr_agency
				)
				values
				(
					$r2, $r4, $r0, $r3,
					$agency
				)";

			$rlt = $this -> db -> query($sql);
		}
		return (1);
	}

	function editvw($i1, $i2, $i3, $i4, $i5) {

		$sql = "select FCA1.fcs_range as rg1, FCA2.fcs_range as rg2 from find_class_attributes as FCA1
					LEFT JOIN find_class_attributes as FCA2 ON FCA2.fcs_class = FCA1.fcs_range
					WHERE FCA1.id_fcs = $i2";
		$rlt = $this -> db -> query($sql);
		echo $sql;
		$rlt = $rlt -> result_array();
		$wh_range = '';
		$wh_class2 = '';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$range1 = $line['rg1'];
			$range2 = $line['rg2'];
			if (strlen($wh_range) > 0) { $wh_range .= ' OR ';
			}
			$wh_range .= "(f_class = $range1)";

			/* */
			IF ($range2 > 0) {
				if (strlen($wh_range) > 0) { $wh_range .= ' OR ';
				}
				$wh_range .= "(f_class = $range2)";
			}
		}

		$sql = "select * from find_id
						INNER JOIN find_literal ON f_literal = id_l
						WHERE $wh_range						
						ORDER BY l_value
						LIMIT 2000";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();

		$sx = '';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];

			$link = '<a href="' . base_url('index.php/find/editvw/' . $i1 . '/' . $i2 . '/' . $i3 . '/' . $i4 . '/' . $line['id_f']) . '">';
			$sx .= $link;
			$sx .= $line['l_value'];
			$sx .= '</a>';
			$sx .= '<br>';
		}
		return ($sx);
	}

	function class_value_edit($id, $c) {
		$cp = 'RP1.prefix_ref as pr1, RS1.rs_propriety as rs1, RS1.id_rs as idrs1';
		$cp .= ', RP2.prefix_ref as pr2, RS2.rs_propriety as rs2, RS2.id_rs as idrs2';
		$cp .= ', id_fcs';
		//$cp = '*';
		$sql = "select $cp 
						FROM find_class_attributes
						LEFT JOIN rdf_resource as RS1 ON fcs_propriety = RS1.id_rs
						LEFT JOIN rdf_resource as RS2 ON fcs_range = RS2.id_rs
						LEFT JOIN rdf_prefix as RP1 ON RP1.id_prefix = RS1.rs_prefix
						LEFT JOIN rdf_prefix as RP2 ON RP2.id_prefix = RS2.rs_prefix
						LEFT JOIN find_rdf ON fr_propriety = fcs_propriety
						WHERE fcs_class = $id
						ORDER BY fcs_group, fcs_order ";
		$sql1 = "select *
						FROM find_class_attributes
						LEFT JOIN rdf_resource as RS1 ON fcs_propriety = RS1.id_rs
						LEFT JOIN rdf_resource as RS2 ON fcs_range = RS2.id_rs
						LEFT JOIN rdf_prefix as RP1 ON RP1.id_prefix = RS1.rs_prefix
						LEFT JOIN rdf_prefix as RP2 ON RP2.id_prefix = RS2.rs_prefix
						WHERE fcs_class = $id
						ORDER BY fcs_group, fcs_order ";

		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		///echo '<br><br><br>';
		$sx = '<table class="table" width="100%">';
		$sx .= '<tr>';
		$sx .= '<th class="small">propriety</th>';
		$sx .= '</tr>';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			//print_r($line); exit;
			$ida = $line['id_fcs'];
			$idb = $line['idrs1'];

			$link = '<a href="#" onclick="newwin(\'' . base_url('index.php/find/editvw/' . $id . '/' . $ida . '/' . $c . '/' . $idb) . '\');">';
			$link .= '<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>';
			$link .= '</a>';

			$sx .= '<tr>';
			$sx .= '<td>' . msg(trim($line['pr1']) . ':' . trim($line['rs1'])) . '</td>';
			$sx .= '<td>' . msg($line['pr2'] . ':' . $line['rs2']) . '</td>';
			$sx .= '<td>' . $link . '</td>';
			$sx .= '</tr>';
		}
		$sx .= '</table>';
		return ($sx);
	}

	function le($id = 0) {
		$work = $this -> work;
		$sql = "select * from find_literal
						INNER JOIN find_id ON f_literal = id_l AND f_class = $work
						INNER JOIN find_rdf ON fr_id_1 = id_f						
						INNER JOIN rdf_resource ON id_rs = f_class
						INNER JOIN rdf_prefix ON rs_prefix = id_prefix					
						WHERE id_f = $id ";

		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			$line = $rlt[0];

			$line['img_cover'] = $this -> find_cover($id);
			return ($line);
		} else {
			return ( array());
		}

	}

	function find_cover($id) {
		$img = base_url('img/find/no_cover.png');
		return ($img);
	}

	function incorpore_id($name = '', $type = '') {
		$sx = '<hr>';
		$action = base_url('index.php/find/tp/' . $type);
		$sx .= form_open($action);
		$sx .= '<div class="row">';
		$sx .= '<div class="col-md-6">';
		$sx .= '<input type="hidden" name="name" class="form-contro" value="' . $name . '">';
		$sx .= '<input type="radio" name="lang" value="pt_BR">' . msg('portugues');
		$sx .= '</br><input type="radio" name="lang" value="es">' . msg('espanhol');
		$sx .= '<br>';
		$sx .= '</div>';

		$sx .= '<div class="col-md-6">';
		switch($type) {
			case 'frad' :
				$sql = "select * from rdf_resource
							WHERE rs_type='C'
							AND rs_public = 1";
				$rlt = $this -> db -> query($sql);
				$rlt = $rlt -> result_array();
				for ($r = 0; $r < count($rlt); $r++) {
					$line = $rlt[$r];
					$sx .= '<input type="radio" name="class" value="' . $line['id_rs'] . '">' . mst('cl_' . $line['rs_propriety']);
					$sx .= '<br>';
				}
		}
		$sx .= '</div>';

		$sx .= '<input type="submit" class="btn btn-default" value="' . msg("insert_" . $type) . '">';
		$sx .= '</form>';
		return ($sx);
	}

	function create_id($name, $lang, $class) {

		$agency = $this -> agency;
		$prop_class = $this -> prop_class;

		$sql = "select * from find_literal where l_value = '$name' and l_language = '$lang' ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();

		if (count($rlt) == 0) {
			/* INSERT NEW LITERAL VALUE */
			$sqlx = "insert into find_literal
								(l_value, l_language)
								value
								('$name','$lang')";
			$rltx = $this -> db -> query($sqlx);
			$rlt = $this -> db -> query($sql);
			$rlt = $rlt -> result_array();
		}
		$line = $rlt[0];
		$id = $line['id_l'];

		$sqlz = "select * from find_id where f_literal = $id and f_class=$class ";
		$rltz = $this -> db -> query($sqlz);
		$rltz = $rltz -> result_array();

		if (count($rltz) == 0) {
			$sqlq = "insert into find_id
							(
							f_literal, f_class,
							f_agency, f_source
							) values (
							$id, $class,
							$agency, 0
							)";
			$rltq = $this -> db -> query($sqlq);
		}
		/* recupera IDC */
		$rltz = $this -> db -> query($sqlz);
		$rltz = $rltz -> result_array();
		$linez = $rltz[0];
		$idc = $linez['id_f'];

		$sql = "select * from find_rdf where fr_id_1 = $idc and fr_propriety = $prop_class and fr_literal = $id ";
		$rrr = $this -> db -> query($sql);
		$rrr = $rrr -> result_array();

		if (count($rrr) == 0) {
			$sql = "insert into find_rdf
							(
								fr_id_1, fr_id_2, fr_literal,
								fr_propriety, fr_agency
							) values (
								$idc, 0, $id,
								$prop_class, $agency
							)";
			$rltz = $this -> db -> query($sql);
		}
	}

	function incorpore($name = '') {
		$dd1 = get("dd1");
		$dd2 = get("dd2");
		if (strlen($dd2) == 0) { $dd2 = $name;
		}

		$f = array();
		$sx = form_open($f);

		$sql = "select * from auth_type 
						where ty_active = 1
						ORDER BY ty_group, ty_name";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();

		$sx .= '<div class="row">';

		$sx .= '<div class="col-sm-6 col-sx-6 col-xs-6">';
		$sx .= '<span class="big">';
		$sx .= '<tt>' . $dd2 . '</tt>';
		$sx .= '</span>';
		$sx .= '<br>' . cr();

		$sx .= '<input type="submit" value="' . msg('create_literal_value') . '" class="btn btn-default">';
		$sx .= '<input type="hidden" value="' . $dd2 . '">';

		$sx .= '<br>' . date("d-m-Y H:i:s");

		$sx .= '</div>' . cr();
		/******** TYPES ***********/
		$sx .= '<div class="col-sm-6 col-sx-6 col-xs-6">';
		$idx = '';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$id = $line['ty_group'];
			if ($id != $idx) {
				$sx .= '<b>' . msg('gr_' . $id) . '</b><br>' . cr();
				$idx = $id;
			}

			$chk = '';
			if ($dd1 == $line['id_ty']) { $chk = 'checked';
			}
			$sx .= '<input type="radio" name="dd1" value="' . $line['id_ty'] . '" ' . $chk . '> ' . msg('ca_' . $line['ty_name']) . '<br>' . cr();
		}
		$sx .= '</div>' . cr();
		$sx .= '</div>' . cr();

		$sx .= form_close();
		return ($sx);
	}

	function literal($name) {
		$work = $this -> work;

		$sql = "select * from find_literal
						INNER JOIN find_id ON f_literal = id_l AND f_class = $work
						INNER JOIN find_rdf ON fr_id_1 = id_f						
						INNER JOIN rdf_resource ON id_rs = f_class
						INNER JOIN rdf_prefix ON rs_prefix = id_prefix						
				WHERE l_value like '%$name%' ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();

		if (count($rlt) == 0) {
			$this -> result = -1;

			/* VARIANTES */
			$wd = troca($name, ' ', ';');
			$wa = splitx(';', $wd);
			$wh = '';

			if (count($wa) > 1) {
				for ($a1 = 0; $a1 < count($wa); $a1++) {
					for ($a2 = ($a1 + 1); $a2 < count($wa); $a2++) {
						IF (strlen($wh) > 0) { $wh .= ' OR ';
						}
						$wh .= " ((l_value like '%" . $wa[$a1] . "%') AND (l_value like '%" . $wa[$a2] . "%')) ";
					}
				}
			} else {
				$wh = "(l_value = '%" . $wd . "%')";
			}
			$sql = "select * from find_literal
						INNER JOIN find_id ON f_literal = id_l AND f_class = $work
						INNER JOIN find_rdf ON fr_id_1 = id_f						
						INNER JOIN rdf_resource ON id_rs = f_class
						INNER JOIN rdf_prefix ON rs_prefix = id_prefix					
						WHERE " . $wh;

			$rlt = $this -> db -> query($sql);
			$rlt = $rlt -> result_array();
		}

		$tela = msg('not_found');
		$sx = '<table width="100%" class="table">';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];

			$link = '<a href="' . base_url('index.php/find/v/' . $line['id_f']) . '">';

			$sx .= '<tr>';
			$sx .= '<td>';
			$sx .= $link . $line['l_value'] . '</a>';
			$sx .= '</td>';
			$sx .= '</tr>';
		}
		$sx .= '</table>';
		$tela = $sx;
		return ($tela);
	}

	function form_search($data, $class = '') {
		$cp = array();
		switch ($class) {
			case 'YEAR' :
				$mmm = msg('find_date');
				break;
			case 'PAGINATION' :
				$mmm = msg('find_pages');
				break;
			case 'FRAD' :
				$mmm = msg('find_author');
				break;
			default :
				$mmm = msg('find');
				break;
		}
		array_push($cp, array('$H8', '', '', false, false));
		array_push($cp, array('$S100', '', $mmm, true, true));
		array_push($cp, array('$B8', '', msg('find'), false, false));
		$form = new form;

		$tela = $form -> editar($cp, '');

		if ($form -> saved > 0) {
			$nome = get("dd1");
			$tela .= $this -> sugestoes_busca($nome, $class);
		}
		return ($tela);
	}

	function sugestoes($name) {
		$n = UpperCaseSql($name);
		$n = nbr_autor($n, 9);
		$n = troca($n, '.', '');
		$n = troca($n, ',', '');
		$n = troca($n, ';', '');
		$m = troca($n, ' ', ';');
		$t = splitx(';', $m);
		$wh = '';

		if (count($t) > 1) {
			for ($r = 0; $r < count($t); $r++) {
				for ($y = 0; $y < count($t); $y++) {
					if ($y != $r) {
						if (strlen($wh) > 0) { $wh .= ' OR ';
						}
						$wh .= "    (l_value like '%" . $t[$r] . "%' and l_value like '%" . $t[$y] . "%') ";
					}
				}
			}
		} else {
			if (count($t) == 0) {
				return ('');
			} else {
				$wh .= "    (l_value like '%" . $t[0] . "%') ";
			}
		}

		$sql = "select * from " . $this -> table_literal . "
						LEFT JOIN auth_id_name ON ia_id_at = id_l
						WHERE (" . $wh . ") and (id_ia is NULL)";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();

		if (count($rlt) == 0) {
			return ('');
		}

		$sx = '';
		$sx .= '<form method="post">';
		$sx .= '<div class="row">';
		$sx .= '<div class="col-sm-6 col-xs-6">';
		$sx .= '<h4>' . msg('Sugestions') . '</h4>' . cr();
		$sx .= '<ul>';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$form = '<input type="radio" name="dd2" value="' . $line['id_l'] . '">';
			$sx .= '<li>' . $form . ' ' . $line['l_value'] . '</li>';
		}
		$sx .= '</ul>';
		$sx .= '<input type="submit" name="acao" value="' . mst('set_alternative') . '">' . cr();

		$sx .= '</div>';

		$sx .= '<div class="col-sm-6 col-xs-6">';
		$sx .= '<h4>' . msg('Relationships') . '</h4>' . cr();
		$sql = "select * from find_literal
						INNER JOIN rdf_prefix ON rs_prefix = id_prefix
						where rs_group = 'FRAD'
								and id_rs <> $this->authorized 
						order by id_rs";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$sx .= '<ul>';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$form = '<input type="radio" name="dd3" value="' . $line['id_rs'] . '">';
			$sx .= '<li>' . $form . ' ' . mst($line['prefix_ref'] . ':' . $line['rs_propriety']) . '</li>';
		}
		$sx .= '</ul>';
		$sx .= '</div>';
		$sx .= '</div>';
		$sx .= '</div>';
		$sx .= '</form>';
		return ($sx);
	}

	function marc21_in($txt) {
		$txt = troca($txt, ';', '¢');
		$txt = troca($txt, chr(13), ';');
		$txt = troca($txt, chr(10), '');

		$ln = splitx(';', $txt);

		for ($r = 0; $r < count($ln); $r++) {
			$line = $ln[$r];
			$field = substr($line, 0, 3);
			switch($field) {
				case '020' :
					$txt = extract_marc('$a', $line);
					$this -> marc21_020($txt);
					break;
			}
		}

		print_r($ln);
		exit ;
	}

	function marc21_020($isbn = '') {
		$isbn = troca($isbn, 'x', 'X');
		$rlt = '';
		for ($ki = 0; $ki < strlen($isbn); $ki++) {
			$ord = ord(substr($isbn, $ki, 1));
			if ((($ord >= 48) and ($ord <= 57)) or ($ord == ord('X'))) { $rlt = $rlt . substr($isbn, $ki, 1);
			}
		}
		$isbn = 'ISBN' . $rlt;

		$sql = "select * from find_literal where l_value = '$isbn' ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();

		if (count($rlt) == 0) {
			$sql2 = "insert into find_literal (l_value) values ('$isbn')";
			$rlt2 = $this -> db -> query($sql2);
			$rlt = $this -> db -> query($sql);
			$rlt = $rlt -> result_array();
		}
		$line = $rlt[0];

		$class = $this->find_rdf('ISBN', 'C');
		/**************************************************************************************/
		$ag = $this->agency;
		$lt = $line['id_l'];
		
		$sql = "select * from find_id where f_literal = $lt and f_class = $class";
		$rlt = $this->db->query($sql);
		$rlt = $rlt->result_array();
		
		if (count($rlt) == 0)
			{
				$sql2 = "insert into find_id 
					(f_literal, f_class, f_agency, f_source)
					values
					($lt,$class,$ag,0)";				
				$rlt = $this->db->query($sql2);
			}
		$rlt = $this->db->query($sql);
		$rlt = $rlt->result_array();
		$id = $rlt[0]['id_f'];
				
		/**************************************************************************************/
		$this->rdf_propriety($lt, 0, $id, $class, 0);
		
		exit;
	}

	function find_rdf($name = '', $tp = 'C') {
		$sql = "select * from rdf_resource where rs_propriety = '$name' and rs_type = '$tp' ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();

		if (count($rlt) == 0) {
			echo 'OPS ' . $name;
			exit ;
		}
		$line = $rlt[0];
		$id = $line['id_rs'];
		return ($id);
	}

}

function extract_marc($f, $t) {
	$t = troca($t, '|', '$');
	$pos = strpos($t, $f);
	$rt = '';
	if ($pos > 0) {
		$t = substr($t, $pos + 2, strlen($t));
		$pos = strpos($t, '$');
		if ($pos > 0) {
			$t = substr($t, 0, $pos);
		}
		$rt = $t;
	}
	return ($rt);
}
?>
