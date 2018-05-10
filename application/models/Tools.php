<?php
class tools extends CI_model {
	function form($tp = '') {
		$form = new form;
		$cp = array();
		array_push($cp, array('$H8', '', '', False, False));
		if (isset($_POST['dd2'])) {
			$tp = get("dd3");
			switch($tp) {
				case '1' :
					$_POST['dd1'] = $this -> remove_parentes(get("dd2"));
					break;
				case '2' :
					$_POST['dd1'] = $this -> prepare_list(get("dd2"));
					break;
			}

			array_push($cp, array('$T80:10', '', 'Result', True, True));
		} else {
			array_push($cp, array('$H8', '', '', False, False));
		}

		array_push($cp, array('$T80:10', '', 'Content', True, True));
		array_push($cp, array('$O 1:Remove char&2:List Names', '', 'Function', False, True));
		array_push($cp, array('$S5', '', 'Separador', False, True));
		$tela = $form -> editar($cp, '');
		return ($tela);
	}

	function remove_parentes($txt = '', $c1 = '[', $c2 = ']') {
		$txt = '####' . $txt;
		while (strpos($txt, $c1)) {
			$pos = strpos($txt, $c1);
			$s1 = substr($txt, 0, $pos);
			$s2 = substr($txt, $pos + 1, strlen($txt));
			$s2 = substr($s2, strpos($s2, ']') + 1, strlen($s2));
			$txt = $s1 . $s2;
		}
		$txt = substr($txt, 4, strlen($txt));
		return ($txt);
	}

	function prepare_list($txt, $c1 = ';') {
		$txt = troca($txt, chr(13), ';');
		$txt = troca($txt, chr(10), '');
		$txt = troca($txt, '; ', ';');
		$txt = troca($txt, '; ', ';');
		$txt = troca($txt, '; ', ';');
		$da = splitx(';', $txt);
		$dt = array();
		$nl = array();
		for ($r = 0; $r < count($da); $r++) {
			$na = trim($da[$r]);
			if (strlen($na) > 0) {
				if (!isset($dt[$na])) {
					$dt[$na] = $na;
					array_push($nl, $na);
				}
			}
		}
		$sx = '';
		asort($nl);
		foreach ($nl as $key => $value) {
			$sx .= $value . cr();
		}
		return ($sx);
	}

}
?>
