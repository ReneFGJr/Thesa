<?php
class Authorities extends CI_model {
    function inport_marc_auth($txt) {
        $txt = troca($txt, ';', '.,');
        $txt = troca($txt, '$', '|');
        $txt = troca($txt, chr(13), ';');
        $txt = troca($txt, chr(10), '');
        $ln = splitx(';', $txt);
        $auth = array();
		
		/* phase I */
        for ($r = 0; $r < count($ln); $r++) {
            $l = $ln[$r];
            $fld = substr(troca($l, ' ', ''), 0, 5);
            $fd = substr($fld, 0, 3);
            switch ($fd) {          	
                case '100' :
                    $ds = $this -> extract_marc($l);
                    array_push($auth, $ds);
                    break;
                case '400' :
                    $ds = $this -> extract_marc($l);
                    array_push($auth, $ds);
                    break;
                case '700' :
                    $ds = $this -> extract_marc($l);
                    array_push($auth, $ds);
                    break;
            }
        }
        $ida = 0;
		$idc = 0;
        /*******************************/
        for ($r=0;$r < count($auth);$r++)
            {
                $nm = $auth[$r]['a'];
                if ($r==0)
                    {
                        $ida = $this->find_literal($nm);        
                    }
                $id = $this->find_literal($nm,0);
                $auth[$r]['ida'] = $ida;
                $auth[$r]['id'] = $id;
            }
		$idc = $this->create_id($auth,'Person');			
			

		/* phase II */
        for ($r = 0; $r < count($ln); $r++) {
            $l = $ln[$r];
            $fld = substr(troca($l, ' ', ''), 0, 5);
            $fd = substr($fld, 0, 3);
            switch ($fd) {
                case '040' :
					/* autoridade */
                    $ds = $this -> extract_marc($l);
					if (isset($ds['a']))
						{
							$source = 'Catalog by '.$ds['a'];						
							$class = $this->finds->find_rdf('Organization','C');
							$idb = $this->finds->create_range('Organization',$source);
							
							$prop = $this->finds->find_rdf('is_agencyCatalog','P');									
							$this->finds->insert_relation($idc,0,$prop,$idb);							
						}
                case '100' :
					$born = '';
					$dead = '';
                    $ds = $this -> extract_marc($l);
                    if (isset($ds['d']))
						{
							$dt = $ds['d'];
							if (strpos($dt,'-') > 0)
								{
									$born = trim(substr($dt,0,strpos($dt,'-')));
									$dead = trim(substr($dt,strpos($dt,'-')+1,strlen($dt)));
								} else {
									$born = trim(substr($dt,0,strpos($dt,'-')));
									$dead = '';
								}							
							
							if (strlen($born) != '')
								{
									$class = $this->finds->find_rdf('Date','C');
									$idb = $this->finds->create_range('Date',$born);
									
									$prop = $this->finds->find_rdf('wasBorn','P');									
									$this->finds->insert_relation($idc,0,$prop,$idb);
								}
							if (strlen($dead) != '')
								{
									$class = $this->finds->find_rdf('Date','C');
									$idb = $this->finds->create_range('Date',$dead);
									
									$prop = $this->finds->find_rdf('wasDead','P');
									$this->finds->insert_relation($idc,0,$prop,$idb);
								}
						}
                    break;            	
            }
        }			
    }
    
    function find_literal($name='')
        {
			$nome = $name;       
            
            $sql = "select * from find_literal where l_value = '$nome'";
            $rlt = $this->db->query($sql);
            $rlt = $rlt->result_array();
            
            if (count($rlt) == 0)
                {
                    $zsql = "insert into find_literal (l_value, l_language)
                            value ('$nome','')";
                    $rlt = $this->db->query($zsql);

                    $rlt = $this->db->query($sql);
                    $rlt = $rlt->result_array();
                }
            $line = $rlt[0];
            $id = $line['id_l'];
            return($id);
        }
        
    function create_id($names, $class) {
    	$wh = '';
		$class = $this->finds->find_rdf('prefLabel','P');
		for ($r=0;$r < count($names);$r++)
			{
				if (strlen($wh) > 0)
					{
						$wh .= ' OR ';
					}
				$wh .= '(fr_literal = '.$names[$r]['id'].')';
			}
		
		$sql = "select * from find_rdf where ($wh)  AND (fr_propriety = $class)";
		$rlt = $this->db->query($sql);
		$rlt = $rlt->result_array();
		
		if (count($rlt) == 0)
			{
				$this->finds->create_id($names[0]['a'],'','Person');
				$rlt = $this->db->query($sql);
				$rlt = $rlt->result_array();
			}
		$auth = $rlt[0]['fr_id_1'];
		$class = $this->finds->find_rdf('altLabel','P');
		
		for ($r=1;$r < count($names);$r++)
			{
				$this->finds->insert_relation($auth,$names[$r]['id'],$class);
			}

        return($auth);        
    }        

    function extract_marc($t)
        {
            $t = troca($t,'|',';');
            $lt = splitx(';',$t);
            $au = array();
            for ($r=1;$r < count($lt);$r++)
                {
                    $lr = substr($lt[$r],0,1);
                    $nm = substr($lt[$r],1,strlen($lt[$r]));
                    $au[$lr] = $nm;
                }
            return($au);
        }

}
?>
