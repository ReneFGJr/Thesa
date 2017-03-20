<?php
class Authorities extends CI_model {
	var $rlt = array();
	var $math = 0;
	var $lc = 1;

	var $authorized = 45;
	var $table_autho = 'rdf_literal';

	function recupera_id_title($name) {
		$sql = "select * from " . $this -> table_autho . " where rl_value = '$name' ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			$line = $rlt[0];
			return ($line['id_rl']);
		} else {
			return (0);
		}
	}

	function le_l($id) {
		$sql = "select * from " . $this -> table_autho . "
					LEFT JOIN auth_id_name ON ia_id_at = id_rl 
				where id_rl = " . round($id);
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			$line = $rlt[0];
			$line['at_note'] = 'lt' . $line['id_rl'];
			$dates = '';
			$line['dates'] = $dates;
			$line['redirect'] = $line['ia_id_a'];
			return ($line);
		} else {
			return ( array());
		}
	}

	function create_access_point($id) {
		$form = new form;
		//$url = base_url('index.php/authority/create_ap/'.$id_rl.'/'.checkpost_link($id_rl.$rl_value));
		$cp = array();
		array_push($cp, array('$H8', '', '', false, false));
		$sql = "select * from auth_type where ty_active=1 ";
		array_push($cp, array('$QR id_ty:ty_name:' . $sql, '', msg('class_name'), true, true));
		array_push($cp, array('$B8', '', msg('bt_create_access_point'), false, false));
		$tela = $form -> editar($cp, '');

		if ($form -> saved > 0) {
			$data = $this -> Authorities -> le_l($id);
			if (count($data) > 0) {
				$type = get("dd1");
				if ($this -> Authorities -> is_auth_id($id) == 0) {
					$this -> Authorities -> create_auth_id($id, $type);
					$data['content'] = 'Saved';
					$tela = $this -> load -> view('success', $data, true);
				}
			}
		}
		return ($tela);
		//<input type="submit"  class="btn btn-default" value="<?php echo msg('create_access_point');
	}

	function le_a($id) {
		$authorized = $this -> authorized;
		$sql = "select * from auth_id
					INNER JOIN auth_id_name ON ia_id_a = a_id_use 
					INNER JOIN " . $this -> table_autho . " ON ia_id_at = id_rl
					INNER JOIN auth_type ON id_ty = a_class
					INNER JOIN auth_agency ON id_ag = id_agency
					where ia_propriety = $authorized AND id_a = " . round($id);
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			$line = $rlt[0];
			$line['at_note'] = 'a' . $line['ia_id_a'];
			$line['at_agency'] = $line['ag_id'];
			$line['notation'] = $line['at_agency'] . ':' . $line['at_note'];

			$dates = '';
			$line['dates'] = $dates;

			/* RELATION */
			$sql = "select * from auth_id_name
						 INNER JOIN auth_agency ON id_agency = id_agency
						 INNER JOIN rdf_resource ON ia_propriety = id_rs
						 INNER JOIN rdf_prefix ON  rs_prefix = id_prefix  				 
						 LEFT JOIN " . $this -> table_autho . " ON ia_id_at = id_rl
						 
					where ia_id_a = $id";
			$rlt = $this -> db -> query($sql);
			$rlt = $rlt -> result_array();
			$line['related'] = $rlt;
			$line['class'] = $line['ty_notation'];

			return ($line);
		} else {
			return ( array());
		}
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
						$wh .= "    (rl_value like '%" . $t[$r] . "%' and rl_value like '%" . $t[$y] . "%') ";
					}
				}
			}
		} else {
			if (count($t) == 0) {
				return ('');
			} else {
				$wh .= "    (rl_value like '%" . $t[0] . "%') ";
			}
		}

		$sql = "select * from " . $this -> table_autho . "
						LEFT JOIN auth_id_name ON ia_id_at = id_rl
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
			$form = '<input type="radio" name="dd2" value="' . $line['id_rl'] . '">';
			$sx .= '<li>' . $form . ' ' . $line['rl_value'] . '</li>';
		}
		$sx .= '</ul>';
		$sx .= '<input type="submit" name="acao" value="' . mst('set_alternative') . '">' . cr();

		$sx .= '</div>';

		$sx .= '<div class="col-sm-6 col-xs-6">';
		$sx .= '<h4>' . msg('Relationships') . '</h4>' . cr();
		$sql = "select * from rdf_resource
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

	function sugestoes_busca($name, $class = '') {
		$n = UpperCaseSql($name);
		$n = nbr_autor($n, 9);
		$n = troca($n, '.', '');
		$n = troca($n, ',', '');
		$n = troca($n, ';', '');

		$m = troca($n, ' ', ';');
		$t = splitx(';', $m);
		$wh = '';

		switch ($class) {
			case 'FRAD' :
				$fld = 'rl_value';
				$fld_id = 'ia_id_a';
				$table = $this -> table_autho;
				$wh2 = '';
				break;
			case 'YEAR' :
				$fld = 'rl_value';
				$fld_id = 'ia_id_a';
				$table = $this -> table_autho;
				$wh2 = 'and (rl_type = 6)';
				break;
			case 'PLACE' :
				$fld = 'rl_value';
				$fld_id = 'ia_id_a';
				$table = $this -> table_autho;
				$wh2 = 'and (rl_type = 7)';
				break;
			case 'EDITION' :
				$fld = 'rl_value';
				$fld_id = 'ia_id_a';
				$table = $this -> table_autho;
				$wh2 = 'and (rl_type = 12)';
				break;
			case 'identifier' :
				$fld = 'rl_value';
				$fld_id = 'ia_id_a';
				$table = $this -> table_autho;
				$wh2 = 'and (rl_type = 8)';
				break;
			case 'PAGINATION' :
				$fld = 'rl_value';
				$fld_id = 'ia_id_a';
				$table = $this -> table_autho;
				$wh2 = 'and (rl_type = 13)';
				break;
			default :
				return ('not implemented');
				break;
		}

		if (count($t) > 1) {
			for ($r = 0; $r < count($t); $r++) {
				for ($y = 0; $y < count($t); $y++) {
					if ($y != $r) {
						if (strlen($wh) > 0) { $wh .= ' OR ';
						}
						$wh .= "    ($fld like '%" . $t[$r] . "%' and $fld like '%" . $t[$y] . "%') ";
					}
				}
			}
		} else {
			if (count($t) == 0) {
				return ('');
			} else {
				$wh .= "    ($fld like '%" . $t[0] . "%') ";
			}
		}

		$sql = "select * from $table
						LEFT JOIN auth_id_name ON ia_id_at = id_rl
						WHERE (" . $wh . ") and (id_ia is not NULL) $wh2";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();

		if (count($rlt) == 0) {
			return ('');
		}

		$sx = '';
		$sx .= '<div class="row">';
		$sx .= '	<div class="col-sm-12 col-xs-12">';
		$sx .= '	<h4>' . msg('Sugestions') . '</h4>' . cr();
		$sx .= '	<ul>';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$link = '<a href="?dd10=' . $line[$fld_id] . '">';
			$sx .= '<li>' . $link . '<tt> ' . $line[$fld] . '</tt></a></li>';
		}
		$sx .= '	</ul>';
		$sx .= '</div>';
		return ($sx);

	}

	function relation_insert($id1, $id2, $id3) {
		$lc = $this -> lc;
		$propriety = $id3;
		$ida = $id1;
		$id = $id2;

		/* Phase IV */
		$sql = "insert into auth_id_name
				(
					id_agency, ia_id_a, ia_id_a2, 
					ia_id_at, ia_propriety
				) values (
					$lc, $ida, 0,
					$id, $propriety
				)";

		$rlt = $this -> db -> query($sql);
		return (1);
	}

	function create_auth_id($id, $class) {
		$lc = $this -> lc;
		$authorized = $this -> authorized;

		if ($ida = $this -> is_auth_id($id) == 0) {
			/* Phase I */
			$sql = "select * from auth_id where a_id_at = $id ";
			$rlt = $this -> db -> query($sql);
			$rlt = $rlt -> result_array();
			if (count($rlt) == 0) {
				$sql = "insert into auth_id 
							(
								a_id_at, a_id, a_id_use,
								a_agency, a_class
							) values (
								$id, 0, 0,
								$lc, $class
							)";
				$rlt = $this -> db -> query($sql);

			}
			$this -> updatex();
			/* Phase II */

			$sql = "select * from auth_id where a_id_at = $id ";
			$rlt = $this -> db -> query($sql);
			$rlt = $rlt -> result_array();
			$ida = $rlt[0]['id_a'];

			$sql = "select * from auth_id_name where ia_id_at = $id";
			$rlt2 = $this -> db -> query($sql);
			$rlt2 = $rlt2 -> result_array();
			if (count($rlt2) == 0) {

				/* Phase IV */
				$sql = "insert into auth_id_name
				(
					id_agency, ia_id_a, ia_id_a2, 
					ia_id_at, ia_propriety
				) values (
					$lc, $ida, 0,
					$id, $authorized
				)";
				$rlt = $this -> db -> query($sql);
			}
		} else {
			$sx = '1';
		}
		redirect(base_url('index.php/catalog/a/' . $ida));

	}

	function updatex() {
		$sql = "update auth_id set 	
						a_id = id_a,
						a_id_use = id_a
					where a_id = 0 or a_id is null";
		$rlt = $this -> db -> query($sql);
		return (1);
	}

	function is_auth_id($id = 0) {
		$sql = "select * from auth_id_name 
					where ia_id_at  = $id";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) == 0) {
			return (0);
		} else {
			return ($rlt[0]['ia_id_a']);
		}
	}

	function search_term($t) {
		$sx = '';

		$t = UpperCaseSql($t);
		$t = nbr_autor($t, 9);
		$t = troca($t, ' ', ';') . ';';
		$tm = splitx(';', $t);
		$wh = '';
		$sql = "select * from " . $this -> table_autho . "
							LEFT JOIN auth_id_name ON ia_id_at = id_rl
							LEFT JOIN auth_id ON ia_id_a = id_a
							LEFT JOIN auth_type ON a_class = id_ty
							WHERE ";
		for ($r = 0; $r < count($tm); $r++) {
			if (strlen($wh) > 0) {
				$wh .= ' AND ';
			}
			$wh .= "(rl_value like '%" . $tm[$r] . "%' )";
		}
		if (strlen($wh) > 0) {
			$sql .= $wh;
			$sql .= " order by rl_value ";
		}
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$this -> rlt = $rlt;

		for ($r = 0; $r <	 count($rlt); $r++) {
			$line = $rlt[$r];
			$link = $this -> link_literal($line);
			$sx .= '<br>' . $link . $line['rl_value'] . '</a>';
			$sx .= ' - (' . msg($line['ty_name']) . ')</a>';
		}

		return ($sx);
	}

	function link_literal($data) {
		$id = $data['id_rl'];
		$link = '<a href="' . base_url('index.php/catalog/literal/' . $id) . '">';
		return ($link);
	}

	function incorporate($name) {
		$cp = array();
		array_push($cp, array('$H8', '', '', false, false));
		array_push($cp, array('$HV', 'rl_value', $name, true, true));
		array_push($cp, array('$A2', '', msg('Incorporate?'), false, true));
		array_push($cp, array('$B8', '', msg('incorporate_name'), false, true));

		$form = new form;
		$tela = $form -> editar($cp, $this -> table_autho);

		if ($form -> saved > 0) {
			$tela = $name . ' Saved';
			$id = $this -> recupera_id_title($name);
			if ($id > 0) {
				$link = base_url('index.php/catalog/literal/' . $id);
				redirect($link);
			}

		}
		$data['content'] = $tela;
		$sx = $this -> load -> view('catalog/cat_incorporate', $data, true);
		return ($sx);
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

}
?>
