<?php
class Authorities extends CI_model {
    function inport_marc_auth($txt) {
        $txt = troca($txt, ';', '.,');
        $txt = troca($txt, '$', '|');
        $txt = troca($txt, chr(13), ';');
        $txt = troca($txt, chr(10), '');
        $ln = splitx(';', $txt);
        $auth = array();

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
        /*******************************/
        for ($r=0;$r < count($auth);$r++)
            {
                $nm = $auth[$r]['a'];
                if ($r==0)
                    {
                        $ida = $this->find_literal($nm,1);        
                    }
                $id = $this->find_literal($nm,0);
                $auth[$r]['ida'] = $ida;
                $auth[$r]['id'] = $id;
            }
            
        $a = $this->create_id($auth,'Person');
    }
    
    function find_literal($name='',$nbr=1)
        {
            if ($nbr==1)
                {
                    $nome = nbr_autor($name,7);        
                } else {
                    $nome = $name;
                }
            
            $sql = "select * from find_literal where l_value = '$nome' ";
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
        print_r($names);
        exit;
        
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
