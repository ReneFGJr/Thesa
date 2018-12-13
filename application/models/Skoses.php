<?php
class skoses extends CI_model {
    var $CO = 25;
    /* Conceito */
    var $TG = 26;
    /* Termo Geral */
    var $BT = 27;
    /* Termo Específico */
    var $TRc = 28;
    /* Termo Coordenado */
    var $TRu = 29;
    /* União com */
    var $TH = 34;
    var $new = 0;

    /* Hidden */
    VAR $table_concept = 'th_concept';
    VAR $table_thesaurus = 'th_thesaurus';
    var $table_terms = 'rdf_literal';
    var $chave = 'pweio23908d09m09e8m';

    var $name = '';
    var $prefix = '';
    var $name_contact = '';
    var $name_contact_email = '';
    var $url = '';

    var $line = array();

    function __construct() {
        $sql = "select * from thesa where id_thesa = 1";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        if (count($rlt) == 0) {
            $this -> load -> view('skos/510', $data);
        } else {
            $line = $rlt[0];
            $this -> name = $line['thesa_name'];
            $this -> prefix = $line['thesa_prefix'];
            $this -> name_contact = $line['thesa_contact'];
            $this -> name_contact_email = $line['thesa_contact_email'];
            $this -> url = $line['thesa_url'];
        }
    }

    /* Recover ID Thesaurus */
    function th($th = '') {
        if (strlen($th) == 0) {
            if (!isset($_SESSION['skos'])) {
                redirect(base_url('index.php/thesa'));
            }
            $th = $_SESSION['skos'];
        }
        return ($th);
    }

    /* Read Term */
    function le_term($id = '', $th = '') {
        $sql = "select * from rdf_literal 
					INNER JOIN rdf_literal_th ON lt_term = id_rl
					LEFT JOIN th_concept_term ON ct_term = id_rl and ct_propriety = 25 and ct_th = $th
						WHERE lt_thesauros = $th AND id_rl = " . $id;
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        if (count($rlt) > 0) {
            $line = $rlt[0];
        } else {
            $line = array();
        }
        return ($line);

    }

    function le_tree_not_assign($id = '', $data) {
        $sql = "SELECT t1.ct_concept as c1, rl_value FROM 
					(select * from th_concept_term where ct_propriety = 25) as T1
				    LEFT JOIN (select * from th_concept_term where ct_propriety = 26) as T2 on T1.ct_concept =  T2.ct_concept_2
				    left join rdf_literal ON t1.ct_term = id_rl
					WHERE T2.ct_concept IS null AND t1.ct_th = $id ";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        return ($rlt);
    }

    /* Read Thesaurus Descriptions */
    function le_skos($th) {
        $th = round(sonumero($th));
        $sql = "select * from " . $this -> table_thesaurus . " 
						WHERE id_pa = $th";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        if (count($rlt) > 0) {
            $line = $rlt[0];
            $line['allow'] = $this -> le_c_users($th);
            $filename = 'img/background/background_thema_' . $th . '.jpg';
            if (file_exists($filename)) {
                $line['image_bk'] = base_url($filename);
            } else {
                $line['image_bk'] = base_url('img/background_custumer/biulings.jpg');
            }
            $line['authors'] = array();
            $sql = "select distinct us_nome from th_users
						INNER JOIN users ON id_us = ust_user_id
						WHERE ust_th = $th and ust_status = 1";
            $rrr = $this -> db -> query($sql);
            $rrr = $rrr -> result_array();
            $line['authors'] = $rrr;
            return ($line);
        } else {
            /* Thesaurus Not Found */
            redirect(base_url('index.php/thesa'));
        }
    }

    /* Read Concept */
    function le($id, $th = '') {
        $th = round(sonumero($th));
        $sql = "select * from " . $this -> table_concept . " 
					INNER JOIN th_concept_term ON ct_concept = id_c
					INNER JOIN rdf_literal ON id_rl = ct_term
					WHERE id_c = $id";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        if (count($rlt) > 0) {
            $line = $rlt[0];
            return ($line);
        } else {
            return ( array());
        }
    }

    function le_images($id) {
        $img = array();
        $id = round($id);
        $sql = "select * from rdf_image_concept where ic_concept = $id";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();

        for ($r = 0; $r < count($rlt); $r++) {
            $line = $rlt[$r];
            array_push($img, $line['ic_url']);
        }
        return ($img);
    }

    /* Read Concept */
    function le_c($id, $th = '') {
        $th = round(sonumero($th));
        $prop = 25;
        $cp = 'id_c, c_th, c_concept, c_agency, rl_value, rl_lang, pa_name, ct_th, ct_term';
        $sql = "select $cp from " . $this -> table_concept . " 
					INNER JOIN th_concept_term ON ct_concept = id_c
					INNER JOIN rdf_literal ON id_rl = ct_term
					INNER JOIN th_thesaurus ON id_pa = ct_th
						WHERE ct_concept = $id and ct_th = $th and ct_propriety = $prop";

        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        if (count($rlt) > 0) {
            $line = $rlt[0];

            /* Read BT */
            $line['terms_bt'] = $this -> le_c_broader($id, $th);
            $line['terms_nw'] = $this -> le_c_narrowed($id, $th);
            $line['terms_al'] = $this -> le_c_propriety($id, 'FE', $th);
            $line['terms_tr'] = $this -> le_c_associate($id, $th);
            $line['terms_tm'] = $this -> le_c_propriety_group($id, $th, 'TE');
            $line['terms_hd'] = $this -> le_c_hidden($id, $th);
            $line['terms_ge'] = ARRAY();

            $line['images'] = $this -> le_images($id);
            if (count($line['images']) == 0) {
                $img = array('_acervo/thumb/0000000_287px.jpg');
                $line['images'] = $img;
            }
            $line['allow'] = $this -> le_c_users($th);

            $line['notes'] = $this -> le_c_note($id, $th);
            $line['logs'] = $this -> log_show($id);

            return ($line);
        } else {
            return ( array());
        }
    }

    /*************************************************************************** FROM */
    function form_concept($th, $id, $gr = 'FE') {
        $th = round(sonumero($th));
        $sx = '';

        /* ASSOCIATION TYPE */
        $sql = "select * from rdf_resource
					inner join rdf_prefix on id_prefix = rs_prefix 
					where rs_group = '$gr' order by id_rs";

        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $sx .= '<table width="100%" class="table normal">' . cr();
        $sx .= '<tr>';

        if (count($rlt) == 1) {
            $sx .= '	<td width="0%">';
            $sx .= '<input type="hidden" value="' . $rlt[0]['id_rs'] . '" name="tr">';
        } else {
            $sx .= '	<td width="40%">';
            /* RELATION */
            $sx .= '		<span class="font-size: 50%">' . msg('relation_type') . '</span>';
            $sx .= '		<select name="tr" size=10 style="width: 100%">';
            for ($r = 0; $r < count($rlt); $r++) {
                $line = $rlt[$r];
                $op = '';
                if ($line['id_rs'] == get("tr")) { $op = ' selected ';
                }
                $sx .= '	<option value="' . $line['id_rs'] . '" class="middle" ' . $op . '>' . msg($line['prefix_ref'] . ':' . $line['rs_propriety']) . '</option>';
            }
            $sx .= '		</select>';

        }
        $sx .= '</td>';

        $sx .= '<td>';
        $dd10 = get("dd10");
        if (get("acao") == 'Limpar filtro') {
            echo "LIMPAR";
            $_SESSION['filter'] = null;
            $dd10 = '';
        }

        if ((isset($_SESSION['filter'])) AND (strlen($_SESSION['filter']) > 0) AND (strlen($dd10) == 0)) {
            $dd10 = $_SESSION['filter'];
        }
        $sx .= 'Filtro: <input type="text" name="dd10" value="' . $dd10 . '"> <input type="submit" value="Filtrar>>>">';
        if ((isset($_SESSION['filter'])) AND (strlen($_SESSION['filter']) > 0)) {
            $sx .= '<input type="submit" value="Limpar filtro" name="acao">';
        }
        $sx .= '<hr>';
        $wh = $dd10;
        if (strlen($wh) > 0) {
            $_SESSION['filter'] = $wh;
            $wh = " AND (rl_value like '%$wh%')";
        } else {
            if ((isset($_SESSION['filter']) > 0) AND (strlen($_SESSION['filter']) > 0)) {
                $wh = $_SESSION['filter'];
                $wh = " AND (rl_value like '%$wh%')";
            }
        }
        $sx .= '<span class="font-size: 50%">' . msg('select_description') . '</span>';
        $sql = "SELECT * FROM rdf_literal_th
				INNER JOIN rdf_literal ON lt_term = id_rl
				LEFT JOIN th_concept_term on  id_rl = ct_term and ct_th = $th
				where (lt_thesauros = $th and id_ct is null) $wh
				order by rl_value";

        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();

        $sx .= '	<select name="tm" size=8 style="width: 100%">';
        for ($r = 0; $r < count($rlt); $r++) {
            $line = $rlt[$r];
            $op = '';
            if ($line['id_rl'] == get("tm")) { $op = ' selected ';
            }

            $sx .= '	<option value="' . $line['id_rl'] . '" class="middle" ' . $op . '>' . $line['rl_value'] . '</option>';
        }
        $sx .= '	</select>';
        $sx .= '</td></tr>';
        $sx .= '</table>';

        $sx .= '<div class="row" style="margin-top: 12px;"><div class="col-md-10 col-md-offset-1 text-right">';
        $sx .= '	<input type="submit" name="action" class="btn btn-secondary " value="' . msg('save') . '">';
        $sx .= '	<input type="submit" name="action" class="btn btn-secondary " value="' . msg('save_continue') . '">';
        $sx .= '	<span onclick="wclose();" class="btn btn-secondary ">' . msg('close') . '</span>';
        $sx .= '</div></div>';
        return ($sx);
        return ($sx);

    }

    /*************************************************************************** FROM */
    function form_concept_tf($th, $id) {
        $th = round(sonumero($th));
        $sx = '	<div class="row"><div class="col-md-10 col-md-offset-1">';
        $sx .= '<span class="font-size: 50%">' . msg('description') . '</span>';
        $sx .= '	<textarea name="nt" rows="8" style="width: 100%">' . get("nt") . '</textarea>';
        $sx .= '</div></div>';

        /* LANGUAGE */
        $sql = "select * from language order by lg_order ";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $sx .= '	<div class="row" style="margin-top: 12px;">
						<div class="col-md-10 col-md-offset-1">';
        $sx .= '	<select name="tt" size=1 style="width: 100%">';
        $sx .= '	<option value="" class="middle">::: ' . msg('select_the_relation') . '</option>';
        $sx .= '	<option value="por" class="middle" selected>' . msg('por') . '</option>';
        for ($r = 0; $r < count($rlt); $r++) {
            $line = $rlt[$r];
            $op = '';
            if ($line['lg_code'] == get("tt")) { $op = ' selected ';
            }
            $sx .= '	<option value="' . $line['lg_code'] . '" class="middle" ' . $op . '>' . msg($line['lg_language']) . '</option>';
        }
        $sx .= '	</select>';
        $sx .= '</div></div>';

        /* ASSOCIATION TYPE */
        $sql = "select * from rdf_resource
					inner join rdf_prefix on id_prefix = rs_prefix 
					where rs_group = 'NT' order by id_rs";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $sx .= '	<div class="row" style="margin-top: 12px;"><div class="col-md-10 col-md-offset-1">';
        $sx .= '	<select name="tr" size=4 style="width: 100%">';
        $sx .= '	<option value="" class="middle">::: ' . msg('select_the_relation') . '</option>';
        for ($r = 0; $r < count($rlt); $r++) {
            $line = $rlt[$r];
            $op = '';
            if ($line['id_rs'] == get("tr")) { $op = ' selected ';
            }
            $sx .= '	<option value="' . $line['id_rs'] . '" class="middle" ' . $op . '>' . msg($line['prefix_ref'] . ':' . $line['rs_propriety']) . '</option>';
        }
        $sx .= '	</select>';
        $sx .= '</div></div>';

        $sx .= '<div class="row" style="margin-top: 12px;"><div class="col-md-10 col-md-offset-1 text-right">';
        $sx .= '	<input type="submit" name="action" class="btn btn-secondary " value="' . msg('save') . '">';
        $sx .= '</div></div>';
        return ($sx);
        return ($sx);

    }

    /*************************************************************************** FROM */
    function form_concept_tg($th, $id) {
        $th = round(sonumero($th));
        $sql = "select rl_value, id_c from " . $this -> table_concept . " 
					INNER JOIN th_concept_term ON ct_concept = id_c
					INNER JOIN rdf_literal ON id_rl = ct_term
						WHERE  ct_th = $th and ct_propriety = 25
								AND id_c <> $id
					ORDER BY rl_value";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $sx = '	<div class="row"><div class="col-md-10 col-md-offset-1">';
        $sx .= '	<select name="tg" size=10 style="width: 100%">';
        $sx .= '	<option value="" class="middle">::: ' . msg('select_a_concept') . '</option>';
        for ($r = 0; $r < count($rlt); $r++) {
            $line = $rlt[$r];
            $sx .= '	<option value="' . $line['id_c'] . '" class="middle">' . $line['rl_value'] . '</option>';
        }
        $sx .= '	</select>';
        $sx .= '</div></div>';
        $sx .= '<div class="row" style="margin-top: 12px;"><div class="col-md-10 col-md-offset-1 text-right">';
        $sx .= '	<input type="submit" name="action" class="btn btn-secondary " value="' . msg('save') . '">';
        $sx .= '</div></div>';
        return ($sx);

    }

    /*************************************************************************** FROM */
    function form_concept_tr($th, $id) {
        $th = round(sonumero($th));

        $sx = '	<table class="table" width="100%">' . cr();
        $sx .= '<tr>';

        /* ASSOCIATION TYPE */
        $sql = "select * from rdf_resource
					inner join rdf_prefix on id_prefix = rs_prefix 
					where rs_group = 'TR' order by id_rs";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();

        if (count($rlt) == 1) {
            $sx .= '<td width="0%">';
            $sx .= '	<input type="hidden" name="tgr" value="' . $rlt[0]['id_rs'] . '">';
        } else {
            $sx .= '<td width="50%">';
            $sx .= '	<select name="tgr" size=10 style="width: 100%">';
            $sx .= '	<option value="" class="middle">::: ' . msg('select_the_relation') . '</option>';

            for ($r = 0; $r < count($rlt); $r++) {
                $line = $rlt[$r];
                $sx .= '	<option value="' . $line['id_rs'] . '" class="middle">' . msg($line['prefix_ref'] . ':' . $line['rs_propriety']) . '</option>';
            }
            $sx .= '	</select>';
        }
        $sx .= '</td>';

        /*****/
        $sql = "select rl_value, id_c from " . $this -> table_concept . " 
					INNER JOIN th_concept_term ON ct_concept = id_c
					INNER JOIN rdf_literal ON id_rl = ct_term
						WHERE  ct_th = $th and ct_propriety = 25
								AND id_c <> $id
					ORDER BY rl_value";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();

        $sx .= '<td>';
        $sx .= '	<select name="tg" size=10 style="width: 100%">';
        $sx .= '	<option value="" class="middle">::: ' . msg('select_a_concept') . '</option>';
        for ($r = 0; $r < count($rlt); $r++) {
            $line = $rlt[$r];
            $sx .= '	<option value="' . $line['id_c'] . '" class="middle">' . $line['rl_value'] . '</option>';
        }
        $sx .= '	</select>';
        $sx .= '</td>';

        $sx .= '</tr>';
        $sx .= '</table>';

        $sx .= '<div class="row" style="margin-top: 12px;"><div class="col-md-10 col-md-offset-1 text-right">';
        $sx .= '	<input type="submit" name="action" class="btn btn-secondary " value="' . msg('save') . '">';
        $sx .= '</div></div>';
        return ($sx);

    }

    function le_c_broader($id = '', $th = '') {
        $th = round(sonumero($th));
        $cp = 't1.id_ct as id_ct, t2.id_ct as id_ct2, rl_value, rl_lang, t1.ct_concept as ct_concept, t1.ct_created as ct_created';
        $sql = "select $cp
					FROM th_concept_term as t1
					INNER JOIN th_concept_term as t2 ON t1.ct_concept = t2.ct_concept and t2.ct_propriety = 25 
					INNER JOIN rdf_literal ON id_rl = t2.ct_term
						WHERE t1.ct_concept_2 = $id and t1.ct_th = $th and t1.ct_propriety = 26";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        if (count($rlt) > 0) {
            return ($rlt);
        } else {
            return ( array());
        }
    }

    function le_c_propriety_group($id = '', $th = '', $prop = '') {
        $th = round(sonumero($th));
        $sql = "select * from (
					SELECT ct_concept_2 as idc, ct_propriety as ctp FROM `th_concept_term` 
					INNER JOIN rdf_resource ON id_rs = ct_propriety
					WHERE rs_group = '$prop' and ct_concept = $id and ct_th = $th 
   				 ) as tb1
			 INNER JOIN th_concept_term ON ct_concept = idc and ct_propriety = 25
			 INNER JOIN rdf_literal ON ct_term = id_rl
			 LEFT JOIN rdf_resource ON id_rs = ctp
			 LEFT JOIN rdf_prefix on id_prefix = rs_prefix
			 ORDER BY rl_value";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();

        if (count($rlt) > 0) {
            /* Read TR */
            return ($rlt);
        } else {
            return ( array());
        }

    }

    function le_c_propriety($id = '', $prop, $th = '') {
        $th = round(sonumero($th));
        $sql = "select * from th_concept_term as t1
					INNER JOIN rdf_literal ON id_rl = t1.ct_term
					INNER JOIN rdf_resource ON id_rs = t1.ct_propriety
					INNER JOIN rdf_prefix on id_prefix = rs_prefix
						WHERE t1.ct_concept = $id and t1.ct_th = $th and rs_group = '$prop'
					order by rs_group ";

        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        if (count($rlt) > 0) {
            /* Read BT */
            return ($rlt);
        } else {
            return ( array());
        }
    }

    function le_c_associate($id = '', $th = '') {
        /*********************************/
        $th = round(sonumero($th));

        $cp = 'id_ct2 as id_ct, rl_value, rl_lang, ct_concept, rs_propriety, prefix_ref, ct_created';
        $sql = "select $cp from (
					SELECT id_ct as id_ct2, ct_concept_2 as idc, ct_propriety as ctp FROM `th_concept_term` 
					INNER JOIN rdf_resource ON id_rs = ct_propriety
					WHERE rs_group = 'TR' and ct_concept = $id and ct_th = $th 
   				 ) as tb1
			 INNER JOIN th_concept_term ON ct_concept = idc and ct_propriety = 25
			 INNER JOIN rdf_literal ON ct_term = id_rl
			 LEFT JOIN rdf_resource ON id_rs = ctp
			 LEFT JOIN rdf_prefix on id_prefix = rs_prefix
			 ORDER BY rl_value";
        $rlt = $this -> db -> query($sql);
        $rlt1 = $rlt -> result_array();

        /* PARTE II */
        $sql = "select $cp from (
					SELECT id_ct as id_ct2, ct_concept as idc, ct_propriety as ctp FROM `th_concept_term` 
					INNER JOIN rdf_resource ON id_rs = ct_propriety
					WHERE rs_group = 'TR' and ct_concept_2 = $id and ct_th = $th 
   				 ) as tb1
			 INNER JOIN th_concept_term ON ct_concept = idc and ct_propriety = 25
			 INNER JOIN rdf_literal ON ct_term = id_rl
			 LEFT JOIN rdf_resource ON id_rs = ctp
			 LEFT JOIN rdf_prefix on id_prefix = rs_prefix			 
			 ORDER BY rl_value";

        $rlt = $this -> db -> query($sql);
        $rlt2 = $rlt -> result_array();

        $rlt = array_merge($rlt1, $rlt2);

        if (count($rlt) > 0) {
            /* Read TR */
            return ($rlt);
        } else {
            return ( array());
        }
    }

    function le_c_narrowed($id = '', $th = '') {
        $th = round(sonumero($th));
        $sql = "select * from th_concept_term as t1
					INNER JOIN th_concept_term as t2 ON t1.ct_concept_2 = t2.ct_concept and t2.ct_propriety = 25 
					INNER JOIN rdf_literal ON id_rl = t2.ct_term
						WHERE t1.ct_concept = $id and t1.ct_th = $th and t1.ct_propriety = 26";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        if (count($rlt) > 0) {
            /* Read BT */
            return ($rlt);
        } else {
            return ( array());
        }
    }

    function le_c_note($id = '', $th = '') {
        $th = round(sonumero($th));
        $sql = "select * from rdf_literal_note
					INNER JOIN rdf_resource ON id_rs = rl_type
					INNER JOIN rdf_prefix ON id_prefix = rs_prefix
						WHERE rl_c = " . $id;
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        if (count($rlt) > 0) {
            /* Read BT */
            return ($rlt);
        } else {
            return ( array());
        }
    }

    function le_c_altlabel($id = '', $th = '') {
        $th = round(sonumero($th));
        $sql = "select * from th_concept_term as t1
					INNER JOIN rdf_literal ON id_rl = t1.ct_term
					INNER JOIN rdf_resource ON id_rs = t1.ct_propriety
					LEFT JOIN rdf_prefix ON id_prefix = rs_prefix
						WHERE t1.ct_concept = $id and t1.ct_th = $th and rs_group = 'TE'";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        if (count($rlt) > 0) {
            /* Read BT */
            return ($rlt);
        } else {
            return ( array());
        }
    }

    function le_c_hidden($id = '', $th = '') {
        $th = round(sonumero($th));
        /************* Mudar **********/
        $thp = 34;
        $sql = "select * from th_concept_term as t1
					INNER JOIN rdf_literal ON id_rl = t1.ct_term
						WHERE t1.ct_concept = $id and t1.ct_th = $th and t1.ct_propriety = " . $thp;
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        if (count($rlt) > 0) {
            /* Read BT */
            return ($rlt);
        } else {
            return ( array());
        }
    }

    function le_tree($th = 0) {
        $th = round(sonumero($th));
        $co = 25;
        $sql = "select l1.id_rl as id1, l1.rl_value as lt1,
					   l2.id_rl as id2, l2.rl_value as lt2
					FROM th_concept_term as t1
					INNER JOIN th_concept_term as t2 ON t1.ct_concept_2 = t2.ct_concept and t2.ct_propriety = " . $co . "
					INNER JOIN th_concept_term as t3 ON t1.ct_concept = t3.ct_concept and t3.ct_propriety = " . $co . " 
					INNER JOIN rdf_literal as l1 ON l1.id_rl = t2.ct_term
					LEFT JOIN rdf_literal as l2 ON l2.id_rl = t3.ct_term
						WHERE t1.ct_th = $th and t1.ct_propriety = " . $this -> TG;
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $desc = array();
        $h = array();
        if (count($rlt) > 0) {
            /* Read BT */
            $tr = array();
            for ($r = 0; $r < count($rlt); $r++) {
                $line = $rlt[$r];

                /* Lista dos descritores */
                $id1 = $line['id1'];
                $desc[$id1] = $line['lt1'];
                $id2 = $line['id2'];
                $desc[$id2] = $line['lt2'];

                $h[$id2]['name'] = $line['lt2'];
                $h[$id2][$id1]['name'] = $line['lt1'];

                $tr[$id1] = $id2;
            }

            $tt = 'id,value' . cr();
            $i = 0;
            foreach ($tr as $v1 => $v2) {
                $tx = '';
                $vm = $v2;
                $vb = $v1;
                $tx = '[' . $vm . '][' . $vb . ']';
                $esc = 50;

                while ((isset($tr[$vm])) and ($esc-- > 0)) {
                    $vm = $tr[$vm];
                    $tx = '[' . $vm . ']' . $tx;
                }
                if ($i == 0) {
                    $tt .= $desc[$vm] . cr();
                    $i++;
                }
                $tt .= $tx . ',1' . cr();
            }

            $tc = $tt;
            foreach ($desc as $key => $value) {
                $value = troca($value, '.', ' ');
                $value = troca($value, ',', ' ');
                $tt = troca($tt, '[' . $key . ']', $value . '.');
            }
            $tt = troca($tt, '.,', ',');
            //$tt = troca($tt,' ','');

            $tt = lowercase($tt);
            $file = fopen('xml/flare_' . $th . '.csv', 'w+');
            fwrite($file, $tt) / fclose($file);
            return ($rlt);
        } else {
            return ( array());
        }
    }

    function le_tree_sistematic($th = 0) {
        $th = round(sonumero($th));
        $co = 25;
        $sql = "select l1.id_rl as id1, l1.rl_value as lt1,
                       l2.id_rl as id2, l2.rl_value as lt2
                    FROM th_concept_term as t1
                    INNER JOIN th_concept_term as t2 ON t1.ct_concept_2 = t2.ct_concept and t2.ct_propriety = " . $co . "
                    INNER JOIN th_concept_term as t3 ON t1.ct_concept = t3.ct_concept and t3.ct_propriety = " . $co . " 
                    INNER JOIN rdf_literal as l1 ON l1.id_rl = t2.ct_term
                    LEFT JOIN rdf_literal as l2 ON l2.id_rl = t3.ct_term
                        WHERE t1.ct_th = $th and t1.ct_propriety = " . $this -> TG;
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $desc = array();
        $h = array();
        if (count($rlt) > 0) {
            /* Read BT */
            $tr = array();
            for ($r = 0; $r < count($rlt); $r++) {
                $line = $rlt[$r];

                /* Lista dos descritores */
                $id1 = $line['id1'];
                $desc[$id1] = $line['lt1'];
                $id2 = $line['id2'];
                $desc[$id2] = $line['lt2'];

                $h[$id2]['name'] = $line['lt2'];
                $h[$id2][$id1]['name'] = $line['lt1'];

                $tr[$id1] = $id2;
            }
            $tt = '';
            $i = 0;
            foreach ($tr as $v1 => $v2) {
                $tx = '';
                $vm = $v2;
                $vb = $v1;
                $tx = '[' . $vm . '][' . $vb . ']';
                $esc = 50;

                while ((isset($tr[$vm])) and ($esc-- > 0)) {
                    $vm = $tr[$vm];
                    $tx = '[' . $vm . ']' . $tx;
                }
                if ($i == 0) {
                    $tt .= $desc[$vm] . cr();
                    $i++;
                }
                $tt .= $tx . ',1' . cr();
            }
            $tc = $tt;
            foreach ($desc as $key => $value) {
                $value = troca($value, '.', ' ');
                $value = troca($value, ',', ' ');
                $tt = troca($tt, '[' . $key . ']', $value . '.');
            }
            $tt = troca($tt, '.,', ',');

            $tt = troca($tt, chr(13), ';');
            $tt = troca($tt, ',1', '');
            $ln = splitx(';', $tt);
            asort($ln);
            /******************************/
            $tt = '';
            foreach ($ln as $key => $l) {
                $ll = $l;
                $i = 0;
                while (strpos($l, '.')) {
                    $i++;
                    $l = substr($l, strpos($l, '.') + 1, strlen($l));
                }
                for ($y = 0; $y < ($i); $y++) {
                    $l = '.' . $l;
                }
                $tt .= $l . '<br>' . cr();

            }
            return ($tt);
        } else {
            return ('');
        }
    }

    function le_report($th = 0) {
        $th = round(sonumero($th));
        $sql = "select *
					FROM th_concept_term 
					INNER JOIN rdf_literal ON id_rl = ct_term
						WHERE ct_th = $th and ct_propriety = " . $co . "
					ORDER BY rl_value
				";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $desc = array();
        $h = array();
        for ($r = 0; $r < count($rlt); $r++) {
            $data = $rlt[$r];
            /* Recupera informações sobre o Concecpt */
            $c = $data['ct_concept'];
            $data2 = $this -> skoses -> le_c($c, $th);
            $this -> load -> view("skos/report_conecpt", $data2);
        }
    }

    function le_report_thas($th = 0) {
        $th = round(sonumero($th));
        $sql = "select *
					FROM th_concept_term 
					INNER JOIN rdf_literal ON id_rl = ct_term
						WHERE ct_th = $th and ct_propriety = " . $co . "
					ORDER BY rl_value
				";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $desc = array();
        $h = array();
        for ($r = 0; $r < count($rlt); $r++) {
            $data = $rlt[$r];
            /* Recupera informações sobre o Concecpt */
            $c = $data['ct_concept'];
            $data2 = $this -> skoses -> le_c($c, $th);
            $this -> load -> view("skos/report_conecpt_2", $data2);
        }
    }

    function le_c_users($th = '') {
        $sql = "select * from th_users where ust_th = $th and ust_status = 1";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $rs = array();
        for ($r = 0; $r < count($rlt); $r++) {
            $line = $rlt[$r];
            $id = $line['ust_user_id'];
            $rl = $line['ust_user_role'];
            $rs[$id] = $rl;
        }
        return ($rs);
    }

    function le_tree_js() {
        return ('');
    }

    function le_th($id = 0) {
        return ($this -> le_skos($id));
        /*
         $sql = "select * from " . $this -> table_thesaurus . " where id_pa = " . $id;
         $rlt = $this -> db -> query($sql);
         $rlt = $rlt -> result_array();
         if (count($rlt) > 0) {
         $line = $rlt[0];
         $line['image_bk'] = base_url('img/background_custumer/biulings.jpg');

         $line['authors'] = array();
         $sql = "select distinct us_nome from th_users
         INNER JOIN users ON id_us = ust_user_id
         WHERE ust_th = $id and ust_status = 1";
         $rrr = $this -> db -> query($sql);
         $rrr = $rrr -> result_array();
         $line['authors'] = $rrr;
         return ($line);
         }
         return ( array());
         */
    }

    function le_propriety($id = 0) {
        $sql = "select * from rdf_resource where id_rs = " . $id;
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        if (count($rlt) > 0) {
            $line = $rlt[0];
            return ($line);
        }
        return ( array());
    }

    /* Show */
    function concepts_show($row) {
        $sx = '<ul>' . cr();
        for ($r = 0; $r < count($row); $r++) {
            $line = $row[$r];

            if ($line['ct_concept'] > 0) {
                $sx .= '<li class="term_item">';
                if (isset($line['rs_propriety'])) {
                    $sx .= '<span style="font-size: 75%;">' . msg($line['prefix_ref'] . ':' . $line['rs_propriety']) . ' - </span>';
                }
                $link = '<a href="' . base_url('index.php/thesa/c/' . $line['ct_concept']) . '">';
                $sx .= $link . $line['rl_value'] . '</a> <sup class="supersmall">(' . $line['rl_lang'] . ')</sup>';

                $sx .= ' ';
                $link = base_url('index.php/thesa/te_remove/' . $line['id_ct'] . '/' . checkpost_link($line['id_ct'] . $this -> chave));
                $sx .= '<a href="#" style="color: red" title="Remove" onclick="newwin(\'' . $link . '\',600,300);">';
                $sx .= '<img src="' . base_url('img/icone/remove.png') . '" width="20" border=0>';
                $sx .= '</a>';

                $sx .= '</li>' . cr();
            }

        }
        $sx .= '</ul>' . cr();
        return ($sx);
    }

    function c_remove($id) {

        $sql = "select * from th_concept_term
					left join rdf_literal ON id_rl = ct_term
					where ct_concept_2 = " . round($id) . " OR ct_concept = " . round($id);
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();

        for ($r = 0; $r < count($rlt); $r++) {
            $line2 = $rlt[$r];
            $c = $line2['ct_concept'];
            $th = $line2['ct_th'];
            $idc = $line2['id_ct'];
            $desc = msg('remove_propriety') . ' - "<b>' . $line2['rl_value'] . '</b>" ' . ' (<i>' . $id . '</i>)';
            $this -> skoses -> log_insert($c, $th, 'REMOV', $desc);

            $sql = "delete from th_concept_term where id_ct = " . round($idc);
            $this -> db -> query($sql);
        }

        $sql = "update th_concept set c_ativo = 0 where id_c = " . $id;
        $this -> db -> query($sql);
        return (0);
    }

    function te_remove($id) {

        $sql = "select * from th_concept_term
					left join rdf_literal ON id_rl = ct_term 
					where id_ct = " . round($id);

        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();

        if (count($rlt) > 0) {

            $line2 = $rlt[0];
            $c = $line2['ct_concept'];
            $th = $line2['ct_th'];
            $desc = msg('remove_propriety') . ' - "<b>' . $line2['rl_value'] . '</b>" ' . ' (<i>' . $id . '</i>)';
            $this -> skoses -> log_insert($c, $th, 'REMOV', $desc);

            $sql = "delete from th_concept_term where id_ct = " . round($id);
            $this -> db -> query($sql);
            //exit;
        }
        return (0);
    }

    function concepts_show_rp($row, $tp = '') {
        $sx = '<ul>' . cr();
        for ($r = 0; $r < count($row); $r++) {
            $line = $row[$r];

            if ($line['ct_concept'] > 0) {
                $sx .= '<li class="term_item">';
                $sx .= '<b>' . $tp . '</b> ';
                $sx .= '' . $line['rl_value'] . '' . ' <sup class="supersmall">(' . $line['rl_lang'] . ')</sup>';
                $sx .= '</li>' . cr();
            }

        }
        $sx .= '</ul>' . cr();
        return ($sx);
    }

    /***** EMAIL */
    function email_cab() {
        $sx = '<table width="600" align="center"><tr><td>';
        $sx .= '<font style="font-family: Tahoma, Verdana, Arial; font-size: 14px;">' . cr();
        $sx .= '<font color="blue">THESA</font>' . cr();
        $sx .= '<hr>';
        return ($sx);
    }

    function email_foot() {
        $sx = '';
        $sx .= '<hr>';
        $sx .= '</td></tr></table>';
        return ($sx);
    }

    /* Export to json */
    function to_json($id) {
        $id = round($id);
        $data = $this -> le($id);

        $tree = array();
        /********************** json *****************/
        $sx = '{ "name": "flare" }';
        //$sx .= 'children": [ { "name": "analytics", }';
        //$sx .= '}';
        $fp = fopen('xml/thesa_' . $id . '.json', 'w');
        fwrite($fp, $sx);
        fclose($fp);
        return ($sx);
    }

    function edit_nt($id) {
        $form = new form;
        $cp = array();
        $table = 'rdf_literal_note';
        array_push($cp, array('$H8', 'id_rl', '', False, False));
        array_push($cp, array('$T8:6', 'rl_value', '', True, True));
        $form -> id = $id;
        $form -> table = $table;
        $tela = $form -> editar($cp, $table);

        if ($form -> saved > 0) { $tela = '';
        }
        return ($tela);
    }

    function edit_nt_remove($id) {
        $form = new form;
        $cp = array();
        $table = 'rdf_literal_note';
        $sql = "delete from " . $table . " where id_rl = " . $id;
        $this -> db -> query($sql);
        return ("");
    }

    function edit_nt_confirm($id) {
        $sx = '<br><br>';
        //$data = $this->le_c_note($id);
        //print_r($data);
        $sx .= '<a href="' . base_url('index.php/thesa/ntremove/' . $id . '/' . checkpost_link($id) . '/confirm') . '" class="btn btn-primary">' . msg('confirm_exclude') . '</a>';
        return ($sx);
    }

    function notes_show($l, $edit = 0) {
        $sx = '';
        if (isset($_SESSION['nivel'])) {
            if ($_SESSION['nivel'] > 0) {
                $edit = 1;
            }
        }

        for ($r = 0; $r < count($l); $r++) {
            $line = $l[$r];
            $ed = '';
            if ($edit == 1) {
                $ed = '<a href="#" onclick="newxy(\'' . base_url('index.php/thesa/ntedit/' . $line['id_rl'] . '/' . checkpost_link($line['id_rl'])) . '\',800,400);">[edit]</a>' . cr();
                $ed .= '<a href="#" onclick="newxy(\'' . base_url('index.php/thesa/ntremove/' . $line['id_rl'] . '/' . checkpost_link($line['id_rl'])) . '\',800,400);">[remove]</a>' . cr();
            }
            $sx .= '<span style="font-size: 75%;">' . msg($line['prefix_ref'] . ':' . $line['rs_propriety']) . '</span>
					<p>' . $ed . $line['rl_value'] . '</p>';

            $sx .= '<hr>';
            $ed = '';
        }
        return ($sx);
    }

    /* MY Thesaurus */
    function th_assosiation_users() {
        $sql = "select *
						FROM th_thesaurus
						LEFT JOIN th_users ON ust_th = id_pa
							where ust_user_id is null ";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        for ($r = 0; $r < count($rlt); $r++) {
            $line = $rlt[$r];
            $th = $line['id_pa'];
            $user = $line['pa_creator'];
            $tipo = 'INS';
            $this -> skoses -> user_thesa($th, $user, $tipo);
        }
    }

    function myskoses($user = 0) {
        if ($user == 0) {
            $sql = "select * from th_thesaurus
					where pa_status = 2 
					order by pa_name ";
        } else {
            $sql = "select id_pa, pa_type, pa_name, pa_created, pa_status, 
			                 pa_avaliacao, pa_creator, pa_description, pa_icone
						FROM th_thesaurus
						LEFT JOIN th_users ON ust_th = id_pa
							where (pa_creator = $user) or (ust_user_id = $user)
							group by  id_pa, pa_name, pa_created, pa_status, 
							          pa_avaliacao, pa_creator, pa_description, pa_icone, pa_type
							order by pa_name, pa_status desc  ";
        }
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();

        $sa = '<style>

				</style>';

        $sa .= '<div class="row" style="margin: 80px 0px;">' . cr();
        #for ($r=0;$r < count($rlt);$r++)
        for ($r = 0; $r < count($rlt); $r++) {
            $line = $rlt[$r];
            $link = '<a href="' . base_url('index.php/thesa/select/' . $line['id_pa'] . '/' . checkpost_link($line['id_pa'])) . '">';

            $sa .= '<div class="col-md-2 text-left" style="border-top: 1px #000000 solid; margin-bottom: 40px; padding: 10px;">' . cr();
            if (round($line['pa_icone']) > 0) {
                $sa .= $link . $this -> show_icone($line['pa_icone']) . '</a>';
            } else {
                $sa .= $link . $this -> show_icone($r) . '</a>';
            }

            $sa .= '</div>' . cr();

            $sa .= '<div class="col-md-4 text-left" style="border-top: 1px #000000 solid; margin-bottom: 40px; padding: 10px;">' . cr();
            $sa .= $link . '<span class="middle">' . $line['pa_name'] . '</span>' . '</a>';
            $sa .= '<br><i>' . msg('th_type_' . $line['pa_type']) . '</i>';
            $sa .= '<p>' . $line['pa_description'] . '</span>';
            //$sa .= '<a href="' . base_url('index.php/thesa/th_edit/' . $line['id_pa'] . '/' . checkpost_link($line['id_pa'] . $this -> chave)) . '" class="btn btn-secondary">' . msg('edit') . '</a>';
            if ((isset($_SESSION['id']) and ($line['pa_creator'] == $_SESSION['id']))) {
                if ($line['pa_creator'] == $_SESSION['id']) {
                    $sa .= '<br>';
                    $sa .= '<a href="' . base_url('index.php/thesa/th_edit/' . $line['id_pa'] . '/' . checkpost_link($line['id_pa'] . $this -> chave)) . '" class="small">' . msg('edit') . '</a>';
                }
            }

            $sa .= '</div>';
        }
        $sa .= '</div>' . cr();

        return ($sa);
    }

    function show_icone($id) {
        if ($id < 0) {
            $id = 0;
        }
        $img = 'img/icone/thema/' . strzero($id, 3) . '.png';
        $x = 0;
        while ((!file_exists($img)) and ($x < 50)) {
            $id = 1;
            $x++;
            $img = 'img/icone/thema/' . strzero($id, 3) . '.png';
        }
        $sa = '<img class="img-fluid" src="' . base_url($img) . '" style="padding: 5px;">';
        return ($sa);
    }

    /* Select SKOS */
    function skoses_select($id) {
        $data = array();
        $data['skos'] = $id;
        $data['skos-md5'] = 'skos' . $id;
        $this -> session -> set_userdata($data);
        return (1);
    }

    function termos_pg($id, $ed = '1') {
        $sql = "select substr(rl_value,1,1) as letra 
					FROM th_concept_term 
					INNER JOIN rdf_literal ON id_rl = ct_term
					WHERE ct_th = $id 
					GROUP BY letra
					ORDER BY letra
					";
        $xrlt = $this -> db -> query($sql);
        $xrlt = $xrlt -> result_array();
        $sx = '<nav aria-label="Page navigation text-center"><ul class="pagination">';
        $sx .= '<li class="page-item"><a href="' . base_url('index.php/thesa/terms/' . $id . '/') . '" class="page-link">' . msg('all') . '</a></li>';
        for ($r = 0; $r < count($xrlt); $r++) {
            $line = $xrlt[$r];
            $sx .= '<li><a href="' . base_url('index.php/thesa/terms/' . $id . '/' . $line['letra']) . '" class="page-link">' . $line['letra'] . '</a></li>';
        }

        #if (($ed == 1) and ($this -> autho('', $id))) {
        #	$sx .= '<li><a href="' . base_url('index.php/thesa/concept_add/' . $id . '/') . '" class="page-link">' . msg('add') . '</a></li>';
        #}
        $sx .= '</ul></nav>	';
        return ($sx);
    }

    /* RESUMO DO THESAURUS */
    function myskoses_total() {
        $id = $this -> socials -> user_id();
        $sql = "select count(*) as total from th_thesaurus where pa_creator = $id";
        $xrlt = $this -> db -> query($sql);
        $xrlt = $xrlt -> result_array();
        $line = $xrlt[0];
        return ($line['total']);
    }

    function thesaurus_resume($id) {
        $co = $this -> find_class('Concept');

        $sql = "select count(*) as total from rdf_literal_th where lt_thesauros = $id";

        /*** TERMS WIDTHOUT CONCEPT ***/

        $xrlt = $this -> db -> query($sql);
        $xrlt = $xrlt -> result_array();
        $sx = '<table class="table" width="100%">';
        for ($r = 0; $r < count($xrlt); $r++) {
            $line = $xrlt[$r];
            $sx .= '<tr>';
            $sx .= '<td align="right">' . msg('terms') . '</td>';
            $sx .= '<td>' . $line['total'] . '</td>';
        }

        /***/
        $sql = "select count(*) as total from th_concept_term 
					where ct_th = $id and ct_propriety = " . $co;

        $xrlt = $this -> db -> query($sql);
        $xrlt = $xrlt -> result_array();

        for ($r = 0; $r < count($xrlt); $r++) {
            $line = $xrlt[$r];
            $sx .= '<tr>';
            $sx .= '<td align="right">' . msg('concepts') . '</td>';
            $sx .= '<td>' . $line['total'] . '</td>';
        }
        $sx .= '</table>';
        return ($sx);
    }

    /* Incorpore terns intro thesaurus */
    function incorpore_terms($t = '', $id = '', $lang = '', $lc = '') {
        $th = $_SESSION['skos'];
        $t = troca($t, chr(13), ';');
        $t = troca($t, chr(10), ';');
        //$t = troca($t, '.', ';');
        $t = troca($t, '"', ' ');
        //$t = troca($t, ',', ';');
        $t = troca($t, '?', ';');
        $ln = splitx(';', $t);
        $sx = '<table class="table" width="100%">' . cr();
        $sx .= '<tr><th width="3%">#</th>
					<th width="37%">' . msg('term') . '</th>
					<th width="30%">' . msg('language') . '</th>
					<th width="30%">' . msg('status') . '</th></tr>' . cr();
        for ($r = 0; $r < count($ln); $r++) {
            $t = $ln[$r];
            $t = $this -> prepara_termo($t, $lc);
            $mmm = '<td class="alert alert-warning">' . msg('already_inserted') . '</td>';
            if (strlen($t) > 0) {
                /* Incorpore Term intro Vocabulary Literal */
                $new = $this -> terms_add($t, $id, $lang);

                /* Insert Term intro the Thesaurus */
                $idt = $this -> association_term_th($t, $lang, $id);

                /* Create Concept Into the Thesaurus */
                #$this -> concept_create($t, $th);
                if ($new == 1) {
                    $mmm = '<td class="alert alert-success">' . msg('add_term') . '</td>';
                }
            }
            $sx .= '<tr>';
            $sx .= '<td>' . ($r + 1) . '</td>';
            $sx .= '<td><b>' . $t . '</b></td>';
            $sx .= '<td>' . msg($lang) . '</td>';
            $sx .= $mmm;
            $sx .= '</tr>';
        }
        $sx .= '</table>';
        return ($sx);
    }

    function delete_term_from_th($th, $idt) {
        $sql = "select * from rdf_literal_th where lt_term = $idt and lt_thesauros = $th";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        if (count($rlt) > 0) {
            $sql = "delete from rdf_literal_th where lt_term = $idt and lt_thesauros = $th";
            $rlt = $this -> db -> query($sql);
            return (1);
        } else {
            return (0);
        }
    }

    function terms_add($t = '', $id = '', $lang = '') {
        $sql = "select * from rdf_literal where rl_value = '$t' and rl_type = 24 and rl_lang = '$lang' ";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $sx = '';
        if (count($rlt) == 0) {
            $sqli = "insert into rdf_literal 
							(
							rl_value, rl_type, rl_lang
							) values (
							'$t',24, '$lang')
							";
            $xrlt = $this -> db -> query($sqli);
            $sx .= $t . '@' . $lang . ' <font style="color: green;">' . msg('inserted') . '</font><br>';
            $this -> new = 1;
        } else {
            $sx .= $t . '@' . $lang . ' <font style="color: red;">' . msg('already_inserted') . '</font><br>';
            $this -> new = 0;
        }
        $sql = "select * from rdf_literal where rl_value = '$t' and rl_type = 24 and rl_lang = '$lang' ";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $line = $rlt[0];
        $id = $line['id_rl'];

        return ($id);
    }

    Function prepara_termo($t = '', $lc = '') {
        $t = utf8_decode($t);
        if (strlen($lc) > 0) {
            $t = LowerCase($t);
            $t = UpperCase(substr($t, 0, 1)) . substr($t, 1, strlen($t));
            if (substr($t, 0, 1) == '#') {
                $t = UpperCase(substr($t, 1, strlen($t)));
            }
        }
        $t = utf8_encode($t);
        return ($t);
    }

    function association_term_th($t, $lang, $th) {
        $sql = "select * from rdf_literal where rl_value = '$t' and rl_type = 24 and rl_lang = '$lang' ";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        if (count($rlt) > 0) {
            $idt = $rlt[0]['id_rl'];
            $sql = "select * from rdf_literal_th where lt_thesauros = $th and lt_term = $idt ";
            $xrlt = $this -> db -> query($sql);
            $xrlt = $xrlt -> result_array();
            if (count($xrlt) == 0) {
                $sql = "insert into rdf_literal_th (lt_thesauros, lt_term) values ('$th','$idt')";
                $yrlt = $this -> db -> query($sql);
            }
        }
    }

    function recupera_termo_id($t) {
        $t = round($t);
        $sql = "select * from rdf_literal where id_rl = $t limit 1";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        if (count($rlt) > 0) {
            $line = $rlt[0];
            return ($line['id_rl']);
        } else {
            return (0);
        }
    }

    function recupera_termo($t) {
        $sql = "select * from rdf_literal where rl_value = '$t' limit 1";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        if (count($rlt) > 0) {
            $line = $rlt[0];
            return ($line['id_rl']);
        } else {
            return (0);
        }
    }

    function is_concept($id, $th) {
        $sql = "select * from  th_concept_term where ct_th = '$th' and ct_term = " . round($id);
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        if (count($rlt) > 0) {
            $line = $rlt[0];
            return ($line['ct_concept']);
        } else {
            return (0);
        }
    }

    function cp_th_new($us = '') {
        $cp = array();
        array_push($cp, array('$H8', 'id_pa', '', false, true));
        array_push($cp, array('$S80', 'pa_name', msg('thesaurus_name'), true, true));
        array_push($cp, array('$A1', '', msg('thesaurus_description'), false, false));
        array_push($cp, array('$T80:8', 'pa_introdution', msg('thesaurus_introdution'), false, true));
        array_push($cp, array('$T80:3', 'pa_audience', msg('thesaurus_audience'), false, true));
        array_push($cp, array('$T80:6', 'pa_methodology', msg('thesaurus_methodology'), false, true));

        $op = '1:' . msg('th_type_1');
        $op .= '&2:' . msg('th_type_2');
        $op .= '&3:' . msg('th_type_3');
        $op .= '&4:' . msg('th_type_4');
        $op .= '&5:' . msg('th_type_5');
        $op .= '&6:' . msg('th_type_6');
        array_push($cp, array('$O ' . $op, 'pa_type', msg('thesaurus_type'), true, true));

        array_push($cp, array('$HV', 'pa_status', '1', true, true));
        array_push($cp, array('$HV', 'pa_classe', 1, true, true));
        array_push($cp, array('$HV', 'pa_creator', $us, true, true));

        array_push($cp, array('$B8', '', msg('save'), false, true));
        return ($cp);
    }

    function cp_th($id = '') {
        $cp = array();
        array_push($cp, array('$H8', 'id_pa', '', true, true));
        array_push($cp, array('$S80', 'pa_name', msg('thesaurus_name'), true, true));
        array_push($cp, array('$T80:5', 'pa_description', msg('thesaurus_description'), false, true));
        array_push($cp, array('$A1', '', msg('thesaurus_description'), false, false));
        array_push($cp, array('$T80:8', 'pa_introdution', msg('thesaurus_introdution'), false, true));
        array_push($cp, array('$T80:3', 'pa_audience', msg('thesaurus_audience'), false, true));
        array_push($cp, array('$T80:6', 'pa_methodology', msg('thesaurus_methodology'), false, true));

        $op = '1:' . msg('th_type_1');
        $op .= '&2:' . msg('th_type_2');
        $op .= '&3:' . msg('th_type_3');
        $op .= '&4:' . msg('th_type_4');
        $op .= '&5:' . msg('th_type_5');
        $op .= '&6:' . msg('th_type_6');
        array_push($cp, array('$O' . $op, 'pa_type', msg('thesaurus_type'), true, true));

        $ops = '1:' . msg('status_1');
        $ops .= '&2:' . msg('status_2');
        array_push($cp, array('$O ' . $ops, 'pa_status', msg('thesaurus_status'), true, true));
        array_push($cp, array('$B8', '', msg('save'), false, true));
        return ($cp);
    }

    function cp_term($id = '') {
        $cp = array();
        array_push($cp, array('$H8', 'id_rl', '', true, true));
        array_push($cp, array('$S100', 'rl_value', msg('term_name'), true, true));

        $sql = "select * from language order by lg_order";
        array_push($cp, array('$Q  lg_code:lg_language:' . $sql, 'rl_lang', msg('language'), true, true));
        array_push($cp, array('$B8', '', msg('save'), false, true));
        return ($cp);
    }

    function concept_chage($c = '', $t1 = '', $t2 = '', $th = '') {
        $sql = "select * from th_concept_term 
					INNER JOIN rdf_literal ON id_rl = ct_term
						where ct_concept = $c
							AND ct_th = $th
							AND ct_term = $t2 ";
        $rlt1 = $this -> db -> query($sql);
        $rlt1 = $rlt1 -> result_array();
        if (count($rlt1) == 1) {
            $rlt1 = $rlt1[0];
        }

        $sql = "select * from th_concept_term 
					INNER JOIN rdf_literal ON id_rl = ct_term
						where ct_concept = $c
							AND ct_th = $th
							AND ct_term = $t1 ";
        $rlt2 = $this -> db -> query($sql);
        $rlt2 = $rlt2 -> result_array();
        if (count($rlt2) == 1) {
            $rlt2 = $rlt2[0];
        }

        if ((count($rlt2) > 0) and (count($rlt1) > 0)) {
            $sql = "update th_concept_term set ct_term = $t2 where id_ct = " . $rlt2['id_ct'] . ';';
            $rlt = $this -> db -> query($sql);

            $sql = "update th_concept_term set ct_term = $t1 where id_ct = " . $rlt1['id_ct'] . ';';
            $rlt = $this -> db -> query($sql);

            $desc = msg('change') . ' <b>' . $rlt2['rl_value'] . '</b> ' . msg('has_prefTerm') . ', ' . msg('change_old') . ' (<i>' . $rlt1['rl_value'] . '</i>)';
            $this -> skoses -> log_insert($c, $th, 'CHG', $desc);
            return (1);
        }
        return (0);
    }

    function concept_create($t, $th) {
        $data = date("Y-m-d");

        /* Validação */
        if (sonumero($t) == $t) {
            $idt = $this -> skoses -> recupera_termo_id($t);
        } else {
            $idt = $this -> skoses -> recupera_termo($t);
        }

        if ($idt == 0) {
            echo 'OPS 212 - Termo not found - ' . $t;
            return ('');
            exit ;
        }

        /* Verificar se já não existe o conceito no thesaurus */
        $sql = "select * from th_concept_term where ct_term = $idt and ct_th = $th";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        if (count($rlt) == 0) {

            /* locate next concept ID */
            $sql = "select id_c from th_concept order by id_c desc limit 1 ";
            $rlt = $this -> db -> query($sql);
            $rlt = $rlt -> result_array();
            if (count($rlt) == 0) {
                $idc = 0;
            } else {
                $line = $rlt[0];
                $idc = sonumero($line['id_c']);
            }
            $co = 'c' . strzero(($idc + 1), 3);

            /* Insert Concept into the thesaurus */
            $sql = "insert into th_concept (c_th, c_concept) values ($th,'$co')";
            $rrb = $this -> db -> query($sql);

            $sql = "select * from th_concept where c_concept = '$co' ";
            $rrb = $this -> db -> query($sql);
            $rrb = $rrb -> result_array();

            $line = $rrb[0];
            $idc = $line['id_c'];
            $sql = "insert into th_concept_term 
							(ct_concept, ct_th, ct_term, ct_propriety, ct_concept_2) 
									values 
							('$idc','$th','$t','25','0') ";
            $rrb = $this -> db -> query($sql);
            return ($idc);
        }
        return (1);
    }

    function concept_show($c) {
        $sx = '<table width="100%" class="table">';
        /*** conceito 1 */
        $sql = "SELECT
						t1.ct_concept as c1, t1.ct_concept_2 as c2, 
						prefix_ref, rs_propriety, rs_propriety_inverse,
						l1.rl_value as l1  
						FROM th_concept_term AS t1
			 			LEFT JOIN rdf_literal as l1 ON t1.ct_term = l1.id_rl
			 			LEFT JOIN rdf_resource ON ct_propriety = id_rs
			 			LEFT JOIN rdf_prefix ON id_prefix = rs_prefix
						WHERE t1.ct_concept = $c 
						ORDER BY t1.ct_propriety ";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();

        for ($r = 0; $r < count($rlt); $r++) {
            $line = $rlt[$r];
            if (strlen(trim($line['l1'])) > 0) {
                $sx .= '<tr>';

                $sx .= '<td width="30%" align="right" class="small">';
                $sx .= msg(trim($line['prefix_ref']) . ':' . trim($line['rs_propriety']));
                $sx .= '</td>';

                $sx .= '<td width="70%" class="middle">';
                $sx .= '<b>' . trim($line['l1']) . '</b>';
                $sx .= '</td>';
                $sx .= '</tr>';
            }
        }

        /*** conceito 2 */
        $sql = "select 
						t1.ct_concept as c1, t1.ct_concept_2 as c2, 
						prefix_ref, rs_propriety, rs_propriety_inverse,
						rl_value as l1
		 		FROM th_concept_term AS T1
					INNER JOIN th_concept_term AS T2 ON t1.ct_concept_2 = t2.ct_concept AND (t2.ct_propriety = 25)
					INNER JOIN rdf_literal ON T2.ct_term = id_rl
					LEFT JOIN rdf_resource ON t1.ct_propriety = id_rs
					LEFT JOIN rdf_prefix ON id_prefix = rs_prefix
					where t1.ct_concept = $c ";

        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();

        for ($r = 0; $r < count($rlt); $r++) {
            $line = $rlt[$r];
            if (strlen(trim($line['l1'])) > 0) {
                $link = '<a href="#">';
                if ($line['c2'] > 0) {
                    $link = '<a href="' . base_url('index.php/thesa/c/' . $line['c2']) . '">';
                }
                $sx .= '<tr>';

                $sx .= '<td width="30%" align="right" class="small">';
                $sx .= msg(trim($line['prefix_ref']) . ':' . trim($line['rs_propriety_inverse']));
                $sx .= '</td>';

                $sx .= '<td width="70%" class="middle">';
                $sx .= '<b>' . $link . trim($line['l1']) . '</a>' . '</b>';
                $sx .= '</td>';
                $sx .= '</tr>';
            }
        }

        $sx .= '</table>';
        return ($sx);

    }

    function autho($id = '', $th) {
        if (strlen($id) == 0) {
            if (isset($_SESSION['id']))
                $id = $_SESSION['id'];
        }
        if (strlen($id) == 0) {
            return (0);
        }

        $sql = "select * from th_users 
						WHERE ust_user_id = $id
						AND ust_th = $th
						AND ust_status = 1";

        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        if (count($rlt) > 0) {
            return (1);
        } else {
            return (0);
        }
    }

    /******************* classes *************/
    function find_class($class) {
        $sql = "select * from rdf_class
                        WHERE c_class = '$class' ";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        if (count($rlt) == 0) {
            echo '<h1>Ops, ' . $class . ' não localizada';
            exit ;
        }
        $line = $rlt[0];
        return ($line['id_c']);
    }

    /*********************************************/

    function termos_show_letter($th, $ltr) {
        $co = $this -> find_class('Concept');
        $co = $this -> CO;

        if (strleN($ltr) == 0) {
            $wh = '';
        } else {
            $wh = " D1.rl_value like '$ltr%' and ";
        }

        /* IS AUTH TO EDIT */
        $auth = 0;
        if (isset($_SESSION['user_id']) > 0) {
            $auth = $this -> autho($id);
        }
        if ($auth == 1) {
            $type = 24;
            echo '<br><br><br><br>RULE 1';
            $sql = "select * from rdf_literal
						INNER JOIN rdf_literal_th as D1 ON lt_term = id_rl 
						LEFT JOIN th_concept_term ON ct_term = id_rl and ct_th = $id
						WHERE lt_thesauros = $th and rl_type = $type 
							AND $wh lt_thesauros = $th  
							ORDER BY rl_value
						";
        } else {
            $sql = "SELECT id_rl, ct_concept, ct_propriety, rl_value, rl_lang, 
								rs_propriety , rs_public , '' as altTerm
							FROM th_concept_term 
							INNER JOIN rdf_literal AS D1 ON ct_term = id_rl
							INNER JOIN rdf_resource ON ct_propriety = id_rs
							WHERE 
								$wh
								ct_th = $th and rs_public = 1 and ct_propriety = " . $co . "
						";
            $sql .= " UNION ";
            $sql .= "SELECT D1.id_rl, T1.ct_concept, T1.ct_propriety, D1.rl_value, D1.rl_lang, 
								rs_propriety , rs_public , D2.rl_value as altTerm
							FROM th_concept_term as T1
							INNER JOIN th_concept_term as T2 ON (T1.ct_concept = T2.ct_concept) and (T2.ct_propriety = 25)
						    INNER JOIN rdf_resource ON T1.ct_propriety = id_rs						    
						    INNER JOIN rdf_literal as D1 ON T1.ct_term = D1.id_rl
						    INNER JOIN rdf_literal as D2 ON T2.ct_term = D2.id_rl
						    
						WHERE 
								$wh
								T1.ct_th = $th and 
								T2.ct_th = $th and 
								rs_public = 1 and 
								T1.ct_propriety <> 25 
								
						";
            $sql .= " ORDER BY rl_value";

        }
        $xrlt = $this -> db -> query($sql);
        $xrlt = $xrlt -> result_array();
        $cols = 1;
        $terms = count($xrlt);
        if ($terms > 2) { $cols = 2;
        }

        $sx = '<div class="row" style="-moz-column-count: ' . $cols . '; -webkit-column-count: ' . $cols . ';">' . cr();
        $sx .= '<ul class="thesa">';
        $lt = '';
        for ($r = 0; $r < count($xrlt); $r++) {
            $line = $xrlt[$r];
            $lta = substr(utf8_decode(UpperCaseSql($line['rl_value'])), 0, 1);
            if ($lta != $lt) {
                if (strlen($lt) != '') {
                    $sx .= '<li>&nbsp;</li>' . cr();
                }
                $sx .= '<li class="big">' . $lta . '</li>' . chr(13);
                $lt = $lta;
            }
            $sa = '';
            $saf = '';
            $link = '<a href="' . base_url('index.php/thesa/term/' . $th . '/' . $line['id_rl']) . '" class="term_word">';
            if (round($line['ct_propriety']) == $co) {
                $sa = '<img src="' . base_url('img/icone/tag.png') . '" height="24" border=0>'; ;
                $link = '<a href="' . base_url('index.php/thesa/c/' . $line['ct_concept']) . '/' . $th . '/" class="term">';
            } else {
                if (strlen(trim($line['altTerm'])) > 0) {
                    $link = '<a href="' . base_url('index.php/thesa/c/' . $line['ct_concept']) . '/' . $th . '/" class="term">';
                    $sa = ' ';
                    $saf = ' </a><span style="color: #808080"><i>' . msg('use') . '</i></span> ' . $link . $line['altTerm'];
                }
            }
            $sx .= '<li>' . $link . $sa . ' ' . $line['rl_value'];
            $sx .= $saf;
            $sx .= '</a></li>';
        }
        $sx .= '</ul>' . cr();
        $sx .= '</div>';
        return ($sx);
    }

    function termos_show_rdf($th, $ltr) {
        $sql = "SELECT * FROM thesa limit 1";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $ln = $rlt[0];

        $sql = "select * from th_concept_term 
                        INNER JOIN rdf_literal ON ct_term = id_rl
                        INNER JOIN rdf_resource ON ct_propriety = id_rs
                        INNER JOIN rdf_prefix ON rs_prefix = id_prefix
                        where ct_th = $th and ct_term > 0
                        order by ct_concept, ct_propriety";
        //echo $sql;
        //exit;
        $xrlt = $this -> db -> query($sql);
        $xrlt = $xrlt -> result_array();
        $terms = count($xrlt);
        $sx = '';
        for ($r = 0; $r < count($xrlt); $r++) {
            $line = $xrlt[$r];
            $sx .= $ln['thesa_prefix'] . ':c' . $line['ct_concept'];
            $sx .= chr(9);
            $sx .= $line['prefix_ref'] . ':' . $line['rs_propriety'];
            $sx .= chr(9);
            $sx .= $line['rl_value'];
            $sx .= cr();
        }
        return ($sx);
    }

    /* ASSIGNS */
    function assign_as_narrower($c1, $c2, $th, $tm) {
        /* Verifica */
        $tg = $this -> TG;

        $sql = "select * from th_concept_term 
						WHERE (ct_concept_2 = $c2 and ct_propriety = " . $this -> TG . ")";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        if (count($rlt) == 0) {
            $sql = "insert into th_concept_term
							(
								ct_concept, ct_th, ct_term, 
								ct_concept_2, ct_propriety
							) values (
								$c1, $th, $tm,
								$c2,'.$this->TG.'
							)";
        }
        $rlt = $this -> db -> query($sql);
    }

    function assign_as_note($c, $th, $type, $texto, $language) {
        $sql = "insert into rdf_literal_note
					(
						rl_type, rl_value, rl_lang, rl_c
					) values (
						$type, '$texto', '$language', $c
					)";
        $rlt = $this -> db -> query($sql);
    }

    function assign_as_propriety($c, $th, $tr, $desc) {
        /* Verifica */
        $c2 = 0;
        $sql = "select * from th_concept_term 
						WHERE (ct_term = $desc and ct_th = $th)";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        if (count($rlt) == 0) {
            $sql = "insert into th_concept_term
							(
								ct_concept, ct_th, ct_term, 
								ct_concept_2, ct_propriety
							) values (
								$c, $th, $desc,
								$c2,'.$tr.'
							)";
        }	$rlt = $this -> db -> query($sql);
    }

    function assign_as_relation($c1, $c2, $th, $tr) {
        /* Verifica */
        $tm = 0;
        $sql = "select * from th_concept_term 
						WHERE (ct_concept_2 = $c2 and ct_propriety = $tr and ct_concept = $c1)";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        if (count($rlt) == 0) {
            $sql = "insert into th_concept_term
							(
								ct_concept, ct_th, ct_term, 
								ct_concept_2, ct_propriety
							) values (
								$c1, $th, $tm,
								$c2,'.$tr.'
							)";
        } else {

        }

        $rlt = $this -> db -> query($sql);
    }

    function export_format($th) {
        $sx = '';
        $sx .= '<a href="' . base_url('index.php/thesa/terms_from_to/' . $th . '/xml') . '" style="margin-right: 10px;">.xml</a> ';
        $sx .= '<a href="' . base_url('index.php/thesa/terms_from_to/' . $th . '/csv') . '" style="margin-right: 10px;">.csv</a> ';
        $sx .= '<a href="' . base_url('index.php/thesa/terms_from_to/' . $th . '/txt') . '" style="margin-right: 10px;">.txt</a> ';
        $sx .= '<a href="' . base_url('index.php/thesa/terms_from_to/' . $th . '/rdf') . '" style="margin-right: 10px;">.rdf</a> ';
        $sx .= '<a href="' . base_url('index.php/thesa/terms_from_to/' . $th . '/json') . '" style="margin-right: 10px;">.json</a> ';
        $sx .= '<a href="' . base_url('index.php/thesa/terms_from_to/' . $th . '/pdf') . '" style="margin-right: 10px;">.pdf</a> ';
        return ($sx);
    }

    function from_to($th = 0, $separador = '=>', $capc = '') {
        $sql = "select ct_concept, ct_propriety, rl_value, length(rl_value) as sz  from th_concept_term 
						INNER JOIN rdf_literal ON ct_term = id_rl
						where ct_th = $th and ct_term > 0
						order by ct_concept, ct_propriety, sz desc";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $mst = '';
        $sx = '';
        for ($r = 0; $r < count($rlt); $r++) {
            $line = $rlt[$r];
            $idcx = $line['ct_concept'];
            $prop = $line['ct_propriety'];
            $valo = trim($line['rl_value']);
            if ($prop == 25) {
                $mst = trim($line['rl_value']);
                $sx .= $capc . UpperCase($line['rl_value']) . $capc . $separador . $capc . $line['rl_value'] . $capc . cr();
            }
            if ($valo != $mst) {
                $sx .= $capc . $line['rl_value'] . $capc . $separador . $capc . $mst . $capc . cr();
            }
        }
        return ($sx);
    }

    function image_concept($c, $img) {
        $sql = "select * from rdf_image_concept where ic_concept = " . round($c);
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();

        if (count($rlt) == 0) {
            $sql = "insert into rdf_image_concept
                                (ic_concept, ic_url, ic_status)
                                values
                                ($c,'$img',1)";
        } else {
            $line = $rlt[0];
            $id = $line['id_ic'];
            $sql = "update rdf_image_concept set
                                ic_url = '$img'
                                where id_ic = $id ";

        }
        $this -> db -> query($sql);
    }

    function indice_html($th, $lk) {
        $sql = "select r1.rl_value as rl1, r2.rl_value as rl2,note0.rs_propriety as prop,
						t1.ct_concept as c, note.rl_value as note, 
						note2.rs_propriety as prop_note, pre2.prefix_ref as prop_pre,
						UPPER(substr(r1.rl_value,1,1)) as ltr
					FROM th_concept_term as t1						
						INNER JOIN rdf_literal AS r1 ON id_rl = t1.ct_term 
						INNER JOIN rdf_resource as note0 ON id_rs = t1.ct_propriety
						LEFT JOIN th_concept_term as t2 on t1.ct_concept = t2.ct_concept and t2.ct_propriety = 25
						LEFT JOIN rdf_literal AS r2 ON r2.id_rl = t2.ct_term 
						LEFT JOIN rdf_literal_note as note ON rl_c = t1.ct_concept
						LEFT JOIN rdf_resource as note2 ON note.rl_type = note2.id_rs
						LEFT JOIN rdf_prefix as pre2 ON note2.rs_prefix = pre2.id_prefix
						WHERE t1.ct_th = $th and 
							(note0.rs_group = 'LABEL' or note0.rs_group = 'TE' or note0.rs_group = 'FE')  
						ORDER BY r1.rl_value";

        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $sx = '<div style="line-height: 160%">';
        $xltr = '';
        for ($r = 0; $r < count($rlt); $r++) {
            $line = $rlt[$r];

            $ltr = $line['ltr'];
            if (($xltr != $ltr) and (strlen(trim($line['rl1'])) >= 3)) {
                $sx .= '<br><br>';
                $sx .= '<font style="font-size: 150%;"><b>~~' . trim($ltr) . '~~</b></font>';
                $sx .= '<br>';
                $xltr = $ltr;
            }

            $sx .= $line['rl1'];
            if ($line['rl1'] != $line['rl2']) {
                $sx .= '<span>';
                $sx .= '<i> ' . msg('use') . ' </i> ';
                $sx .= '</span>';
                $sx .= $line['rl2'];
            }
            $sx .= '<br>';
        }
        $sx .= '</div>';
        return ($sx);
    }

    function glossario_html($th, $lk) {
        $sql = "select r1.rl_value as rl1, r2.rl_value as rl2,note0.rs_propriety as prop,
                        t1.ct_concept as c, note.rl_value as note, 
                        note2.rs_propriety as prop_note, pre2.prefix_ref as prop_pre,
                        UPPER(substr(r1.rl_value,1,1)) as ltr
                    FROM th_concept_term as t1                      
                        INNER JOIN rdf_literal AS r1 ON id_rl = t1.ct_term 
                        INNER JOIN rdf_resource as note0 ON id_rs = t1.ct_propriety
                        LEFT JOIN th_concept_term as t2 on t1.ct_concept = t2.ct_concept and t2.ct_propriety = 25
                        LEFT JOIN rdf_literal AS r2 ON r2.id_rl = t2.ct_term 
                        LEFT JOIN rdf_literal_note as note ON rl_c = t1.ct_concept
                        LEFT JOIN rdf_resource as note2 ON note.rl_type = note2.id_rs
                        LEFT JOIN rdf_prefix as pre2 ON note2.rs_prefix = pre2.id_prefix
                        WHERE t1.ct_th = $th and 
                            (note0.rs_group = 'LABEL' or note0.rs_group = 'TE' or note0.rs_group = 'FE')  
                        ORDER BY r1.rl_value";

        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $sx = '<div style="line-height: 110%">';
        $xltr = '';
        $term = '';
        for ($r = 0; $r < count($rlt); $r++) {
            $line = $rlt[$r];
            $ltr = UpperCaseSql($line['ltr']);
            if (($xltr != $ltr) and (strlen(trim($line['rl1'])) >= 3)) {
                $sx .= '<br><br>';
                $sx .= '<font style="font-size: 200%;"><b>~~' . trim($ltr) . '~~</b></font>';
                $sx .= '<br>';
                $xltr = $ltr;
            }

            $xterm = $line['rl1'];
            $rem = 0;
            if ($xterm != $term) {
                if ($line['rl1'] != $line['rl2']) {
                    $sx .= '<br>' . $line['rl1'];
                    $sx .= '<span>';
                    $sx .= '<i> ' . msg('ver') . '</i> ';
                    $sx .= '</span>';
                    $sx .= '<b>' . $line['rl2'] . '</b> ';
                    $sx .= '<br>';
                    $rem = 1;
                } else {
                    $sx .= '<br>' . $line['rl1'];
                }
            }
            $nota = '';
            if ($rem == 0) {
                if (strlen($line['prop_note']) > 0) {
                    switch ($line['prop_note']) {
                        case 'definition' :
                            $cpat = msg('definição');
                            break;
                        case 'scopeNote' :
                            $cpat = msg('scopeNote');
                            break;
                        default :
                            $cpat = 'nota';
                            break;
                    }
                    $nota = trim($line['note']);
                    $nota = troca($nota, chr(13), ' ');
                    $nota = troca($nota, chr(10), '');
                    $nota = troca($nota, '<', '&gt;');
                    $nota = troca($nota, '>', '&lt;');
                    if (strlen($nota) > 0) {
                        $nota = $nota . cr() . cr();
                        $sx .= '<div class="note small" style="margin-left: 40px;"><i>' . $cpat . '</i>: ' . $nota . '</div>';
                    }
                }
            }
            $term = $xterm;
        }
        $sx .= '</div>';
        return ('' . $sx . '');
    }

    function glossario_alfabetico_html($th, $lk) {

        $sql = "select 
                    r1.rl_value as rl_value, 
                    r2.rl_value as r2_value,
                    rs_propriety, 
                    T1.ct_th as ct_th, 
                    T1.ct_concept as ct_concept,
                    rs_group
                    
                    FROM th_concept_term as T1
                    INNER JOIN rdf_literal AS r1 ON r1.id_rl = T1.ct_term
                    INNER JOIN rdf_resource ON T1.ct_propriety = id_rs
                    
                    INNER JOIN th_concept_term as UP ON T1.ct_concept = UP.ct_concept and UP.ct_propriety = 25
                    INNER JOIN rdf_literal AS r2 ON r2.id_rl = UP.ct_term
                    
                    WHERE T1.ct_th = $th
                    ORDER BY r1.rl_value ";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $sx = '';
        for ($r = 0; $r < count($rlt); $r++) {
            $line = $rlt[$r];
            //$sx .= '<br>';
            $sx .= '<br>';
            $sx .= '<b>' . $line['rl_value'] . '</b>';
            //$sx .= '(' . $line['rs_propriety'] . '-' . $line['rs_group'] . ')';

            $idx = $line['ct_concept'];
            $id_th = $line['ct_th'];

            switch ($line['rs_group']) {
                case 'FE' :
                    $sx .= '<br>';
                    $sx .= '&nbsp;&nbsp;&nbsp;&nbsp;USE ' . $line['r2_value'];
                    break;
                case 'TH' :
                    $sx .= '<br>';
                    $sx .= '&nbsp;&nbsp;&nbsp;&nbsp;USE ' . $line['r2_value'];
                    break;
                case 'LABEL' :
                    $d = $this -> skoses -> le_c($idx, $id_th);
                    $bt = $d['terms_bt'];
                    $nw = $d['terms_nw'];
                    $tr = $d['terms_tr'];
                    $al = $d['terms_al'];
                    /******************** AL **********/
                    if (count($al) > 0) {
                        for ($z = 0; $z < count($al); $z++) {
                            $sx .= '<br>';
                            if ($z == 0) {
                                $sx .= '&nbsp;&nbsp;&nbsp;&nbsp;UP ' . $al[$z]['rl_value'];
                            } else {
                                $sx .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ' . $al[$z]['rl_value'];
                            }

                        }
                    }
                    /******************** BT **********/
                    if (count($bt) > 0) {
                        for ($z = 0; $z < count($bt); $z++) {
                            $sx .= '<br>';
                            if ($z == 0) {
                                $sx .= '&nbsp;&nbsp;&nbsp;&nbsp;TG ' . $bt[$z]['rl_value'];
                            } else {
                                $sx .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ' . $bt[$z]['rl_value'];
                            }

                        }
                    }
                    /******************** NW **********/
                    if (count($nw) > 0) {
                        for ($z = 0; $z < count($nw); $z++) {
                            $sx .= '<br>';
                            if ($z == 0) {
                                $sx .= '&nbsp;&nbsp;&nbsp;&nbsp;TE ' . $nw[$z]['rl_value'];
                            } else {
                                $sx .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ' . $nw[$z]['rl_value'];
                            }
                        }
                    }
                    /******************** TR **********/
                    if (count($tr) > 0) {
                        for ($z = 0; $z < count($tr); $z++) {
                            $sx .= '<br>';
                            if ($z == 0) {
                                $sx .= '&nbsp;&nbsp;&nbsp;&nbsp;TR ' . $tr[$z]['rl_value'];
                            } else {
                                $sx .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ' . $tr[$z]['rl_value'];
                            }
                        }
                    }
                    break;
                default :
                    if ((isset($d['rl_value'])) and ($d['rl_value'] != $line['rl_value'])) {
                        $sx .= '<br>x';
                        $sx .= '&nbsp;&nbsp;&nbsp;&nbsp;USE ' . $d['rl_value'];

                    }
            }

        }
        return ('<pre>' . $sx . '</pre>');
    }

    function ficha_terminologica_html($th, $lk) {

        $sql = "select 
                    r1.rl_value as rl_value, 
                    r2.rl_value as r2_value,
                    rs_propriety, 
                    T1.ct_th as ct_th, 
                    T1.ct_concept as ct_concept,
                    rs_group,
                    T1.ct_created as ct_created
                    
                    FROM th_concept_term as T1
                    INNER JOIN rdf_literal AS r1 ON r1.id_rl = T1.ct_term
                    INNER JOIN rdf_resource ON T1.ct_propriety = id_rs
                    
                    INNER JOIN th_concept_term as UP ON T1.ct_concept = UP.ct_concept and UP.ct_propriety = 25
                    INNER JOIN rdf_literal AS r2 ON r2.id_rl = UP.ct_term
                    
                    WHERE T1.ct_th = $th AND rs_propriety = 'prefLabel'
                    ORDER BY r1.rl_value 
                    ";

        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $sx = '';
        for ($r = 0; $r < count($rlt); $r++) {
            $line = $rlt[$r];

            $idx = $line['ct_concept'];
            $id_th = $line['ct_th'];
            $dt = sonumero(substr($line['ct_created'], 0, 10));
            $dta = $dt;

            $d = $this -> skoses -> le_c($idx, $id_th);
            $sx .= '<div style="height: 10px; width: 100%;">';
            $sx .= '<table border=0 cellspacing="0" cellpadding="5" width="100%" class="prt">';
            $sx .= '<tr><th width="2%"></th><th width="90%"></th><th width="10%"></th></tr>' . cr();

            /* NAME */
            $sx .= '<tr valign="top">';
            $sx .= '<td rowspan="1" colspan=2><font class="ttitle">';
            $sx .= '<b>' . $line['rl_value'] . '</b>';
            //$sx .= '(' . $line['rs_propriety'] . '-' . $line['rs_group'] . ')';
            $sx .= '</font></td>';
            $sx .= '<td rowspan="10" align="center" valign="top">';
            if (trim($d['images'][0]) != '_acervo/thumb/0000000_287px.jpg') {
                $sx .= '<img src="' . base_url($d['images'][0]) . '" width="100">';
            }
            $sx .= '</td>';
            $sx .= '</tr>';

            for ($z = 0; $z < count($d['notes']); $z++) {
                $note = $d['notes'][$z];
                $dt1 = sonumero(substr($note['rl_created'], 0, 10));
                if ($dt1 > $dta) { $dta = $dt1;
                }

                $sx .= '<tr>';
                $sx .= '<td width="20">&nbsp;</td>';
                $sx .= '<td class="prt small">';
                $sx .= '<b>' . msg($note['prefix_ref'] . ':' . $note['rs_propriety']) . '</b>: ';
                $sx .= htmlentities(mst($note['rl_value']));
                $sx .= '</td>';
                $sx .= '</tr>';
            }

            /*********************************************** termos relacionados ************/
            if ((count($d['terms_bt']) + count($d['terms_nw']) + count($d['terms_tr']) + count($d['terms_al'])) > 0) {

                $sx .= '<tr>';
                $sx .= '<td>&nbsp;</td>';
                $sx .= '<td style="text-align: left; line-height: 120%;" class="small">';
                $sx .= '<i>' . msg('term_relations') . '</i>';
                $sx .= '<br/>';
                $sx .= '';

                switch ($line['rs_group']) {
                    case 'FE' :
                        $sx .= '<br><pre>';
                        $sx .= '&nbsp;&nbsp;&nbsp;&nbsp;USE ' . $line['r2_value'];
                        $sx .= '</pre>';
                        break;
                    case 'TH' :
                        $sx .= '<br><pre>';
                        $sx .= '&nbsp;&nbsp;&nbsp;&nbsp;USE ' . $line['r2_value'];
                        $sx .= '</pre>';
                        break;
                    case 'LABEL' :
                        $d = $this -> skoses -> le_c($idx, $id_th);
                        $bt = $d['terms_bt'];
                        $nw = $d['terms_nw'];
                        $tr = $d['terms_tr'];
                        $al = $d['terms_al'];
                        $sx .= '<pre>';
                        /******************** AL **********/
                        if (count($al) > 0) {
                            for ($z = 0; $z < count($al); $z++) {
                                $dt1 = sonumero(substr($al[$z]['ct_created'], 0, 10));
                                if ($dt1 > $dta) { $dta = $dt1;
                                }
                                $sx .= '<br>';
                                if ($z == 0) {
                                    $sx .= '&nbsp;&nbsp;&nbsp;&nbsp;UP ' . $al[$z]['rl_value'];
                                } else {
                                    $sx .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ' . $al[$z]['rl_value'];
                                }

                            }
                        }
                        /******************** BT **********/
                        if (count($bt) > 0) {
                            for ($z = 0; $z < count($bt); $z++) {
                                $dt1 = sonumero(substr($bt[$z]['ct_created'], 0, 10));
                                if ($dt1 > $dta) { $dta = $dt1;
                                }
                                $sx .= '<br>';
                                if ($z == 0) {
                                    $sx .= '&nbsp;&nbsp;&nbsp;&nbsp;TG ' . $bt[$z]['rl_value'];
                                } else {
                                    $sx .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ' . $bt[$z]['rl_value'];
                                }

                            }
                        }
                        /******************** NW **********/
                        if (count($nw) > 0) {
                            for ($z = 0; $z < count($nw); $z++) {
                                $dt1 = sonumero(substr($nw[$z]['ct_created'], 0, 10));
                                if ($dt1 > $dta) { $dta = $dt1;
                                }
                                $sx .= '<br>';
                                if ($z == 0) {
                                    $sx .= '&nbsp;&nbsp;&nbsp;&nbsp;TE ' . $nw[$z]['rl_value'];
                                } else {
                                    $sx .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ' . $nw[$z]['rl_value'];
                                }
                            }
                        }
                        /******************** TR **********/
                        if (count($tr) > 0) {
                            for ($z = 0; $z < count($tr); $z++) {
                                $dt1 = sonumero(substr($tr[$z]['ct_created'], 0, 10));
                                if ($dt1 > $dta) { $dta = $dt1;
                                }
                                $sx .= '<br>';
                                if ($z == 0) {
                                    $sx .= '&nbsp;&nbsp;&nbsp;&nbsp;TR ' . $tr[$z]['rl_value'];
                                } else {
                                    $sx .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ' . $tr[$z]['rl_value'];
                                }
                            }
                        }
                        $sx .= '</pre>';
                        break;
                    default :
                        if ((isset($d['rl_value'])) and ($d['rl_value'] != $line['rl_value'])) {
                            $sx .= '<br>x';
                            $sx .= '&nbsp;&nbsp;&nbsp;&nbsp;USE ' . $d['rl_value'];

                        }
                }
                $sx .= '';
                $sx .= '</td>';
                $sx .= '</tr>';
            }

            $sx .= '<tr>';
            $sx .= '<td>&nbsp;</td>';
            $sx .= '<td class="small" colspan=2>';
            $sx .= '';
            $sx .= msg('created_in') . ': ' . stodbr($dt) . '';
            if ($dt != $dta) {
                $sx .= ' &nbsp; &nbsp; &nbsp; &nbsp; ';
                $sx .= msg('update_in') . ': ' . stodbr($dta) . '';
            }
            $sx .= '';
            $sx .= '</td>';
            $sx .= '</tr>';

            $sx .= '</table>';
            $sx .= '<br/>';
        }
        //echo $sx;
        //exit ;
        return ($sx);
    }

    function glossario($th = '', $lk = '') {
        $sql = "select r1.rl_value as rl1, r2.rl_value as rl2,note0.rs_propriety as prop,
						t1.ct_concept as c, note.rl_value as note, 
						note2.rs_propriety as prop_note, pre2.prefix_ref as prop_pre
					FROM th_concept_term as t1						
						INNER JOIN rdf_literal AS r1 ON id_rl = t1.ct_term 
						INNER JOIN rdf_resource as note0 ON id_rs = t1.ct_propriety
						LEFT JOIN th_concept_term as t2 on t1.ct_concept = t2.ct_concept and t2.ct_propriety = 25
						LEFT JOIN rdf_literal AS r2 ON r2.id_rl = t2.ct_term 
						LEFT JOIN rdf_literal_note as note ON rl_c = t1.ct_concept
						LEFT JOIN rdf_resource as note2 ON note.rl_type = note2.id_rs
						LEFT JOIN rdf_prefix as pre2 ON note2.rs_prefix = pre2.id_prefix
						WHERE t1.ct_th = $th and 
							(note0.rs_group = 'LABEL' or note0.rs_group = 'TE' or note0.rs_group = 'FE')  
						ORDER BY r1.rl_value";

        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();

        $sx = '<ul style="list-style-type: none;">' . cr();
        $ltr = '';
        $xc = '';
        $ix = 0;
        for ($r = 0; $r < count($rlt); $r++) {
            $line = $rlt[$r];
            $xltr = substr($line['rl1'], 0, 1);
            $c = $line['c'];
            if ($xltr != $ltr) {
                if ($ix > 0) { $sx .= '</li>' . cr();
                    $ix = 0;
                }
                $ltr = $xltr;
                $sx .= '<span class="big">~' . substr($ltr, 0, 1) . '~</span>' . cr();
            }
            if ($lk != '') {
                $link = '';
                $linka = '';
            } else {
                $link = '<a href="' . base_url('index.php/thesa/c/' . $line['c']) . '">';
                $linka = '</a>';
            }

            if ($c != $xc) {
                if ($ix > 0) { $sx .= '</li>' . cr();
                    $ix = 0;
                }
                $sx .= '<li>';
                $sx .= $link . $line['rl1'] . $linka;
                if (strlen($line['note']) > 0) {
                    $sx .= '<p>' . '<b>' . msg($line['prop_pre'] . ':' . $line['prop_note']) . '</b>: ' . $line['note'] . '</p>' . cr();
                }
                // $sx .= '<i>'.$line['prop'].'</i>';
                if ($line['rl1'] != $line['rl2']) {
                    $sx .= '<br>';
                    $sx .= '<span style="margin-left: 45px;">';
                    $sx .= '<i>' . msg('ver') . '</i> ';
                    $sx .= '</span>';
                    $sx .= $line['rl2'];
                }
                $xc = $c;
                $ix = 1;
            } else {
                if (strlen($line['note']) > 0) {
                    $sx .= '<p>' . '<b>' . msg($line['prop_pre'] . ':' . $line['prop_note']) . '</b>: ' . $line['note'] . '</p>';
                }
            }

        }
        if ($ix > 0) { $sx .= '</li>' . cr();
            $ix = 0;
        }
        $sx .= '</ul>';
        return ($sx);
    }

    function acao_visualizar_glossario($th = '') {
        $sx = '';
        $sx .= '<br/>';
        $sx .= '<br/>';
        $sx .= '<a href="' . base_url('index.php/thesa/glossario/' . $th) . '" class="btn btn-secondary" style="width: 100%;">' . msg('glossario') . '</a>';
        $sx .= '<br/>';
        $sx .= '<br/>';
        $sx .= '<a href="' . base_url('index.php/thesa/thes/' . $th) . '" class="btn btn-secondary" style="width: 100%;">' . msg('Conceitual map') . '</a>';
        $sx .= '<br/>';
        $sx .= '<br/>';
        $sx .= '<a href="' . base_url('index.php/thesa/thrs/' . $th) . '" class="btn btn-secondary" style="width: 100%;">' . msg('Report Thesaurus') . '</a>';

        return ($sx);
    }

    function termos_sem_conceito($th = '', $ltr = '') {
        $th = round($th);
        $sql = "select * from rdf_literal_th
						INNER JOIN rdf_literal ON id_rl = lt_term 
						LEFT JOIN th_concept_term on ct_term = id_rl AND ct_th = $th
						WHERE lt_thesauros = $th and ct_th is null
						ORDER BY rl_value";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $sx = '<ul>' . cr();
        for ($r = 0; $r < count($rlt); $r++) {
            $line = $rlt[$r];
            $link = '<a href="' . base_url('index.php/thesa/term/' . $th . '/' . $line['lt_term']) . '">';
            $sx .= '<li>';
            $sx .= $link;
            $sx .= $line['rl_value'];
            $sx .= '</a>';
            $sx .= '</li>' . cr();
        }
        $sx .= '</ul>' . cr();
        return ($sx);
    }

    function association_term_th2($t, $lang, $th) {
        $sql = "select * from rdf_literal where rl_value = '$t' and rl_type = 24 and rl_lang = '$lang' ";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        if (count($rlt) > 0) {
            $idt = $rlt[0]['id_rl'];
            $sql = "select * from rdf_literal_th where lt_thesauros = $th and lt_term = $idt ";
            $xrlt = $this -> db -> query($sql);
            $xrlt = $xrlt -> result_array();
            if (count($xrlt) == 0) {
                $sql = "insert into rdf_literal_th (lt_thesauros, lt_term) values ('$th','$idt')";
                $yrlt = $this -> db -> query($sql);
            }
        }
    }

    function xml($data) {

        //create a new document
        // 1st param takes version and 2nd param takes encoding;
        $dom = new DomDocument("1.0", "UTF-8");

        // it can also be set later, like below, if you decide not to declare at first line:
        $dom -> version = "1.0";
        $dom -> encoding = "UTF-8";

        $dom -> createElement('NODE_NAME', 'NODE_VALUE');
        // we create a XML Node and store it in a variable called noteElem;
        $noteElem = $dom -> createElement('rdf');

        // createElement takes 2 param also, with 1st param takes the node Name, and 2nd param is node Value
        $toElem = $dom -> createElement('concept', $data['ct_concept']);
        $toElem -> setAttribute('created', $data['c_created']);
        //$toElem->setAttribute('value', $data['rl_value']);
        //$toElem->setAttribute('thesaurus', $data['pa_name']);

        $toElem2 = $dom -> createElement('url', $this -> skoses -> url . 'index.php/c/');
        $toElem3 = $dom -> createElement('prefix', $this -> skoses -> prefix);

        $toElem4 = $dom -> createElement('literal', $data['rl_value']);
        $toElem4 -> setAttribute('language', $data['rl_lang']);

        $toElem5 = $dom -> createElement('thesauros', $data['pa_name']);
        $toElem6 = $dom -> createElement('thesauros_id', $data['id_pa']);
        $toElem7 = $dom -> createElement('uri', $this -> skoses -> prefix . ':' . $data['ct_concept']);
        $toElem8 = $dom -> createElement('class', 'skos');
        $toElem9 = $dom -> createElement('subclass', $data['pa_class']);

        // now, we add $toElem as a child of $noteElem
        $toSKOS = $dom -> createElement('skos', '');
        $toSKOS -> setAttribute('uri', $this -> skoses -> prefix . ':' . $data['ct_concept']);
        $toSKOS -> setAttribute('value', $this -> skoses -> prefix . ':' . $data['rl_value']);
        $toSKOS -> setAttribute('language', $this -> skoses -> prefix . ':' . $data['rl_lang']);

        $toSKOS -> appendChild($toElem7);
        $toSKOS -> appendChild($toElem);
        $toSKOS -> appendChild($toElem2);
        $toSKOS -> appendChild($toElem3);
        $toSKOS -> appendChild($toElem4);
        $toSKOS -> appendChild($toElem5);
        $toSKOS -> appendChild($toElem6);
        $toSKOS -> appendChild($toElem8);
        $toSKOS -> appendChild($toElem9);

        $noteElem -> appendChild($toSKOS);

        // add $noteElem to the main dom
        $dom -> appendChild($noteElem);
        $dom -> createComment('Thesa');

        echo $dom -> saveXML();
        exit ;
        return ($sx);
    }

    function rdf($data) {

        //create a new document
        // 1st param takes version and 2nd param takes encoding;
        print_r($data);
        return ($sx);
    }

    function th_collabotors_add($email, $th) {
        $ok = $this -> le_user_email($email);
        if ($ok == 1) {
            $id_us = $this -> line['id_us'];
            $ok = $this -> insert_collaborators_add($id_us, $th);
            if ($ok == 1) {
                $msg = '<br/><br/><span class="btn alert-success">' . msg('collaborator_insered') . '</span>';
                $this -> skoses -> user_thesa($th, $id_us, 'INS');
                echo $msg;
                return (1);
            } else {
                $msg = '<br/><br/><span class="btn alert-success">' . msg('collaborator_updated') . '</span>';
                $this -> skoses -> user_thesa($th, $id_us, 'UPD');
                echo $msg;
                return (1);
            }
        } else {
            echo '
					<br>
					<div class="alert alert-warning">
					  <strong>Warning!</strong> 
					  ';
            echo 'ERROR #600 - ' . msg('ERRO_600') . ' - ' . $email;
            echo '
					</div>
					';
        }

    }

    function welcome_resumo() {
        $sql = "select count(*) as total from users";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $line = $rlt[0];
        $data['nr_users'] = $line['total'];

        $sql = "select count(*) as total from th_thesaurus";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $line = $rlt[0];
        $data['nr_thesaurus'] = $line['total'];

        $sql = "select count(*) as total from th_concept";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $line = $rlt[0];
        $data['nr_terms'] = $line['total'];

        $sql = "select count(*) as total from rdf_literal";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $line = $rlt[0];
        $data['nr_concept'] = $line['total'];

        return ($data);
    }

    function insert_collaborators_add($id, $th) {
        $sql = "select * from th_users where ust_user_id = $id and ust_th = $th";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        if (count($rlt) == 0) {
            $sql = "insert into th_users
							(ust_user_id, ust_user_role, ust_th, ust_status)
							values
							($id,1,$th,1)";
            $rlt = $this -> db -> query($sql);
            $ok = 1;
        } else {
            $id_ust = $rlt[0]['id_ust'];

            $sql = "update th_users set ust_status = 1 where id_ust = $id_ust";
            $rlt = $this -> db -> query($sql);
            $ok = 2;
        }
        return ($ok);
    }

    function th_users() {
        $th = $_SESSION['skos'];
        $id_user = $_SESSION['id'];
        $user_nivel = 0;
        $thesa = $this -> le_th($th);

        $sql = "select * from th_users
						INNER JOIN users ON ust_user_id = id_us
						WHERE ust_status = 1
							AND ust_th = $th ";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $sx = '<table class="table" width="100%">' . cr();
        $sx .= '<tr class="small">';
        $sx .= '<th width="2%">' . msg('#') . '</th>';
        $sx .= '<th width="40%">' . msg('name') . '</th>';
        $sx .= '<th width="40%">' . msg('email') . '</th>';
        $sx .= '<th width="18%">' . msg('designated') . '</th>';
        $sx .= '</tr>' . cr();

        for ($r = 0; $r < count($rlt); $r++) {
            $line = $rlt[$r];
            $sx .= '<tr>';

            $sx .= '<td>';
            $sx .= ($r + 1);
            $sx .= '</td>';

            $sx .= '<td>';
            $sx .= $line['us_nome'];
            $sx .= '</td>';

            $sx .= '<td>';
            $sx .= $line['us_email'];
            $sx .= '</td>';

            $sx .= '<td>';
            $sx .= stodbr($line['ust_created']);
            $sx .= '</td>';
            if ($thesa['pa_creator'] != $line['id_us']) {
                $link = '<a href="#">';
                $sx .= '<td>';
                $sx .= $link;
                $sx .= '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>';
                $sx .= '</a>';
                $sx .= '</td>';
            }

            $sx .= '</tr>' . cr();
        }
        $sx .= '</table>' . cr();

        if ($thesa['pa_creator'] == $id_user) {
            $sx .= '					
							<!-- Button trigger modal -->
							<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
							  ' . msg('collaborators_add') . '
							</button>
							
							<!-- Modal -->
							<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
							  <div class="modal-dialog" role="document">
							    <div class="modal-content">
							      <div class="modal-header">
							        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							        <h4 class="modal-title" id="myModalLabel">' . msg('collaborators_add') . '</h4>
							      </div>
							      <div class="modal-body">';
            $sx .= '<div class="middle">' . msg('collaborators_info') . '</div><br>';
            $sx .= '<span class="small">' . msg('email') . '</span><br/>';
            $sx .= '<input type="text" name="email" id="email" class="form-control" aria-label="' . msg('find') . '">';

            $data = '';
            $data .= ', acao: "save" ';
            $url = base_url('index.php/thesa/ajax/collaborators_add/' . checkpost_link('hello') . '/1');
            $sx .= '
							      </div>
							      <div class="modal-footer">
							        <button type="button" class="btn btn-secondary" data-dismiss="modal">' . msg('cancel') . '</button>
							        <button type="button" class="btn btn-primary" data-dismiss="modal" id="submit">' . msg('add') . '</button>
							      </div>
							    </div>
							  </div>
							</div>		
							<div id="status">							
							</div>
							<script>
								$( "#submit" ).click(function() {
									$vlr = $("#email").val();
										$.ajax({
											url : "' . $url . '",
											type : "post",
											data : { dd1: $vlr ' . $data . ' }, 
											success : function(data) {
												$("#status").html(data);
										} });
								});
							</script>
					';
        }

        return ($sx);
    }

    function user_email_send($para, $nome, $code) {
        $anexos = array();
        $texto = $this -> email_cab();
        $de = 0;
        switch($code) {
            case 'SIGNUP' :
                $link = base_url('index.php/thesa/user_password_new/?dd0=' . $para . '&chk=' . checkpost_link($para . date("Ymd")));
                $assunto = 'Cadastro de novo usuários - Thesa';
                $texto .= '<p>' . msg('Dear') . ' <b>' . $nome . ',</b></p>';
                $texto .= '<p>Para ativar seu cadastro é necessário clicar no link abaixo:';
                $texto .= '<br><br>';
                $texto .= '<a href="' . $link . '" target="_new">' . $link . '</a></p>';
                $de = 1;
                break;
            case 'PASSWORD' :
                $this -> le_user_id($para);
                $link = base_url('index.php/thesa/user_password_new/?dd0=' . $para . '&chk=' . checkpost_link($para . date("Ymd")));
                $assunto = msg('Cadastro de novo senha') . ' - Thesa';
                $texto .= '<p>' . msg('Dear') . ' ' . $this -> line['us_nome'] . '</p>';
                $texto .= '<p>' . msg('change_new_password') . '</p>';
                $texto .= '<br><br>';
                $texto .= '<a href="' . $link . '" target="_new">' . $link . '</a>';
                $de = 1;
                break;
            default :
                $assunto = 'Enviado de e-mail';
                $texto .= ' Vinculo não informado ' . $code;
                $de = 1;
                break;
        }
        $texto .= $this -> email_foot();
        if ($de > 0) {
            enviaremail($para, $assunto, $texto, $de);
        } else {
            echo 'e-mail não enviado - ' . $code;
        }
    }

    function term_row($obj) {
        $obj -> fd = array('id_rl', 'rl_value', 'rl_lang');
        $obj -> lb = array('ID', msg('value'), msg('language'));
        $obj -> mk = array('', 'L', 'C');
        return ($obj);
    }

    function user_insert_temp($email, $name) {
        if ($this -> user_exist($email) == 0) {
            $md5 = md5($email . $name);
            $sql = "insert into users 
							( 	
							us_nome, us_email, us_login,
							us_password, us_autenticador, us_nivel,
							us_ativo
							)
							values
							('$name','$email','$email',
							'$md5','E',0,
							-1
							)";
            $rlt = $this -> db -> query($sql);
            $sql = "update users set us_codigo = lpad(id_us,7,0) where us_codigo = '' or us_codigo is null";
        }
    }

    function user_valid($email, $vlr = 1) {
        $sql = "update users
						set us_ativo = $vlr
						where us_email = '$email' ";
        $rlt = $this -> db -> query($sql);
        return (1);
    }

    function user_set_password($email, $pw) {
        $sql = "update users
						set us_ativo = 1,
						us_password = '$pw'
						where us_email = '$email' ";
        $rlt = $this -> db -> query($sql);
        return (1);
    }

    function user_exist($email) {
        $sql = "select * from users where us_email = '$email' ";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        if (count($rlt) > 0) {
            $this -> line = $rlt[0];
            return (1);
        } else {
            return (0);
        }
    }

    function le_user_id($id) {
        $id = sonumero($id);
        $sql = "select * from users where id_us = $id ";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        if (count($rlt) > 0) {
            $this -> line = $rlt[0];
            return (1);
        } else {
            return (0);
        }
    }

    function le_user_email($email) {
        $sql = "select * from users where us_email = '$email' or us_login = '$email' ";
        $rlt = $this -> db -> query($sql);

        $rlt = $rlt -> result_array();
        if (count($rlt) > 0) {
            $this -> line = $rlt[0];
            return (1);
        } else {
            return (0);
        }
    }

    function th_collaborators($th) {
        $sql = "select * from th_users 
								INNER JOIN users ON ust_user_id = id_us
								where ust_th = $th
								order by us_nome";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $sx = '<table width="100%" class="table">';
        $sx .= '<tr class="small">';
        $sx .= '<th width="2%">' . msg('#') . '</th>';
        $sx .= '<th width="48%">' . msg('userName') . '</th>';
        $sx .= '<th width="48%">' . msg('userEmail') . '</th>';
        $sx .= '</tr>';
        $id = 0;
        for ($r = 0; $r < count($rlt); $r++) {
            $id++;
            $line = $rlt[$r];
            $sx .= '<tr>';
            $sx .= '<td>';
            $sx .= $id;
            $sx .= '</td>';

            $sx .= '<td>';
            $sx .= $line['us_nome'];
            $sx .= '</td>';
            $sx .= '<td>';
            $sx .= $line['us_email'];
            $sx .= '</td>';
            $sx .= '</tr>';
        }
        $sx .= '</table>';
        return ($sx);
    }

    function user_thesa($th, $user, $tipo) {
        if ($tipo == 'INS') {
            $sql = "select * from th_users 
								where ust_user_id = $user and ust_th = $th";
            $rlt = $this -> db -> query($sql);
            $rlt = $rlt -> result_array();
            if (count($rlt) == 0) {
                $sql = "insert into th_users
									(
									ust_user_id, ust_user_role, ust_th,
									ust_status
									) values (
									$user, 1, $th,
									1)";
                $rlt = $this -> db -> query($sql);
                return (1);
            } else {
                $sql = "update th_users set ust_status = 1 
									where ust_user_id = $user and ust_th = $th";
                $rlt = $this -> db -> query($sql);
            }
            return (1);
        }
    }

    function search_term($t = '', $th = '0') {
        $sql = "select * from th_concept_term
						INNER JOIN rdf_literal ON id_rl = ct_term
						INNER JOIN th_thesaurus ON ct_th = id_pa
						where rl_value like '%" . $t . "%' and ct_th = $th
						order by rl_value";

        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $sx = '<table width="100%" class="table">';
        for ($r = 0; $r < count($rlt); $r++) {
            $line = $rlt[$r];

            $link = '<a href="' . base_url('index.php/thesa/c/' . $line['ct_concept'] . '/' . $line['ct_th']) . '" class="link">';
            $sx .= '<tr>';
            $sx .= '<td>';
            $sx .= $link;
            $sx .= $line['rl_value'];
            $sx .= '</a>';
            $sx .= '<sup>(';
            $sx .= $line['rl_lang'];
            $sx .= ')</sup>';
            $sx .= '</td>';
            $sx .= '<td>';
            $sx .= $line['pa_name'];
            $sx .= '</td>';
            $sx .= '</tr>';
        }
        $sx .= '</table>';
        return ($sx);
    }

    function log_show($idc) {
        $sql = "select * from  th_log 
						INNER JOIN users ON id_us = lg_user
					WHERE lg_c = $idc
					ORDER BY lg_date";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $sx = '';
        $sx .= '<span class="btn btn-secondary" id="view_log">' . msg('view_log') . '</span>';
        $sx .= '<span class="btn btn-secondary" id="hidden_log" style="display: none;">' . msg('hidden_log') . '</span>';
        $sx .= '<br><br>';
        $sx .= '<table width="100%" style="display: none;" id="table_log" class="table small">';
        $sx .= '<tr>';
        $sx .= '<th width="5%">' . msg('date') . '</th>';
        $sx .= '<th width="5%">' . msg('Action') . '</th>';
        $sx .= '<th width="65%">' . msg('Descript') . '</th>';
        $sx .= '<th width="25%">' . msg('User') . '</th>';
        $sx .= '</tr>';
        for ($r = 0; $r < count($rlt); $r++) {
            $line = $rlt[$r];
            $sx .= '<tr>';
            $sx .= '<td>' . stodbr($line['lg_date']) . '</td>';
            $sx .= '<td>' . $line['lg_action'] . '</td>';
            $sx .= '<td>' . $line['lg_descript'] . '</td>';
            $sx .= '<td>' . $line['us_nome'] . '</td>';
            $sx .= '</tr>';
        }
        $sx .= '</table>';
        $sx .= '<script>' . cr();
        $sx .= '	$( "#view_log" ).click(function() {
							$("#table_log").toggle("slow");
							$("#view_log").toggle();
							$("#hidden_log").toggle();
						});';
        $sx .= '	$( "#hidden_log" ).click(function() {
							$("#table_log").toggle("slow");
							$("#view_log").toggle();
							$("#hidden_log").toggle();
						});';
        $sx .= '</script>' . cr();
        return ($sx);
    }

    function log_insert($idc, $th, $act, $desc) {
        $user = $_SESSION['id'];
        $sql = "insert into th_log
					(
						lg_c, lg_user, lg_action, lg_descript, lg_th 
					) values (
						$idc, $user, '$act', '$desc', $th
					)";
        $rlt = $this -> db -> query($sql);
        return (1);
    }

    function export($th, $type) {
        $prefix = array();

        $sql = "select * from th_concept						
						INNER JOIN th_concept_term ON ct_concept = id_c
						LEFT JOIN rdf_literal ON ct_term = id_rl
						INNER JOIN rdf_resource ON ct_propriety = id_rs
						INNER JOIN rdf_prefix ON rs_prefix = id_prefix
						INNER JOIN thesa ON c_agency = id_thesa						
						WHERE c_th = $th
						order by thesa_prefix, c_concept";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $sx = '';
        for ($r = 0; $r < count($rlt); $r++) {
            $line = $rlt[$r];

            /* PREFIX */
            $pre = trim($line['thesa_prefix']);
            if (!isset($prefix[$pre])) { $prefix[$pre] = "url";
            }
            $pre = trim($line['prefix_ref']);
            if (!isset($prefix[$pre])) { $prefix[$pre] = "url";
            }

            $lang = '';

            $update = $line['c_created'];

            $p1 = $line['thesa_prefix'];
            $p1 .= ':';
            $p1 .= $line['c_concept'];

            $re = $line['prefix_ref'];
            $re .= ':';
            $re .= $line['rs_propriety'];

            if (strlen($line['rl_value']) == 0) {
                $p2 = $line['thesa_prefix'];
                $p2 .= ':';
                $p2 .= 'c' . $line['ct_concept_2'];
            } else {
                $p2 = parseToXML($line['rl_value']);
                $lang = ' language="' . $line['rl_lang'] . '" ';
                if ($line['rl_value'] == '') {
                    $p2 = '#';
                }
            }
            switch ($type) {
                case 'xml' :
                    $sx .= '<concept id="' . $p1 . '" propriety="' . $re . '" resource="' . $p2 . '" update="' . $update . '" ' . $lang . '/>' . cr();
                    break;
                default :
                    $sx .= $p1 . chr(9) . $re . chr(9) . '"' . $p2 . '"' . cr();
                    break;
            }

        }

        switch ($type) {
            case 'xml' :
                header("Content-type: text/xml");
                $sr = '<?xml version="1.0"?>' . cr();
                $sr .= '<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:si="https://www.w3schools.com/rdf/">' . cr();
                foreach ($prefix as $key => $value) {
                    $sr .= '<prefix id="' . $key . '" url="' . $value . '"/>' . cr();
                }
                $sr .= $sx;
                $sr .= '</rdf:RDF>';
                echo $sr;
                return ('');
                break;
            default :
                echo '<pre>' . $sx . '</pre>';
                break;
        }

    }

    function icone_update($th, $ic) {
        $sql = "update th_thesaurus
						set pa_icone = " . round($ic) . "
						where id_pa = " . $th;
        $this -> db -> query($sql);
        return (1);
    }

    function icones_select($th) {
        $sx = '';
        $sx .= '<div class="row">' . cr();
        for ($r = 0; $r < 1000; $r++) {
            $img = 'img/icone/thema/' . strzero($r, 3) . '.png';
            if (file_exists($img)) {
                $link = '<a href="' . base_url('index.php/thesa/icone/' . $th . '/' . $r . '/' . checkpost_link('icone' . $r)) . '">';
                $sx .= '<div class="col-2">' . $r . $link . $this -> show_icone($r) . '</a>' . '</div>' . cr();
            }

        }
        $sx .= '</div>' . cr();
        return ($sx);
    }

    function save($th, $type, $cnt) {
        $path = 'docs';
        if (!is_dir($path)) {
            mkdir($path);
        }
        $path .= '/' . $th;
        if (!is_dir($path)) {
            mkdir($path);
        }
        $fld = $path . '/html_' . $type . '.html';
        $f = fopen($fld, 'w');
        fwrite($f, $cnt);
        fclose($f);
        return (1);
    }

    function make_pdf($id) {
        $data = $this -> skoses -> le_th($id);
        $sx = '';
        $auth = '';
        $auth2 = '<br><br>';
        for ($r = 0; $r < count($data['authors']); $r++) {
            if (strlen($auth) > 0) {
                $auth .= '; ';
                $auth2 .= '<br>';
            }
            $auth .= $data['authors'][$r]['us_nome'];
            $auth2 .= UpperCase($data['authors'][$r]['us_nome']);
        }

        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once (dirname(__FILE__) . '/lang/eng.php');
            $pdf -> setLanguageArray($l);
        }
        $page = '<hr style="page-break-after: always;">';

        // ---------------------------------------------------------
        $sx .= '<div class="row" ><div class="col-12">';
        $sx .= '<h2 style="text-align: center; font-size: 35px;">THESA: ' . $data['pa_name'] . '</h2>';
        $sx .= '<center>';
        $sx .= '<span style="text-align: center; font-size: 15px;">' . msg('th_type_' . $data['pa_type']) . '</span>';
        $sx .= '<br>';
        $sx .= '<br>';
        /************************************ AUTHORS */
        for ($r = 0; $r < count($data['authors']); $r++) {
            $auth2 = UpperCase($data['authors'][$r]['us_nome']);
            $sx .= '<span style="text-align: center; font-size: 15px;">' . $auth2 . '</span><br>';
        }
        $sx .= '</center>';
        $sx .= '<br>';

        /******************* IMAGEM *********************/
        $img = 'img/background_custumer/biulings.jpg';
        $filename = 'img/background/background_thema_' . $id . '.jpg';
        if (file_exists($filename)) {
            $img = $filename;
        }
        $sx .= '<center><img src="' . base_url($img) . '" class="img-fluid" width="100%"></center>';
        $sx .= '<br>';
        $sx .= '<br>';
        $sx .= '</div>';
        $sx .= '</div>';

        if (strlen($data['pa_description']) > 0) {

        }

        /********************************************************************/
        $sx .= $page;
        /************************************************ METHODOLOGY *******/
        $sx .= '<div class="row" >
                  <div class="col-12">';
        $sx .= '    <div style="line-height: 170%; text-align:justify">';

        if (strlen($data['pa_introdution']) > 0) {
            $sx .= '<h1>' . msg('thesaurus_introdution') . '</h1>';
            $sx .= mst($data['pa_introdution']);
        }

        if (strlen($data['pa_audience']) > 0) {
            $sx .= '<h1>' . msg('thesaurus_audience') . '</h1>';
            $sx .= mst($data['pa_audience']);
        }

        if (strlen($data['pa_methodology']) > 0) {
            $sx .= '<h1>' . msg('thesaurus_methodology') . '</h1>';
            $sx .= mst($data['pa_methodology']);
        }
        $sx = troca($sx, '<br/>', '<br>&nbsp;<br>');
        $sx .= '    </div>
		          </div>
		       </div>';

        /******************************** GRAPHO ****************************/
        $data['file'] = $id;
        $sx .= $this -> load -> view('grapho/mind_map_full', $data, true);

        /******************************** GLOSSARIO ****************************/
        $filename = 'docs/' . $id . '/html_5.html';

        if (file_exists($filename)) {
            /********************************************************************/
            $sx .= $page;
            /********************************************************************/
            $sx .= '<div class="row"><div class="col-12" >';
            $sx .= '<h1>Apresentação Sistemática</h1>';
            $sx .= '</div></div><br>' . cr();
            $sx .= '<div class="row"><div class="col-12" >';
            $sx .= load_file_local($filename) . '';
            $sx .= '</div></div>' . cr();
        } else {
            echo 'Falha';
        }

        /******************************** GLOSSARIO ****************************/
        $filename = 'docs/' . $id . '/html_1.html';

        if (file_exists($filename)) {
            /********************************************************************/
            $sx .= $page;
            /********************************************************************/
            $sx .= '<div class="row"><div class="col-12" >';
            $sx .= '<h1>Glossário</h1>';
            $sx .= '</div></div>' . cr();
            $sx .= '<div class="row"><div class="col-12 prt" >';
            $sx .= load_file_local($filename);
            $sx .= '</div></div>' . cr();
        } else {
            echo 'Falha';
        }

        $filename = 'docs/' . $id . '/html_2.html';
        if (file_exists($filename)) {
            /********************************************************************/
            $sx .= $page;
            /********************************************************************/
            $sx .= '<div class="row"><div class="col-12" >';
            $sx .= '<h1>Apresentação Alfabética</h1>';
            $sx .= '</div></div>' . cr();
            $sx .= '<div class="row"><div class="col-12 prt" >';
            $sx .= load_file_local($filename);
            $sx .= '</div></div>' . cr();
        }

        $filename = 'docs/' . $id . '/html_3.html';
        if (file_exists($filename)) {
            /********************************************************************/
            $sx .= $page;
            /********************************************************************/
            $sx .= '<div class="row"><div class="col-12">';
            $sx .= '<h1>Ficha Terminológica para Coleta dos Termos</h1>';
            $sx .= '</div></div>' . cr();
            $sx .= '<div class="row"><div class="col-12 prt">';
            $sx .= load_file_local($filename);
            $sx .= '</div></div>' . cr();
        }
        return ($sx);
    }

    function make_pdf_2($id) {
        $data = $this -> skoses -> le_th($id);

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf -> setPrintFooter(false);

        // set document information
        $pdf -> SetCreator(PDF_CREATOR);
        $auth = '';
        $auth2 = '<br><br>';
        for ($r = 0; $r < count($data['authors']); $r++) {
            if (strlen($auth) > 0) {
                $auth .= '; ';
                $auth2 .= '<br>';
            }
            $auth .= $data['authors'][$r]['us_nome'];
            $auth2 .= UpperCase($data['authors'][$r]['us_nome']);
        }
        $pdf -> SetAuthor($auth);
        $pdf -> SetTitle('Thesa: ' . $data['pa_name']);
        $pdf -> SetSubject('Thesaurus');
        $pdf -> SetKeywords('Thesaurus');

        $pdf -> SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf -> setImageScale(PDF_IMAGE_SCALE_RATIO);

        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once (dirname(__FILE__) . '/lang/eng.php');
            $pdf -> setLanguageArray($l);
        }

        // ---------------------------------------------------------

        // set font
        $pdf -> SetFont('dejavusans', '', 10);

        // add a page
        $pdf -> AddPage();

        $pdf -> setXY(0, 70);
        $pdf -> SetFont('dejavusans', '', 25);
        $html = '<h2 style="text-align: center;">THESA: ' . $data['pa_name'] . '</h2>';
        $html .= '<span style="text-align: center; font-size: 15px;">' . msg('th_type_' . $data['pa_type']) . '</span>';
        $pdf -> writeHTML($html, true, false, true, false, '');

        /************************************ AUTHORS */
        $pdf -> setXY(0, 10);
        $pdf -> SetFont('dejavusans', '', 10);
        for ($r = 0; $r < count($data['authors']); $r++) {
            $pdf -> setXY(10, 5 * $r + 20);
            $auth2 = UpperCase($data['authors'][$r]['us_nome']);
            $pdf -> Cell(0, 0, $auth2, 0, false, 'C', 0, '', 0, false, 'M', 'M');
        }

        /******************* IMAGEM *********************/
        $img = 'img/background_custumer/biulings.jpg';
        $filename = 'img/background/background_thema_' . $id . '.jpg';
        if (file_exists($filename)) {
            $img = $filename;
        }
        $pdf -> Image($img, 0, 140, 210, 0);

        $pdf -> AddPage();
        $pdf -> SetFont('dejavusans', '', 10);

        $html = '<div style="line-height: 170%; text-align:justify">';

        if (strlen($data['pa_methodology']) > 0) {
            $html .= '<h1>' . msg('thesaurus_introdution') . '</h1>';
            $html .= mst($data['pa_introdution']);
        }

        if (strlen($data['pa_methodology']) > 0) {
            $html .= '<h1>' . msg('thesaurus_audience') . '</h1>';
            $html .= mst($data['pa_audience']);
        }

        if (strlen($data['pa_methodology']) > 0) {
            $html .= '<h1>' . msg('thesaurus_methodology') . '</h1>';
            $html .= mst($data['pa_methodology']);
        }
        $html = troca($html, '<br/>', '<br>&nbsp;<br>');
        $pdf -> writeHTML($html, true, false, true, false, '');

        /******************************** GLOSSARIO ****************************/
        $filename = 'docs/' . $id . '/html_1.html';

        if (file_exists($filename)) {
            $pdf -> AddPage();
            $html = '<h1>Glossário</h1>';
            $html = load_file_local($filename);
            $pdf -> writeHTML($html, true, false, true, false, '');
        } else {
            echo 'Falha';
        }

        $filename = 'docs/' . $id . '/html_2.html';
        if (file_exists($filename)) {
            $pdf -> AddPage();
            $html = '<h1>Glossário - Apresentação Alfabética</h1>';
            $html = load_file_local($filename);
            $pdf -> writeHTML($html, true, false, true, false, '');
        }

        $filename = 'docs/' . $id . '/html_3.html';
        if (file_exists($filename)) {
            $pdf -> AddPage();
            $html = '<h1>Glossário - Ficha Terminológica para Coleta dos Termos</h1>';
            $html = load_file_local($filename);
            $pdf -> writeHTML($html, true, false, true, false, '');
        }
        $pdf -> AddPage();

        // reset pointer to the last page
        $pdf -> lastPage();

        // ---------------------------------------------------------
        //Close and output PDF document
        $pdf -> Output('example_006.pdf', 'I');

        //============================================================+
        // END OF FILE
        //============================================================+

    }
    function th_list($th='')
        {
            $sql = "select * from th_thesaurus
                        INNER JOIN users ON pa_creator = id_us
                        order by pa_name";
            $rlt = $this->db->query($sql);
            $rlt = $rlt->result_array();
            $sx = '<table class="table">';
            for ($r=0;$r < count($rlt);$r++)
                {
                    $line = $rlt[$r];
                    $link = '<a href="'.base_url('index.php/thesa/select/'.$line['id_pa'].'/'.checkpost_link($line['id_pa'])).'">';
                    $sx .= '<tr>';
                    $sx .= '<td align="center">'.$line['id_pa'].'</td>';
                    $sx .= '<td>'.$link.$line['pa_name'].'</a>'.'</td>';
                    $sx .= '<td>'.$link.$line['us_nome'].'</a>'.'</td>';
                    $sx .= '</tr>';
                }
                $sx .= '</table>';
            return($sx);
        }    

}

function parseToXML($htmlStr) {
    $xmlStr = str_replace('<', '&lt;', $htmlStr);
    $xmlStr = str_replace('>', '&gt;', $xmlStr);
    $xmlStr = str_replace('"', '&quot;', $xmlStr);
    $xmlStr = str_replace("'", '&#39;', $xmlStr);
    $xmlStr = str_replace("&", '&amp;', $xmlStr);
    return $xmlStr;
}
?>