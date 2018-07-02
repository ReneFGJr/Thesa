<?php
class thesa_api extends CI_model {
    function index($d1 = '', $d2 = '') {
        $user = get("user");
        $pass = get("password");
        $term = get("term");
        if (strpos($term,'@') > 0)
            {
                $term = substr($term,0,strpos($term,'@'));
            }
        $th = $d1;
        if ((strlen($term) == 0) or (strlen($th) == 0)) {
            http_response_code(500);
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            echo "ERRO - Empty Term";
            return ("");
        }
        $this -> load -> model("skoses");

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
        }
        echo '</thesa>';
    }

}
