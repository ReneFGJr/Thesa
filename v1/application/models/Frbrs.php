<?php
class frbrs extends CI_model {
    var $table_catalogs = 'catalogs';

    function le($id) {
        $ln = array();
        return ($ln);
    }

    function catalogs_list() {
        $lt = array();
        array_push($lt, $this -> frbrs -> catalogs_view('0000001'));
        array_push($lt, $this -> frbrs -> catalogs_view('0000002'));
        array_push($lt, $this -> frbrs -> catalogs_view('0000003'));
        array_push($lt, $this -> frbrs -> catalogs_view('0000004'));
        return ($lt);
    }

    function catalogs_select($wh = '') {
        $sql = "select * from " . $this -> table_catalogs;
    }

    function catalogs_view($id) {
        $sx = '<a href="' . base_url('index.php/main/catalogs/' . $id) . '">';
        $sx .= '<img src="' . base_url('_acervo/thumb/' . $id . '.jpg') . '" style="width: 90%;" class="btn-social">';
        $sx .= '</a>';
        return ($sx);
    }

    function rdf_class_edit($class) {
        $sql = "select * from rdf_resource							
							INNER JOIN rdf_prefix ON rs_prefix = id_prefix
							LEFT JOIN auth_id_name on ia_propriety = id_rs
						where RS_GROUP = '$class'
						order by RS_GROUP";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();

        $sx = '<table width="100%" class="table">';
        for ($r = 0; $r < count($rlt); $r++) {
            $line = $rlt[$r];
            $sx .= '<tr>';
            $sx .= '<td>';
            $sx .= '<tt>';
            $sx .= $line['prefix_ref'];
            $sx .= ':';
            $sx .= $line['rs_propriety'];
            $sx .= '</tt>';
            $sx .= '</td>';

            $sx .= '<td>';
            $sx .= '<tt>';
            $sx .= msg($line['rs_propriety']);
            $sx .= '</tt>';
            $sx .= '</td>';

            $sx .= '</tr>';
        }
        $sx .= '</table>';
        return ($sx);
    }

    function vis($data) {
        /*
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        */
        $nd = '';
        $ed = '';
        /* NODES */
        $id = 1;
        $nd .= '{id:'.($id++).', label: \''.$data['rl_value'].'\'}';
        
        /* Geral */
        for ($r=0;$r < count($data['terms_bt']);$r++)
            {
                $l = $data['terms_bt'][$r];
                if (strlen($nd) > 0)
                    {
                        $nd .= ','.cr();
                    }
                $nd .= '{id:'.($id).', label: \''.$l['rl_value'].'\'}';
                $ed .= '{from: 1, to: '.$id.', label: \''.msg('TG').'\',     font: {align: \'middle\'}},'; 
                $id++;      
            }
                
        /* Específico */
        for ($r=0;$r < count($data['terms_nw']);$r++)
            {
                $l = $data['terms_nw'][$r];
                if (strlen($nd) > 0)
                    {
                        $nd .= ','.cr();
                    }
                $nd .= '{id:'.($id).', label: \''.$l['rl_value'].'\'}';
                $ed .= '{from: 1, to: '.$id.', label: \''.msg('TE').'\',     font: {align: \'middle\'}},'; 
                $id++;      
            }  
        /* Relateds */
        for ($r=0;$r < count($data['terms_tr']);$r++)
            {
                $l = $data['terms_tr'][$r];
                if (strlen($nd) > 0)
                    {
                        $nd .= ','.cr();
                    }
                $nd .= '{id:'.($id).', label: \''.$l['rl_value'].'\'}';
                $ed .= '{from: 1, to: '.$id.', label: \''.msg($l['prefix_ref'].':'.$l['rs_propriety']).'\',     font: {align: \'middle\'}},'; 
                $id++;      
            }
        /* TYPE EDGE
                {from: 1, to: 2, label: \'middle\',     font: {align: \'middle\'} , },
                {from: 1, to: 3, label: \'top\',        font: {align: \'top\'}},
                {from: 2, to: 4, label: \'horizontal\', font: {align: \'horizontal\'}},
                {from: 2, to: 5, label: \'bottom\',     font: {align: \'bottom\'}}
          TYPE NODE
                {id: 2, label: \'Node 2\'},
                {id: 3, label: \'Node 3:\nLeft-Aligned\', font: {\'face\': \'Monospace\', align: \'left\'}},
                {id: 4, label: \'Node 4\'},
                {id: 5, label: \'Node 5\nLeft-Aligned box\', shape: \'box\',
                 font: {\'face\': \'Monospace\', align: \'left\'}} 
         */
        $sx = '  <link href="' . base_url('css/vis.min.css') . '" rel="stylesheet">' . cr();
        $sx .= '<script type="text/javascript" src="' . base_url('js/vis.min.js') . '"></script>' . cr();
        $sx .= '
            <div id="mynetwork" style="border:0px solid #000000; height: 500px;"></div>
            <pre id="eventSpan"></pre>
            
            <script type="text/javascript">
              // create an array with nodes
              var nodes = [
                '.$nd.'
              ];
            
              // create an array with edges
              var edges = [
                    '.$ed.'
              ];
            
              // create a network
              var container = document.getElementById(\'mynetwork\');
              var data = {
                nodes: nodes,
                edges: edges
              };
              var options = {physics:false};
              var network = new vis.Network(container, data, options);
            
                network.on("click", function (params) {
                    params.event = "[original event]";
                    //document.getElementById(\'eventSpan\').innerHTML = \'<h2>Click event:</h2>\' + JSON.stringify(params, null, 4);
                });
            </script>';

        return ($sx);
    }

}
?>
