<?php

/* Recupera IP
* @author Rene F. Gabriel Junior <renefgj@gmail.com>
* @versao v0.15.23
*/
function ip()
{
    $ip = trim($_SERVER['REMOTE_ADDR']);
    if ($ip == '::1') {
        $ip = '127.0.0.1';
    }
    return ($ip);
}


function bt_cancel($url)
{
    /******************** WCLOSE */
    if ($url == 'wclose')
        {
            $sx = '<a href="#" class="btn btn-outline-warning" onclick="wclose();">'.lang('rdf.return').'</a>';
            return $sx;
        }

    if (!isset($url))
        {
            return "";
        }
    if (strpos($url, '/edit')) {
        $url = substr($url, 0, strpos($url, '/edit'));
    }
    $sx = anchor($url, msg('return'), ['class' => 'btn btn-outline-warning']);
    return $sx;
}

function bt_submit($t = 'save')
{
    $sx = '<input type="submit" value="' . $t . '" class="btn btn-outline-primary">';
    return ($sx);
}

function form($th)
{
    $table = $th->table;
    $sx = '';

    $fl = $th->allowedFields;
    $tp = $th->typeFields;

    $id = round($th->id);

    /* Sem PATH */
    if (isset($th->path)) {
        $url = ($th->path . '/edit/' . $id);
    } else {
        echo 'Erro $this->path não informado';
        exit;
    }

    /********************************* Salvar *****************/
    $dt = $_POST;

    /* Load Data from registrer *******************************/
    if ((count($dt) == 0) and ($id > 0)) {
        $dt = $th->find($th->id);
    }

    /* Verifica o formulário correto **************************/
    if (!isset($th->form)) {
        $th->form = 'form_' . date("Ymd");
    }
    $form_id = md5($th->form);

    /******************************************* Checar obrigatoriedade */
    $mandatory = array();
    for ($r = 0; $r < count($tp); $r++) {
        $obr = substr($tp[$r], strlen($tp[$r]) - 1, 1);
        if ($obr == '*') {
            array_push($mandatory, 1);
            $tp[$r] = substr($tp[$r], 0, strlen($tp[$r]) - 1);
        } else {
            array_push($mandatory, 0);
        }
    }


    if (get("form") == $form_id) {
        /* Salvar dados */
        $ok = 1;
        /**********************************/
        for ($r = 0; $r < count($fl); $r++) {
            if (($mandatory[$r] == 1) and (strlen(get($fl[$r])) == 0)) {
                $ok = 0;
            }
            /********************** TRATAMENTO DE CAMPO ASC */
            if (strtolower(substr($tp[$r], 0, 3)) == 'asc') {
                $vlr = trim(get($fl[$r]));
                $vlr = ascii($vlr);
                $vlr = LowerCase($vlr);
                $vlr = troca($vlr, ' ', '_');
                $_POST[$fl[$r]] = $vlr;
                $dt[$fl[$r]] = $vlr;
            }
        }

        /* Checa submissão */
        $th->saved = 0;
        if ((count($dt) > 0) and ($ok == 1)) {
            $th->saved = 1;
            if ($table != '*') {
                $id = $th->save($dt);
                $idx = $th->insertID;
                $th->id = $idx;
            }
            $sx .= bsmessage('SALVO');
            if (isset($th->path_back)) {
                switch ($th->path_back) {
                    case 'close':
                        $sx .= '<script>close();</script>';
                        break;
                    case 'wclose':
                        $sx .= '<script>window.opener.location.reload(); close();</script>';
                        break;
                    case 'nome':
                        $sx .= '';
                        break;
                    default:
                        $sx .= metarefresh($th->path_back, 1);
                        break;
                }
            } else {
                $sx .= bsmessage('$this->path_back não foi informado! - ' . $th->table, 3);
            }
            return ($sx);
        }
    }


    /************************************************ Formulário */
    $attr = array('name' => $th->form);
    $sx .= '<div class="shadow p-3 mb-5 bg-white rounded">';
    $sx .= form_open($url, $attr) . cr();
    $sx .= '<input type="hidden" name="form" value="' . $form_id . '">';
    $submit = false;

    /* Formulario */
    for ($r = 0; $r < count($fl); $r++) {
        $fld = $fl[$r];
        if (!isset($tp[$r])) {
            $typ = 'string:100';
        } else {
            $typ = $tp[$r];
        }

        $vlr = '';
        if (isset($dt[$fld])) {
            $vlr = $dt[$fld];
        }

        /********************************** Prefixo da biblioteca de texto */
        $pre = '';
        if (isset($th->pre)) {
            $pre = $th->pre;
        }

        /***************************************************** monta campo */
        if (!isset($mandatory[$r])) {
            $mandatory[$r] = 0;
        }
        $sx .= form_fields($typ, $fld, $vlr, $th, $mandatory[$r], $pre);
    }

    /***************************************** BOTAO SUBMIT */
    if (!$submit) {
        $sx .= bsc(bt_submit() . ' &nbsp;|&nbsp; ' . bt_cancel($th->path_back), 12, 'text-end mt-5 mb-3') . cr();
    }

    /************************************** FIM DO FORMULARIO */

    $sx .= form_close() . cr();
    $sx .= '<style>
                 .bg-mandatory { background-color: #FFEEEE; }
                 .form-control.imput
                    {
                        background-color: #EEEEFF;
                    }

            </style>';
    $sx .= '</div>';

    return ($sx);
}

function form_fields($typ, $fld, $vlr, $th = array(), $obg = 0, $pre = '')
{
    $class_mandatory = '';
    if (($obg == 1) and ($vlr == '')) {
        $class_mandatory = 'bg-mandatory';
    }
    $fld = troca($fld, '*', '');
    $label_mandatory = '';
    if ($obg == 1) {
        $label_mandatory = '<span class="text-danger">*</span>';
    }
    /*
    $lib = $th->lib;
    if (strlen($lib) > 0) {
        $lib .= '.';
    }
    */
    if (substr($typ, 0, 1) == '[') {
        $typ = 'seq:' . substr($typ, 1, strlen($typ) - 2);
        $typ = troca($typ, '-', ':');
    }
    $td = '<div class="form-group">';
    $tdc = '</div>';
    /*********** Mandatory */
    $sub = 0;
    $mandatory = false;
    $sx = '<tr>';
    $typ = str_replace(array('*'), '', $typ);
    if (strpos($typ, ':') > 0) {
        $t = substr($typ, 0, strpos($typ, ':'));
    } else {
        $t = $typ;
    }

    if ($t == 'index') {
        $t = 'hidden';
    }
    if ($t == 'none') {
        $t = 'hr';
    }

    if ($t == 'checkbox') {
        $t = 'ck';
    }
    if ($t == 'sql') {
        $t = 'qr';
    }
    if ($t == 'hi') {
        $t = 'hidden';
    }
    if ($t == 'string') {
        $t = 'st';
    }
    if ($t == 'year') {
        $t = 'yr';
    }
    if (($t == 'update') or ($t=='now')) {
        $t = 'up';
    }

    if ($t == 'asc') {
        $t = 'st';
        $label_mandatory = ' - ' . '<span class="text-warning">' . lang($pre . 'no_use_especial_char') . '</span>';
    }
    /************************************* Formulários */
    switch ($t) {
        case 'hr':
            $sx .= $td . ' &nbsp; ' . $tdc;
            break;
        case 'ck':
            $chk = '';
            if ($vlr == 1) {
                $chk = 'checked';
            }
            $sx .= '<input type="checkbox" id="' . $fld . '" name="' . $fld . '" value="1" ' . $chk . '>';
            $sx .= ' ' . lang($pre . $fld);
            break;

        case 'up':
            $sx .= '<input type="hidden" id="' . $fld . '" name="' . $fld . '" value="' . date("YmdHi") . '">';
            break;

        case 'dt':
            $sx .= $td . lang($pre . $fld) . $label_mandatory . $tdc;
            $sx .= $td;
            $sx .= '<input type="text" id="' . $fld . '" name="' . $fld . '" value="' . $vlr . '" class="form-control ' . $class_mandatory . '" style="width:200px;">';
            $sx .= $tdc;
            break;

        case 'ur':
            $sx .= $td . lang($pre . $fld) . $label_mandatory . $tdc;
            $sx .= $td;
            $sx .= '<input type="text" id="' . $fld . '" name="' . $fld . '" value="' . $vlr . '" class="form-control ' . $class_mandatory . '">';
            $sx .= $tdc;
            break;

        case 'yr':
            $sx .= $td . lang($pre . $fld) . $label_mandatory . $tdc;
            $sx .= $td;
            $op = array();
            $opc = array();
            for ($r = date("Y") + 1; $r > 1900; $r--) {
                array_push($op, $r);
                array_push($opc, $r);
            }
            $sg = '<select id="' . $fld . '" name="' . $fld . '" class="form-control mb-3 ' . $class_mandatory . '" style="width: 200px;">' . cr();
            $sg .= '<option value="0">' . '- - -' . '</option>' . cr();
            for ($r = 0; $r < count($op); $r++) {
                $sel = '';
                if ($vlr == $op[$r]) {
                    $sel = 'selected ';
                }
                $sg .= '<option value="' . $op[$r] . '" ' . $sel . '>' . $opc[$r] . '</option>' . cr();
            }
            $sg .= '</select>' . cr();
            $sx .= $sg;
            $sx .= $tdc;
            break;

        case 'pl':
            $sx .= $td . lang($pre . $fld) . $label_mandatory . $tdc;
            $sx .= $td;
            //$dt = $this->db->query("select * from oa_country where ct_lang = 'pt-BR'").findAll();

            $sql = "SELECT * FROM some_table WHERE ct_lang = :ct_lang:";
            $rlt = $th->query($sql, ['ct_lang' => 'pt-BR']);
            $op = array();
            $opc = array();
            for ($r = date("Y") + 1; $r > 1900; $r--) {
                array_push($op, $r);
                array_push($opc, $r);
            }
            $sg = '<select id="' . $fld . '" name="' . $fld . '" value="' . $vlr . '" class="form-control mb-3 ' . $class_mandatory . '" style="width: 200px;">' . cr();
            for ($r = 0; $r < count($op); $r++) {
                $sel = '';
                $sg .= '<option value="' . $op[$r] . '" ' . $sel . '>' . $opc[$r] . '</option>' . cr();
            }
            $sg .= '</select>' . cr();
            $sx .= $sg;
            $sx .= $tdc;
            break;

        case 'tx':
            $rows = 5;
            $sx .= $td . lang($pre . $fld) . $label_mandatory . $tdc;
            $sx .= $td;
            $sx .= '<textarea id="' . $fld . '" rows="' . $rows . '" name="' . $fld . '" class="form-control ' . $class_mandatory . '">' . $vlr . '</textarea>';
            $sx .= $tdc;
            break;

        case 'seq':
            $sx .= $td . lang($pre . $fld) . $label_mandatory . $tdc;
            $sx .= $td;
            $op = array(1, 0);
            $opt = substr($typ, strpos($typ, ':') + 1, strlen($typ));
            $opc = explode(':', $opt);
            $sg = '<select id="' . $fld . '" name="' . $fld . '" value="' . $vlr . '" class="form-control mb-3' . $class_mandatory . '">' . cr();
            for ($r = $opc[0]; $r <= $opc[1]; $r++) {
                $sel = '';
                $vll = strzero($r, 2);
                if (round($vlr) == $r) {
                    $sel = 'selected';
                }
                $sg .= '<option value="' . $vll . '" ' . strzero($r, 2) . ' ' . $sel . '>' . $vll . '</option>' . cr();
            }
            $sg .= '</select>' . cr();
            $sx .= $sg;
            $sx .= $tdc;
            break;

        case 'sn':
            $sx .= $td . lang($pre . $fld) . $label_mandatory . $tdc;
            $sx .= $td;
            $op = array(1, 0);
            $opc = array(msg($pre . 'YES'), msg($pre . 'NO'));
            $sg = '<select id="' . $fld . '" name="' . $fld . '" value="' . $vlr . '" class="form-control mb-3 ' . $class_mandatory . '">' . cr();
            for ($r = 0; $r < count($op); $r++) {
                $sel = '';
                if ($op[$r] == $vlr) {
                    $sel = 'selected';
                }
                $sg .= '<option value="' . $op[$r] . '" ' . $sel . '>' . $opc[$r] . '</option>' . cr();
            }
            $sg .= '</select>' . cr();
            $sx .= $sg;
            $sx .= $tdc;
            break;

        case 'op':
            $sx .= $td . lang($pre . $fld) . $label_mandatory . $tdc;
            $sx .= $td;
            $op = array(1, 0);
            $opt = substr($typ, strpos($typ, ':') + 1, strlen($typ));
            $opc = explode(':', $opt);
            $sg = '<select id="' . $fld . '" name="' . $fld . '" value="' . $vlr . '" class="form-control mb-3 ' . $class_mandatory . '">' . cr();
            $sg .= '<option value="">:: options ::</option>' . cr();
            for ($r = 0; $r < count($opc); $r++) {
                $sel = '';
                $opx = explode('&', $opc[$r]);
                if ($opx[0] == $vlr) {
                    $sel = 'selected';
                }
                $sg .= '<option class="' . $class_mandatory . ' value="' . $opx[0] . '" ' . $sel . '>' . $opx[1] . '</option>' . cr();
            }
            $sg .= '</select>' . cr();
            $sx .= $sg;
            $sx .= $tdc;
            break;
            /**************************************** Query */
        case 'qr':
            $q = explode(':', trim(substr($typ, 2, strlen($typ))));
            $fld1 = $q[1];
            $fld2 = $q[2];

            $sx .= $td . lang($pre . $fld) . $label_mandatory . $tdc;
            $sx .= $td;

            $sql = 'select * from ' . $q[3];
            if (isset($q[4])) {
                $sql .= ' where ' . $q[4];
            }

            $query = $th->query($sql);
            $query = $query->getResult();

            $sg = '<select id="' . $fld . '" name="' . $fld . '" class="form-control mb-3 ' . $class_mandatory . '">' . cr();
            $sg .= '<option value=""></option>' . cr();
            for ($r = 0; $r < count($query); $r++) {
                $ql = (array)$query[$r];
                $sel = '';
                if ($vlr == $ql[$fld1]) {
                    $sel = 'selected';
                }
                $sg .= '<option value="' . $ql[$fld1] . '" ' . $sel . '>' . $ql[$fld2] . '</option>' . cr();
            }
            $sg .= '</select>' . cr();
            $sx .= $sg;
            $sx .= $tdc;
            break;

        case 'in':
            $sx .= '<div class="form-group">' . cr();
            $sx .= '<small id="emailHelp" class="form-text text-muted">' . lang($pre . $fld) . '</small>';
            $sx .= '</div>';
            break;

        case 'select':
            $opt = substr($typ, strpos($typ, ':') + 1, strlen($typ));
            $opt = explode(':', $opt);

            $sx .= '<div class="form-group">' . cr();
            $sx .= '<small id="emailHelp" class="form-text text-muted">' . lang($pre . $fld) . $label_mandatory . '</small>';
            $sx .= '<select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" id="' . $fld . '" name="' . $fld . '">';
            $sx .= '<option>' . lang('Select an option') . '</option>' . cr();
            for ($r = 0; $r < count($opt); $r++) {
                $chk = '';
                if ($vlr == $r) {
                    $chk = 'selected';
                }
                $sx .= '<option value="' . $r . '" ' . $chk . '>' . lang($opt[$r]) . '</option>' . cr();
            }
            $sx .= '</select>';
            $sx .= '</div>';
            break;

        case 'status':
            $opt = array();
            if (strpos($typ, ':') > 0) {
                $source = substr($typ, strpos($typ, ':') + 1, strlen($typ));
            } else {
                $source = $pre;
            }
            if (strlen($source) == 0) {
                $source = 'main.';
            } else {
                $source .= '.';
            }

            $sx .= '<div class="form-group">' . cr();
            $sx .= '<small id="emailHelp" class="form-text text-muted">' . lang($pre . $fld) . '</small>';
            $sx .= '<select class="form-select mb-3" aria-label=".form-select-lg example" id="' . $fld . '" name="' . $fld . '">';
            $sx .= '<option>Select...</option>' . cr();
            for ($r = 0; $r <= 9; $r++) {
                $chk = '';
                $txtv = $source . 'status_' . $r;
                if (lang($txtv) != $txtv) {
                    if ($vlr == $r) {
                        $chk = 'selected';
                    }
                    $sx .= '<option value="' . $r . '" ' . $chk . '>' . lang($source . $txtv) . '</option>' . cr();
                }
            }
            $sx .= '</select>';
            $sx .= '</div>';
            break;

        case 'version':
            if (strlen($vlr) == 0) {
                $vlr = version();
            }
            $sx .= '<div class="form-group" style="margin-bottom: 20px;">' . cr();
            $sx .= '<label for="' . $fld . '">' . lang($pre . $fld) . $label_mandatory . '</label>
                        <input type="text" class="form-control ' . $class_mandatory . '" id="' . $fld . '" name="' . $fld . '" value="' . $vlr . '" placeholder="' . lang($pre . $fld) . '">
                        ' . cr();
            $sx .= '</div>';
            break;


        case 'email':
            $sx .= '<div class="form-group" style="margin-bottom: 20px;">' . cr();
            $sx .= '<label for="' . $fld . '">' . lang($pre . $fld) . $label_mandatory . '</label>
                                <input type="email" class="form-control ' . $class_mandatory . '" id="' . $fld . '" name="' . $fld . '" value="' . $vlr . '" placeholder="' . lang($pre . $fld) . '">
                                ' . cr();
            $sx .= '</div>';
            break;

        case 'session':
            $opt = substr($typ, strpos($typ, ':') + 1, strlen($typ));
            if (isset($_SESSION[$opt])) {
                $opt = $_SESSION[$opt];
            } else {
                $opt = '-1';
            }
            $sx .= '<input type="hidden" id="' . $fld . '" name="' . $fld . '" value="' . $opt . '">';
            break;

        case 'set':
            $opt = substr($typ, strpos($typ, ':') + 1, strlen($typ));
            $sx .= '<input type="hidden" id="' . $fld . '" name="' . $fld . '" value="' . $opt . '">';
            break;

        case 'hidden':
            $sx .= '<input type="hidden" id="' . $fld . '" name="' . $fld . '" value="' . $vlr . '">';
            break;

        case 'user':
            $user_id = user_id();
            $sx .= '<input type="hidden" id="' . $fld . '" name="' . $fld . '" value="' . $user_id . '">';
            break;

        case 'password':
            $sx .= '<div class="form-group" style="margin-bottom: 20px;">' . cr();
            $sx .= '<label for="' . $fld . '">' . lang($pre . $fld) . $label_mandatory . '</label>
                                 <input type="password" class="form-control ' . $class_mandatory . '" id="' . $fld . '" name="' . $fld . '" value="' . $vlr . '" placeholder="' . lang($pre . $fld) . '">
                                 ' . cr();
            $sx .= '</div>';
            break;

        case 'st':
            if (strlen($vlr) != 0) {
                $class_mandatory = '';
            }
            $sx .= '<div class="form-group" style="margin-bottom: 20px;">' . cr();
            $sx .= '<label for="' . $fld . '">' . lang($pre . $fld) . $label_mandatory . '</label>
                                <input type="string" class="form-control ' . $class_mandatory . '" id="' . $fld . '" name="' . $fld . '" value="' . $vlr . '" placeholder="' . lang($pre . $fld) . '">
                                ' . cr();
            $sx .= '</div>';
            break;

        case 'text':
            $rows = 5;
            $sx .= '<div style="margin-bottom: 20px;" class="form-group">' . cr();
            $sx .= '<label for="' . $fld . '">' . lang($pre . $fld) . $label_mandatory . '</label>' . cr();
            $sx .= '<textarea id="' . $fld . '" rows="' . $rows . '" name="' . $fld . '" class="form-control ' . $class_mandatory . '">' . $vlr . '</textarea>';
            $sx .= $tdc;
            break;

        case 'url':
            $sx .= '<div class="form-group" style="margin-bottom: 20px;">' . cr();
            $sx .= '<label for="' . $fld . '">' . lang($pre . $fld) . $label_mandatory . '</label>
                                <input type="string" class="form-control ' . $class_mandatory . '" id="' . $fld . '" name="' . $fld . '" value="' . $vlr . '" placeholder="' . lang($pre . $fld) . '">
                                ' . cr();
            $sx .= '</div>';
            break;

        default:
            $sx .= bsmessage('OPS - ' . $t, 1);
    }
    $sx .= '</tr>';
    return ($sx);
}