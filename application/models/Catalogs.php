<?php
class catalogs extends CI_model {
	var $math = 0;
	var $title = 54;
	var $lc = 1;

	function le_w($id) {
		$sql = "select * from auth_id_name
							INNER JOIN rdf_literal ON ia_id_at = id_rl
							INNER JOIN auth_id ON a_id = ia_id_a
							INNER JOIN thesa ON a_agency = id_thesa
							WHERE id_a = $id ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			$line = $rlt[0];
			$line['w_note'] = 'w' . $line['id_a'];
			$line['w_agency'] = $line['thesa_prefix'];
			$line['w_title'] = $line['rl_value'];
			$line['notation'] = $line['w_agency'] . ':' . $line['w_note'];

			$line['work_authority'] = $this -> le_work_authority($id);
			//$line['work_manifestation'] = $this -> le_work_manifestation($id);
			return ($line);
		} else {
			return ( array());
		}
	}

	function le_work_authority($id) {
		$sql = "
				SELECT * 
					FROM rdf_resource
					LEFT JOIN auth_id_name ON ia_propriety = id_rs

				WHERE rs_group = 'WORKFRAD' AND ia_id_a = $id
				";
				/*
				 * 	INNER JOIN rdf_literal ON ia_id_at = id_rl
				    INNER JOIN rdf_resource ON it_propriety = id_rs
				    INNER JOIN rdf_prefix ON rs_prefix = id_prefix */
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		return ($rlt);
	}

	function le_work_manifestation($id) {
		$sql = "
				SELECT id_rl, prefix_ref, rs_group, rs_propriety, it_agency, rl_value, at_birth, at_death FROM work_id_title 
					INNER JOIN auth_id_name ON it_id_i2 = ia_id_a AND ia_propriety = 45
				    INNER JOIN auth_literal ON ia_id_at = id_rl
				    INNER JOIN rdf_resource ON it_propriety = id_rs
				    INNER JOIN rdf_prefix ON rs_prefix = id_prefix
				WHERE rs_group = 'WORKMANI' and it_id_i = $id
				order by id_rs, id_it";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		return ($rlt);
	}
	function link_class($line) {
		$sx = '<a href="' . base_url('index.php/catalog/w/' . $line['ia_id_a']) . '" class="middle">';
		return ($sx);
	}

	function incorporate($name) {
		$cp = array();
		array_push($cp, array('$H8', '', '', false, false));
		array_push($cp, array('$HV', 'w_title', $name, true, true));
		array_push($cp, array('$A2', '', msg('Incorporate?'), false, true));
		array_push($cp, array('$A3', '', $name, false, true));

		$sql = "select * from language where lg_active = 1 order by lg_order";
		array_push($cp, array('$QR lg_code:lg_language:' . $sql, 'w_language', msg('type'), true, true));

		array_push($cp, array('$B8', '', msg('yes'), false, true));

		$form = new form;
		$tela = $form -> editar($cp, 'work_literal');

		if ($form -> saved > 0) {
			/* Criar Trabalho */
			$id = $this -> catalogs -> recupera_id_title($name);
			$this -> catalogs -> create_auth_id($id);
			$tela = $name . ' Saved';
		}
		$data['content'] = $tela;
		$sx = $this -> load -> view('catalog/cat_incorporate', $data, true);
		return ($sx);
	}

	function recupera_id_title($title) {
		$sql = "select * from work_literal where w_title = '$title' ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			$line = $rlt[0];
			return ($line['id_w']);
		} else {
			return (0);
		}
	}

	function is_work_id($id = 0) {
		$sql = "select * from work_id_title 
					where it_id_w  = $id";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) == 0) {
			return (0);
		} else {
			return ($rlt[0]['it_id_i']);
		}
	}

	function create_auth_id($id) {
		$lc = $this -> lc;
		$title = $this -> title;

		if ($ida = $this -> is_work_id($id) == 0) {
			/* Phase I */
			$sql = "select * from work_id where wi_id_w = $id ";
			$rlt = $this -> db -> query($sql);
			$rlt = $rlt -> result_array();
			if (count($rlt) == 0) {
				$sql = "insert into work_id 
							(
								wi_id_w, wi_id, wi_id_use,
								wi_agency
							) values (
								$id, 0, 0,
								$lc
							)";
				$rlt = $this -> db -> query($sql);

			}
			$this -> updatex();
			/* Phase II */

			$sql = "select * from work_id where wi_id_w = $id ";
			$rlt = $this -> db -> query($sql);
			$rlt = $rlt -> result_array();
			$ida = $rlt[0]['wi_id'];

			$sql = "select * from work_id_title where it_id_w = $id";
			$rlt2 = $this -> db -> query($sql);
			$rlt2 = $rlt2 -> result_array();
			if (count($rlt2) == 0) {

				/* Phase IV */
				$sql = "insert into work_id_title
				(
					it_agency, it_id_i, it_id_i2, 
					it_id_w, it_propriety
				) values (
					$lc, $ida, 0,
					$id, $title
				)";
				$rlt = $this -> db -> query($sql);
			}
		} else {
			$sx = '1';
		}
		redirect(base_url('index.php/catalog/w/' . $ida));

	}

	function updatex() {
		$sql = "update work_id set 	
						wi_id = id_wi,
						wi_id_use = id_wi
					where wi_id = 0 or wi_id is null";
		$rlt = $this -> db -> query($sql);
		return (1);
	}

	function search_term($t,$class) {
		$sx = '';
		$sql = "select * from rdf_literal
							INNER JOIN auth_id_name ON ia_id_at = id_rl
							INNER JOIN auth_id ON a_id = ia_id_a
							WHERE rl_value = '$t' AND a_class = $class ";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$this -> rlt = $rlt;

		if (count($rlt) > 0) {
			$this -> math = 1;
		} else {
			$t = UpperCaseSql($t);
			$t = nbr_autor($t, 9);
			$t = troca($t, ' ', ';') . ';';
			$tm = splitx(';', $t);
			$wh = '';
			$sql = "select * from rdf_literal
							INNER JOIN auth_id_name ON ia_id_at = id_rl
							INNER JOIN auth_id ON a_id = ia_id_a
							WHERE (a_class = $class) AND ";
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
		}
		$sx .= '<table width="100%" class="table">';
		$sx .= '<tr>';
		$sx .= '<th>'.msg('work_title').'</th>';
		$sx .= '</tr>';

		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$link = $this -> link_class($line);
			$sx .= '<tr>';
			$sx .= '<td>';
			$sx .= '<tt>';
			$sx .= $link . $line['rl_value'].'</a>';
			$sx .= '</tt>';
			$sx .= '</td>';
			$sx .= '</tr>';
		}

		return ($sx);
	}

	function insert_propriety($id = '', $prop = '', $ida = '') {
		$ag = $this -> lc;
		$sql = "select * from work_id_title 
						where (it_id_i = $id and it_id_i2 = $ida and it_propriety = $prop)";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) == 0) {
			$sql = "insert into work_id_title
						(
							it_agency, it_id_i, it_id_i2, 
							it_id_w, it_propriety
						) values (
							$ag, $id, $ida,
							0, $prop
						)";
			$this -> db -> query($sql);
		}
		return (1);
	}

}
?>
