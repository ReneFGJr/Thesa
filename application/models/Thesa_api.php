<?php
class thesa_api extends CI_model {
    function cp_th($id = '') {
        $cp = array();
        array_push($cp, array('$H8', 'id_pa', '', true, true));
        array_push($cp, array('$S80', 'pa_name', msg('thesaurus_name'), true, true));
        array_push($cp, array('$T80:5', 'pa_description', msg('thesaurus_description'), false, true));
        array_push($cp, array('$A1', '', msg('thesaurus_description'), false, false));
        array_push($cp, array('$T80:8', 'pa_introdution', msg('thesaurus_introdution'), false, true));
        array_push($cp, array('$T80:3', 'pa_audience', msg('thesaurus_audience'), false, true));
        array_push($cp, array('$T80:6', 'pa_methodology', msg('thesaurus_methodology'), false, true));
        array_push($cp, array('$Q id_lg:lg_language:select * from language order by lg_order', 'pa_language', msg('thesaurus_language_pref'), true, true));
        array_push($cp, array('$C1', 'pa_multi_language', msg('thesaurus_multilanguage'), false, false));
                

        $op = '1:' . msg('th_type_1');
        $op .= '&2:' . msg('th_type_2');
        $op .= '&3:' . msg('th_type_3');
        $op .= '&4:' . msg('th_type_4');
        $op .= '&5:' . msg('th_type_5');
        //$op .= '&6:' . msg('th_type_6');
        array_push($cp, array('$O' . $op, 'pa_type', msg('thesaurus_type'), true, true));

        $ops = '1:' . msg('status_1');
        $ops .= '&2:' . msg('status_2');
        array_push($cp, array('$O ' . $ops, 'pa_status', msg('thesaurus_status'), true, true));
        array_push($cp, array('$B8', '', msg('save'), false, true));
        return ($cp);
    }

    function le($id)
    {
        $sql = "select * from th_thesaurus where id_pa = ".$id;
        $rlt = $this->db->query($sql);
        $rlt = $rlt->result_array();
        $line = $rlt[0];
        return($line);
    }
    function check_token()
    {
        return(1);
    }

    function index($d1 = '', $d2 = '') 
    {
        if ($this->check_token() ==1)
        {
            $dt['erro'] = 0;
            $dt['status'] = 'ok';
            $dt['api'] = $d1;
            switch($d1)
            {
                case 'check':
                $dt['term'] = get("q");
                $dt['thesa'] = get("th");
                $dt['result'] = $this->api_check($dt['term'],$dt['thesa']);
            }
            echo json_encode($dt);
        } else {
            echo json_encode(array("erro"=>1,"status"=>"Token Invalid"));
        }
    }

    function checksum($term)
    {
        $term = lowercasesql($term);
        $cs = 0;
        for ($r=0;$r < count($term);$r++)
        {
            $c = substr($term,$r,1);
            $cs = $cs + ord($c);
        }
        return($cs);
    }

    function api_check($term,$th)
    {
        $this->load->model("frbrs");
        $new = get("new");
        $t = troca($term,' ',';').';';
        $t = splitx(';',$t);
        $wh = '';
        if (round($th) == 0) { return(array()); }
        for ($r=0;$r < count($t);$r++)
        {
            if (strlen($wh) > 0) { $wh .= ' AND '; }
            $wh .= '(rl_value like  \'%' . $t[$r] . '%\') ';
        }
        if (count($t) == 0)
        {
            return(array());
        }
        $sql = "select * from rdf_literal
        INNER JOIN th_concept_term ON ct_term = id_rl and ct_th = $th
        INNER JOIN rdf_resource ON ct_propriety = id_rs 
        where $wh";

        $csm = $this->checksum($term);

        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();   
        $dt = array(); 
        $dts = array();
        $dtc = array();
        for ($r=0;$r < count($rlt);$r++)
        {
            $line = $rlt[$r];
            $cs = $this->checksum($line['rl_value']);
            $idcp = $cs/$csm;
            {
                $idc = $line['ct_concept'];
                if (!isset($dts[$idc]))
                {
                    array_push($dtc,array('idc'=>$line['ct_concept'],'name'=>$line['rl_value'],'lang'=>$line['rl_lang'],'ind'=>$idcp));
                    $dts[$idc] = 1;
                }
            }
        }

        for ($r=0;$r < count($dtc);$r++)
        {
            $idc = $dtc[$r]['idc'];
            $da = $this->le_concept($idc);
            $dtc[$r]['autho'] = $da['rl_value'];
        }
        $dt['conecpt'] = $dtc;
        return($dt);
    }

    function le_concept($idc)
    {
        $sql = "select * from th_concept_term
        INNER JOIN rdf_literal ON ct_term = id_rl
        where ct_propriety = 25 and ct_concept = ".$idc;
        
        $rlt = $this->db->query($sql);
        $rlt = $rlt->result_array();
        $line = $rlt[0];
        return($line);
    }

    function index_old($dd1='',$dd2='')
    {
        $user = get("user");
        $pass = get("password");
        $term = get("term");
        $lang = '';
        if (strpos($term,'@') > 0)
        {
            $lang = substr($term,strpos($term,'@')+1,5);
            $term = substr($term,0,strpos($term,'@'));
        }
        $th = $d1;
        if ((strlen($term) == 0) or (strlen($th) == 0)) {
            //http_response_code(500);
            //header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            echo "ERRO - Empty Term";
            exit;
            return ("");
        }
        $this -> load -> model("Skoses");

        header('Content-type: application/xml');
        echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
        echo '<thesa>';

        $sql = "select * from rdf_literal
        INNER JOIN th_concept_term ON ct_term = id_rl and ct_th = $th
        INNER JOIN rdf_resource ON ct_propriety = id_rs 
        where rl_value = '" . $term . "'";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        if (count($rlt) == 1) {
            $line = $rlt[0];
            $c = $line['ct_concept'];

            $sql = "
            select * from rdf_literal 
            INNER JOIN th_concept_term ON ct_term = id_rl and ct_th = 64 
            INNER JOIN rdf_resource ON ct_propriety = id_rs 
            where ct_concept = $c ";
            $rlt2 = $this -> db -> query($sql);
            $rlt2 = $rlt2 -> result_array();
            echo '<concept>thesa:c' . $c . '</concept>';

            for ($r = 0; $r < count($rlt2); $r++) {
                $data = $rlt2[$r];
                $name = $data['rl_value'];
                $lang = $data['rl_lang'];
                echo '<' . $data['rs_propriety'] . '>';
                echo '<term>'.$name .'</term>';                
                echo '<lang>'.$lang.'</lang>';
                echo '</' . $data['rs_propriety'] . '>';

            }
        } else {
            echo '<error>not found '.$term.'</error>';
            /******************************/
            $this -> skoses -> incorpore_terms($term, $d1, $lang, $lc);
        }
        echo '</thesa>';
    }

}
