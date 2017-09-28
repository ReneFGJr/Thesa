<?php
class find extends CI_controller {
    function __construct() {
        parent::__construct();
        $this -> lang -> load("skos", "portuguese");
        $this -> load -> library('form_validation');
        $this -> load -> database();
        $this -> load -> helper('form');
        $this -> load -> helper('form_sisdoc');
        $this -> load -> helper('url');
        $this -> load -> library('session');
        $this -> load -> helper('xml');
        $this -> load -> helper('email');

        date_default_timezone_set('America/Sao_Paulo');
        /* Security */
        //		$this -> security();
    }

    function cab($nb = 1) {
        $this -> load -> view('find/header/header', null);
        if ($nb > 0) {
            $this -> load -> view("find/header/cab", null);
            $this -> load -> view('find/header/menu_top');
            $data['content'] = '<br><br><br><br><br>';
            $this -> load -> view('content', $data);
        }
    }

    function foot() {
        $sx = 'Rodape<br><br><br><br>';
        $data['content'] = $sx;
        $data['title'] = '';
        $data['fluid'] = true;
        $data['class'] = 'foot';
        $this -> load -> view('content', $data);
    }

    function index() {
        $this -> load -> model('finds');
        $this -> cab(1);

        $this -> load -> view('find/search');

        $tela = '<div class="container"><div class="row">' . cr();
        $tela .= '<div class="col-sm-6 col-md-6 col xs-6">' . cr();
        $tela .= '<img src="' . base_url('img/find/logo_find.png') . '" class="img-responsive">' . cr();
        $tela .= '</div>' . cr();
        $tela .= '</div></div>' . cr();

        $data['content'] = $tela;
        $this -> load -> view('content', $data);
    }

    function cat() {
        $this -> load -> model('finds');
        $this -> cab();
        $title = '<span style="color: #c0c0ff">Catalog</span>';
        $title .= ' ';
        $title .= '<span style="color: #3030c0">Book</span>';

        $data['title'] = $title;
        $data['content'] = msg('Cataloging');

        $cp = array();
        $form = new form;
        array_push($cp, array('$H8', '', '', False, False));
        array_push($cp, array('$S20', '', msg('Nº tombo'), True, True));
        array_push($cp, array('$S20', '', msg('ISBN'), False, True));
        array_push($cp, array('$T80:4', '', msg('Title'), False, True));

        array_push($cp, array('$B8', '', msg('find') . ' >>>', False, True));

        $data['content'] = $form -> editar($cp, '');

        if ($form -> saved > 0) {
            /* INSERE ITEM */
            $item = 'ITEM' . strzero(get("dd1"), 7);
            $item_id = $this -> finds -> create_range('Item', $item);

            /* WORK */
            $dd3 = get("dd3");
            if (strlen($dd3) > 0) {
                $idw = $this -> finds -> create_range('WORK', $dd3);
                $title = $dd3;
            }

            /* ISBN */
            $dd2 = get("dd2");
            if (strlen($dd2) > 7) {
                $isbn = $this -> finds -> isbn($dd2);
                $class = $this -> finds -> find_rdf('ISBN', 'C');
                $idb = $this -> finds -> create_range('ISBN', $isbn);

                /******************************/
                $isbnn = 'ISBN: ' . sonumero($isbn);
                $idc = $this -> finds -> create_literal($isbnn);
                $prop = $this -> finds -> find_rdf('altLabel', 'P');
                $this -> finds -> insert_relation($idb, $idc, $prop, 0);
            }

            /* JOIN ISBN WITH ITEM */

            $tela = '<table width="100%" class="table">';
            if (isset($isbn)) {
                $tela .= '<tr><td width="15%" align="right">ISBN</td><td><b>' . $isbn . '</b></td></tr>';
            }
            if (isset($title)) {
                $tela .= '<tr><td width="15%" align="right">Título do trabalho</td><td><b>' . $title . '</b></td></tr>';
            }

            $tela .= '</table>';
            $tela .= '===>' . $idb;
            $data['content'] .= $tela;

        }

        $this -> load -> view('content', $data);
    }

    function authority()
        {
        /* Edit attribute */
        $this -> load -> model('finds');
        $this -> cab();
        
        $tela = '';
        $tela .= '<h1>'.msg('Authoriry_control').'</h1>';
        $tela .= '<a href="'.base_url('index.php/find/authority_viaf').'" class="btn btn-default">'.msg('inport_viaf').'</a>';
        $tela .= ' ' ;
        $tela .= '<a href="'.base_url('index.php/find/authority_viaf_file').'" class="btn btn-default">'.msg('inport_viaf_file').'</a>';
        
        
        $data['content'] = $tela;
        
        $this->load->view('content',$data);
        
        
        $this->foot();  
        }

    function authority_viaf()
        {
        /* Edit attribute */
        $this -> load -> model('finds');
        $this -> cab();
        
        $cp = array();
        array_push($cp,array('$H8','','',false,false));
        array_push($cp,array('$A1','',msg('Viaf import'),false,true));
        array_push($cp,array('$S100','','link',false,true));
        
        $txt = msg('link_sample').': https://viaf.org/viaf/12305881/#Bradbury,_Ray,_1920-2012<br>';
        $txt .= '<a href="https://viaf.org/" target="_new">'.msg('access').' '.msg('Viaf - Virtual International Authority File').'</a>';
        array_push($cp,array('$M','',$txt,false,true));        
        array_push($cp,array('$B8','',msg('import link >>>'),false,true));
        
        $id = '';
        $form = new form;
        $form->id = $id;
        
        $tela = $form->editar($cp,'');
        
        if ($form->saved > 0)
            {
                $link = get("dd2");
                $tela .= '<h3><font color="blue">'.msg('inported').':</font> <b>'.
                            $this->finds->authority_fiaf_import($link).'</b></h3>';
            }
        
        $data['content'] = $tela;
        $data['title'] = '';
        $this->load->view('content',$data);        
        
        $this->foot();
           
        }

   function authority_viaf_file()
        {
        /* Edit attribute */
        $this -> load -> model('finds');
        $this -> cab();
               
        /* */
        $form = new form;
        $cp = array();        
        array_push($cp,array('$SFILE','','',true,true));
        array_push($cp,array('$B8','',msg('import file >>>'),false,true));
        $tela = $form->editar($cp,'');
        $data['content'] = $tela;
        $data['title'] = msg('Viaf import file - MARC-21 record');
        
        $this->load->view('content',$data);
        
        $this->foot();
           
        }

    function attribute($id = '', $chk = '', $act = '', $idc = '') {
        /* Edit attribute */
        $this -> load -> model('finds');
        $this -> cab(1);

        $data = $this -> finds -> le_attr($id);

        $this -> load -> view('find/class/view', $data);

        $tela2 = $this -> finds -> attr_edit($id);

        $tela = $this -> finds -> attr_class($id, $chk, $act, $idc) . $tela2;

        $data['content'] = $tela;
        $data['content'] .= $this -> finds -> list_data_attr($id);

        $data['content'] .= '<hr>';
        $data['content'] .= $this -> finds -> list_data_values($id);

        $this -> load -> view('content', $data);
        $this -> foot();
    }

    function classes($id = '') {
        /* Edit attribute */
        $this -> load -> model('finds');
        $this -> cab(1);

        $tela = $this -> finds -> row_classes();
        $data['content'] = $tela;
        $this -> load -> view('content', $data);

        $this -> foot();
    }

    function classes_ed($id = '') {
        /* Edit attribute */
        $this -> load -> model('finds');
        $this -> cab(1);
        $tabela = 'rdf_resource';

        $cp = $this -> finds -> cp_class($id);
        $form = new form;
        $form -> id = $id;
        $tela = $form -> editar($cp, $tabela);
        $data['content'] = $tela;
        $this -> load -> view('content', $data);

        if ($form -> saved > 0) {
            redirect(base_url('index.php/find/classes'));
        }
    }

    function tp($action = '') {
        /* Technical preparation */
        $this -> load -> model('finds');
        $this -> cab(1);

        switch($action) {
            case 'work' :
                $class = $this -> finds -> work;
                $name = get("name");
                $lang = get("lang");
                $this -> finds -> create_id($name, $lang, $class);
                break;
            case 'frad' :
                $name = get("name");
                $lang = get("lang");
                $class = get("class");
                $this -> finds -> create_id($name, $lang, $class);
                break;

            default :
                break;
        }
        $this -> foot();
    }

    function editvw($i1, $i2, $i3, $i4 = '', $i5 = '') {
        $this -> load -> model('finds');
        $this -> cab(0);

        if ((strlen($i4) > 0) and (strlen($i5) > 0)) {
            $this -> finds -> rdf_propriety($i1, $i2, $i3, $i4, $i5);
        }

        $tela = $this -> finds -> editvw($i1, $i2, $i3, $i4, $i5);
        $data['content'] = $tela;
        $data['title'] = '';
        $this -> load -> view('content', $data);
    }

    function ve($id = '') {
        $this -> load -> model('finds');
        $this -> cab(1);

        $data = $this -> finds -> le($id);

        if (count($data) > 0) {

            $tela = $this -> load -> view('find/view_work', $data, true);
            $tela .= $this -> finds -> view_propriety($id);

            $data['content'] = $tela;
            $this -> load -> view('content', $data);

            $class = $data['f_class'];

            $tela = $this -> finds -> class_value_edit($class, $id);
            $data['content'] = $tela;
            $this -> load -> view('content', $data);
        }

        $this -> foot();
    }

    function inport_marc() {
        $this -> load -> model('finds');
        $this -> cab();
        $data = array();

        $tx = get("dd2");
        if (strlen($tx) > 0) {
            $tt = $this -> finds -> marc21_in($tx);
        }

        $this -> load -> view('find/catalog', $data);

        $this -> foot();
    }

    function v($id = '') {
        $this -> load -> model('finds');
        $this -> cab(1);

        $data = $this -> finds -> le($id);

        $tela = $this -> load -> view('find/view_work', $data, true);
        $tela .= $this -> finds -> view_propriety($id);

        $data['content'] = $tela;

        if (isset($_SESSION['nivel'])) {
            if ($_SESSION['nivel'] > 0) {
                $tela = '<form action="' . base_url('index.php/find/e/' . $id) . '"><input type="submit" class="btn btn-default" value="' . msg('edit') . '"></form>';
                $data['content'] .= $tela;
            }
        }

        $this -> load -> view('content', $data);
        $this -> foot();
    }

    function ajaxdt($ac = '') {
        if (isset($_GET['ky'])) {
            $_POST["keyword"] = $_GET['ky'];
        }
        if (!empty($_POST["keyword"])) {
            $sql = "SELECT * FROM find_literal 
                        INNER JOIN find_id ON f_literal = id_l
                        where l_value like '%" . $_POST["keyword"] . "%' ORDER BY l_value LIMIT 0,6";
            $rlt = $this -> db -> query($sql);
            $rlt = $rlt -> result_array();

            echo '<ul class="list alert-info">' . cr();
            for ($r = 0; $r < count($rlt); $r++) {
                $line = $rlt[$r];
                $link = '<a href="#" onclick="select_ajax('.$line['id_f'].');"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>';
                echo '<li>' . $link.$line["l_value"] .'</a>'. '</li>' . cr();
            }
            echo '</ul>' . cr();
        }
    }

    function ajax($ac = '', $ed = '', $id = '', $chk = '') {
        $this -> load -> model("finds");
        $chk2 = checkpost_link($ed . 'ed' . $id);

        if ($chk2 != $chk) {
            header('HTTP/1.1 500 Internal Server Error');
            echo "Error, checkpost";
            return ("");
        }
        /********************************************/
        switch($ac) {
            case 'ed_range' :
                echo $this -> finds -> ajax_autocomplete();
                break;
            default :
                echo '
                        <div class="alert alert-danger" role="alert">
                          <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                          <span class="sr-only">Error:</span>
                          ' . msg('action_not_defined') . ': <b>' . $ac . '</b>
                        </div><br>';

                echo 'Action=' . $ac;
                echo '<br>Edition ' . $ed;
                echo '<br>ID ' . $id;

                break;
        }
    }

    function e($id = '') {
        if ((!isset($_SESSION['nivel'])) or ($_SESSION['nivel'] <= 0)) {
            redirect(base_url('index.php/find/v/' . $id));
        }
        /***/
        $this -> load -> model('finds');
        $this -> cab(1);

        $data = $this -> finds -> le($id);

        $tela = $this -> load -> view('find/view_work', $data, true);
        $tela .= $this -> finds -> view_propriety($id);

        $data['content'] = $tela;

        $class = $data['id_rs'];
        $tela = $this -> finds -> class_edit_value($id, $class);
        $data['content'] .= $tela;

        $this -> load -> view('content', $data);
        $this -> foot();
    }

    function work() {
        $this -> load -> model('finds');
        $this -> cab(1);

        $data = array();
        $data['dd2'] = get("dd2");
        $this -> load -> view('find/header/search', $data);

        if (strlen($data['dd2']) > 0) {
            $tela = $this -> finds -> literal($data['dd2']);
            $rst = $this -> finds -> result;

            if ($rst == -1) {
                $tela .= $this -> finds -> incorpore_id($data['dd2'], 'work');
            }

            $data['content'] = $tela . $rst;
            $this -> load -> view('content', $data);
        }

    }

    function inport_marc21() {
        $this -> load -> model('finds');
        $this -> load -> model('authorities');
        $this -> cab(1);
        /* https://viaf.org/viaf/68040144/viaf.xml */

        $cp = array();
        array_push($cp, array('$H8', '', '', false, false));
        array_push($cp, array('$A1', '', msg('authority'), false, false));
        array_push($cp, array('$T80:10', '', msg('marc'), True, True));

        $form = new form;
        $tela = $form -> editar($cp, '');

        if ($form -> saved > 0) {
            $txt = get("dd2");
            $this -> authorities -> inport_marc_auth($txt);
        }

        $data['content'] = $tela;
        $this -> load -> view('content', $data);
        $this -> foot();

    }

    function cataloging() {
        $this -> load -> model('finds');
        $this -> cab(1);

        $cp = array();
        array_push($cp, array('$H8', '', '', false, false));
        array_push($cp, array('$S100', '', msg('work_title'), True, True));

        $sql = "select * from language where lg_active = 1";
        array_push($cp, array('$Q lg_code;lg_language;' . $sql, '', msg('work_language'), True, True));

        $sql = "select * from rdf_resource where rs_type = 'C'";
        array_push($cp, array('$Q rs_propriety:rs_propriety:' . $sql, '', msg('class'), True, True));

        $form = new form;
        $tela = $form -> editar($cp, '');

        if ($form -> saved > 0) {
            $source = get("dd1");
            $idioma = get("dd2");
            $class = get("dd3");
            $dd4 = get("dd4");

            $idb = $this -> finds -> create_range($class, $source, $idioma);

            redirect(base_url('index.php/find/v/' . $idb));
        }

        $data['content'] = $tela;
        $this -> load -> view('content', $data);
        $this -> foot();

    }

    function nomen() {
        $this -> load -> model('finds');
        $this -> cab(1);

        $data['content'] = '';
        $data['title'] = 'FRAD - Group 2';
        $this -> load -> view('content', $data);

        $data = array();
        $data['dd2'] = get("dd2");
        $this -> load -> view('find/header/search', $data);

        if (strlen($data['dd2']) > 0) {
            $tela = $this -> finds -> literal($data['dd2']);
            $rst = $this -> finds -> result;

            $data['content'] = $tela . $rst;
            $this -> load -> view('content', $data);
        }

    }

    function update() {
        $this -> load -> model('finds');
        $this -> cab(1);
        $file = 'xml/thesa-01.xml';
        $table = 'rdf_resource';

        $class = 64;
        $tela = $this -> finds -> import_xml($file, $class);
        $data['content'] = $tela;
        $this -> load -> view('content', $data);
    }

}
?>
