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
                case '3' :
                    $_POST['dd1'] = $this -> word_count(get("dd2"));
                    break;                    
                case '4':
                    $_POST['dd1'] = $this -> change_terms(get("dd2"),get("dd4"));
                    break;
                case '5':
                    $_POST['dd1'] = $this -> duplicate_lines(get("dd2"));
                    break;
			}

			array_push($cp, array('$T80:10', '', 'Result', True, True));
		} else {
			array_push($cp, array('$H8', '', '', False, False));
		}

		array_push($cp, array('$T80:10', '', msg('Content'), True, True));
        $ops = '1:'.msg('Remove char');
        $ops .= '&2:'.msg('List Names');
        $ops .= '&3:'.msg('Word Count');
        $ops .= '&4:'.msg('Change_terms');
        $ops .= '&5:'.msg('Remove_duplicate_lines');

		array_push($cp, array('$O '.$ops, '', msg('Function'), False, True));
		array_push($cp, array('$S5', '', msg('Separador'), False, True));
		$tela = $form -> editar($cp, '');
		return ($tela);
	}

    function duplicate_lines($txt)
        {
            $ln = explode(chr(10),$txt);
            $l = array();
            $sx = '';
            $i = 0;
            for ($r=0;$r < count($ln);$r++)
                {
                    if (isset($l[$ln[$r]]))
                        {
                            $i++;
                        } else {
                            $l[$ln[$r]] = 1;
                            $sx .= $ln[$r].chr(10);
                        }
                }
            $sx = '#################### '.$i.' '.msg('deleted_lines').chr(10).$sx;
            return($sx);
        }

    function change_terms($txt='',$sep='')
        {
            $th = $this -> skoses -> th(); 
            $ter = $this -> skoses -> from_to($th, '¢', '');
            $ln = explode(chr(13).chr(10),$ter);
            for ($r=0;$r < count($ln);$r++)
                {
                    $sz = substr($ln[$r],0,strpos($ln[$r],'¢'));
                    $ln[$r] = strzero(strlen($sz),5).'¢'.$ln[$r];
                }
            rsort($ln);
            for ($r=0;$r < count($ln);$r++)
                {
                    $t = explode('¢',$ln[$r]);
                    if (count($t) == 3)
                    {
                        $txt = troca($txt,$sep.$t[1].$sep,$sep.$t[2].$sep.' ');
                    }
                }
            return($txt);
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
    
    function word_count($txt)
        {
            /* dicionario de dados */
            /* recupera as stop words do site ************************************/
            $tr = file_get_contents('https://www.ufrgs.br/tesauros/index.php/thesa/terms_from_to/64/txt');
            $tr = troca($tr,chr(13),';');            
            $tr = LowerCaseSQL($tr);
            $tr = splitx(';',$tr);
            
            $wr = array();
            for ($r=0;$r < count($tr);$r++)
                {
                    $w = $tr[$r];
                    $w = troca($w,'=>',';');
                    $w = splitx(';',$w);
                    $w1 = $w[0];
                    $w2 = $w[0];
                    $wr[$w1] = $w2;
                }           
                                    
            /* recupera as stop words do site ************************************/
            $sw = file_get_contents('https://www.ufrgs.br/tesauros/index.php/thesa/terms_from_to/69/txt');            
            #$sw = file_get_contents('_documment/stopword.txt');
            $sw = troca($sw,'=>[sw]','');            
            $sw = LowerCaseSQL($sw);            
            for ($r=0;$r < 32;$r++)
                {                
                    $sw = troca($sw,chr($r),';');
                }
            $sw = splitx(';',$sw);
            /********** TEXTO ***************************************************/
            $txt = LowerCaseSQL($txt);
            $txt = troca($txt,chr(13),' ');
            $txt = troca($txt,chr(10),'');
            while (strpos($txt,'  '))
                {
                    $txt = troca($txt,'  ',' ');
                }            
            for ($r=0;$r < 32;$r++)
                {                
                    $txt = troca($txt,chr($r),';');
                }
            $txt = troca($txt,' ',';');
            $txt = troca($txt,'.',';');
            $txt = troca($txt,',',';');
            $txt = troca($txt,'(',';');
            $txt = troca($txt,')',';');
            $txt = troca($txt,'[',';');
            $txt = troca($txt,']',';');
            $txt = troca($txt,'{',';');
            $txt = troca($txt,'}',';');
            $txt = troca($txt,':',';');
            $txt = troca($txt,'- ',';');
            $txt = troca($txt,'"',';');
            $txt = troca($txt,'“',';');
            $txt = troca($txt,'”',';');
            /********** TERMOS *********/
            foreach ($wr as $key => $value) {                
                    $key = troca($key,' ',';');
                    $value = ';['.$value.'];';    
                    //echo '<br>==>'.$key.'=='.$value;
                    $txt = troca($txt,';'.$key.';',$value);
                }             
            
            /********** STOP WORDS *********/
            for ($r=0;$r < count($sw);$r++)
                {
                    $st = $sw[$r];
                    $st = ';'.troca($st,' ',';').';';
                    $txt = troca($txt,$st,';');
                }            
            while (strpos($txt,';;'))
                {
                    $txt = troca($txt,';;',';');
                }                        
            
            $ln = splitx(';',$txt);
            $sx = '';
            $wd = array();
            for ($r=0;$r < count($ln);$r++)
                {
                    $w = $ln[$r];
                    if (isset($wd[$w]))
                        {
                            $wd[$w] = $wd[$w] + 1;
                        } else {
                            $wd[$w] = 1;
                        }
                }
            /******* ordena *************/
            $ln = array();
            foreach ($wd as $word => $tot) {
                $tot = strzero(10000-$tot,7);
                array_push($ln,$tot.'|'.$word);
            }
            sort($ln);
            foreach ($ln as $key => $value) {
                $sx .= substr($value,8,strlen($value)).' ('.(10000 - round(substr($value,0,7))).')'.cr();
            }
            return($sx);
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
		//asort($nl);
		foreach ($nl as $key => $value) {
			$sx .= $value . cr();
		}
		return ($sx);
	}

}
?>
