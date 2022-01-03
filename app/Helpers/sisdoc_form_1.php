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


    if (get("form") == $form_id) {
        /* Salvar dados */
        if (count($dt) > 0) 
            {
            if ($table != '*') 
                { $th->save($dt); }
            $sx .= bsmessage('SALVO');
            if (isset($th->path_back)) {
                $sx .= metarefresh($th->path_back, 0);
            } else {
                $sx .= bsmessage('$th->path_back não foi informado! - ' . $th->table, 3);
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
        $typ = $tp[$r];
        $vlr = '';
        if (isset($dt[$fld])) {
            $vlr = $dt[$fld];
        }
        $sx .= form_fields($typ, $fld, $vlr, $th);
    }

    /***************************************** BOTAO SUBMIT */
    if (!$submit) {
        $sx .= bt_submit() . ' | ' . bt_cancel($url) . cr();
    }

    /************************************** FIM DO FORMULARIO */

    $sx .= form_close() . cr();
    $sx .= '</div>';

    return ($sx);
}

function form_fields($typ, $fld, $vlr, $th = array())
{
    $lib = $th->lib;
    if (strlen($lib) > 0) {
        $lib .= '.';
    }
    if (substr($typ,0,1) == '[')
        {
            $typ = 'seq:'.substr($typ,1,strlen($typ)-2);
            $typ = troca($typ,'-',':');

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
    /************************************* Formulários */
    //echo '<br>' . $t;
    switch ($t) {
        case 'up':
            $sx .= '<input type="hidden" id="' . $fld . '" name="' . $fld . '" value="' . date("YmdHi") . '">';
            break;

        case 'dt':
            $sx .= $td . ($fld) . $tdc;
            $sx .= $td;
            $sx .= '<input type="text" id="' . $fld . '" name="' . $fld . '" value="' . $vlr . '" class="form-control" style="width:200px;">';
            $sx .= $tdc;
            break;

        case 'ur':
            $sx .= $td . ($fld) . $tdc;
            $sx .= $td;
            $sx .= '<input type="text" id="' . $fld . '" name="' . $fld . '" value="' . $vlr . '" class="form-control">';
            $sx .= $tdc;
            break;

        case 'yr':
            $sx .= $td . ($fld) . $tdc;
            $sx .= $td;
            $op = array();
            $opc = array();
            for ($r = date("Y") + 1; $r > 1900; $r--) {
                array_push($op, $r);
                array_push($opc, $r);
            }
            $sg = '<select id="' . $fld . '" name="' . $fld . '" class="form-control" style="width: 200px;">' . cr();
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
            $sx .= $td . ($fld) . $tdc;
            $sx .= $td;
            //$dt = $this->db->query("select * from oa_country where ct_lang = 'pt-BR'").findAll();

            $sql = "SELECT * FROM some_table WHERE ct_lang = :ct_lang:";
            $rlt = $this->db->query($sql, ['ct_lang' => 'pt-BR']);
            $op = array();
            $opc = array();
            for ($r = date("Y") + 1; $r > 1900; $r--) {
                array_push($op, $r);
                array_push($opc, $r);
            }
            $sg = '<select id="' . $fld . '" name="' . $fld . '" value="' . $vlr . '" class="form-control" style="width: 200px;">' . cr();
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
            $sx .= $td . ($fld) . $tdc;
            $sx .= $td;
            $sx .= '<textarea id="' . $fld . '" rows="' . $rows . '" name="' . $fld . '" class="form-control">' . $vlr . '</textarea>';
            $sx .= $tdc;
            break;

        case 'seq':
            $sx .= $td . ($fld) . $tdc;
            $sx .= $td;
            $op = array(1, 0);            
            $opt = substr($typ, strpos($typ, ':') + 1, strlen($typ));
            $opc = explode(':', $opt);
            $sg = '<select id="' . $fld . '" name="' . $fld . '" value="' . $vlr . '" class="form-control">' . cr();
            for ($r = $opc[0]; $r <= $opc[1]; $r++) {
                $sel = '';
                $vll = strzero($r,2);
                if ($vlr == $r) { $sel = 'selected'; }
                $sg .= '<option value="' . $vll . '" ' . strzero($r,2) . '>' . $vll . '</option>' . cr();
            }
            $sg .= '</select>' . cr();
            $sx .= $sg;
            $sx .= $tdc;
            break;            

        case 'sn':
            $sx .= $td . ($fld) . $tdc;
            $sx .= $td;
            $op = array(1, 0);
            $opc = array(msg('YES'), msg('NO'));
            $sg = '<select id="' . $fld . '" name="' . $fld . '" value="' . $vlr . '" class="form-control">' . cr();
            for ($r = 0; $r < count($op); $r++) {
                $sel = '';
                $sg .= '<option value="' . $op[$r] . '" ' . $sel . '>' . $opc[$r] . '</option>' . cr();
            }
            $sg .= '</select>' . cr();
            $sx .= $sg;
            $sx .= $tdc;
            break;

        case 'op':
            $sx .= $td . ($fld) . $tdc;
            $sx .= $td;
            $op = array(1, 0);            
            $opt = substr($typ, strpos($typ, ':') + 1, strlen($typ));
            $opc = explode(':', $opt);
            print_r($opc);
            $sg = '<select id="' . $fld . '" name="' . $fld . '" value="' . $vlr . '" class="form-control">' . cr();
            $sg .= '<option value="">:: options ::</option>'.cr();
            for ($r = 0; $r < count($opc); $r++) {
                $sel = '';
                $opx = explode('&',$opc[$r]);
                $sg .= '<option value="' . $opx[0] . '" ' . $sel . '>' . $opx[1] . '</option>' . cr();
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

            $sx .= $td . ($fld) . $tdc;
            $sx .= $td;
            print_r($q);
            $sql = 'select * from ' . $q[3];
            if (isset($q[4])) {
                $sql .= ' where ' . $q[4];
            }

            $query = $th->query($sql);
            $query = $query->getResult();

            $sg = '<select id="' . $fld . '" name="' . $fld . '" value="' . $vlr . '" class="form-control">' . cr();
            $sg .= '<option value=""></option>'.cr();
            for ($r = 0; $r < count($query); $r++) {
                $ql = (array)$query[$r];
                $sel = '';
                $sg .= '<option value="' . $ql[$fld1] . '" ' . $sel . '>' . $ql[$fld2] . '</option>' . cr();
            }
            $sg .= '</select>' . cr();
            $sx .= $sg;
            $sx .= $tdc;
            break;

        case 'in':
            $sx .= '<div class="form-group">' . cr();
            $sx .= '<small id="emailHelp" class="form-text text-muted">' . lang($lib . $fld) . '</small>';
            $sx .= '</div>';
            break;

        case 'select':
            $opt = substr($typ, strpos($typ, ':') + 1, strlen($typ));
            $opt = explode(':', $opt);

            $sx .= '<div class="form-group">' . cr();
            $sx .= '<small id="emailHelp" class="form-text text-muted">' . lang($lib . $fld) . '</small>';
            $sx .= '<select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" id="' . $fld . '" name="' . $fld . '">';
            $sx .= '<option>'.lang('Select an option').'</option>' . cr();
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
            $source = substr($typ, strpos($typ, ':') + 1, strlen($typ));
            if (strlen($source) == 0) 
                { 
                  $source = 'main.'; 
                } else {
                  $source .= '.';  
                }
            array_push($opt, lang($source.'status_0'));
            array_push($opt, lang($source.'status_1'));
            array_push($opt, lang($source.'status_2'));
            array_push($opt, lang($source.'status_3'));
            array_push($opt, lang($source.'status_4'));
            array_push($opt, lang($source.'status_9'));

            $sx .= '<div class="form-group">' . cr();
            $sx .= '<small id="emailHelp" class="form-text text-muted">' . lang($lib . $fld) . '</small>';
            $sx .= '<select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" id="' . $fld . '" name="' . $fld . '">';
            $sx .= '<option>Select...</option>' . cr();
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

        case 'version':
            if (strlen($vlr) == 0) { $vlr = version(); }
            $sx .= '<div class="form-group" style="margin-bottom: 20px;">' . cr();
            $sx .= '<label for="' . $fld . '">' . lang($lib . $fld) . '</label>
                        <input type="text" class="form-control" id="' . $fld . '" name="' . $fld . '" value="' . $vlr . '" placeholder="' . lang($lib . $fld) . '">                                
                        ' . cr();
            $sx .= '</div>';            
            break;


        case 'email':
            $sx .= '<div class="form-group" style="margin-bottom: 20px;">' . cr();
            $sx .= '<label for="' . $fld . '">' . lang($lib . $fld) . '</label>
                                <input type="email" class="form-control" id="' . $fld . '" name="' . $fld . '" value="' . $vlr . '" placeholder="' . lang($lib . $fld) . '">                                
                                ' . cr();
            $sx .= '</div>';
            break;

        case 'session':
            $opt = substr($typ, strpos($typ, ':') + 1, strlen($typ));
            if (isset($_SESSION[$opt]))
                {
                    $opt = $_SESSION[$opt];
                } else {
                    $opt = '-1';
                }
            $sx .= '<input type="hidden" id="' . $fld . '" name="' . $fld . '" value="' . $opt . '">';
            break;            

        case 'none':
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
            $sx .= '<label for="' . $fld . '">' . lang($lib . $fld) . '</label>
                                 <input type="password" class="form-control" id="' . $fld . '" name="' . $fld . '" value="' . $vlr . '" placeholder="' . lang($lib . $fld) . '">                                
                                 ' . cr();
            $sx .= '</div>';
            break;

        case 'st':
            $sx .= '<div class="form-group" style="margin-bottom: 20px;">' . cr();
            $sx .= '<label for="' . $fld . '">' . lang($lib . $fld) . '</label>
                                <input type="string" class="form-control" id="' . $fld . '" name="' . $fld . '" value="' . $vlr . '" placeholder="' . lang($lib . $fld) . '">                                
                                ' . cr();
            $sx .= '</div>';
            break;

        case 'text':
            $rows = 5;
            $sx .= '<div class="form-group" style="margin-bottom: 20px;">' . cr();
            $sx .= '<label for="' . $fld . '">' . lang($lib . $fld) . '</label>' . cr();
            $sx .= '<textarea id="' . $fld . '" rows="' . $rows . '" name="' . $fld . '" class="form-control">' . $vlr . '</textarea>';
            $sx .= $tdc;
            break;

        case 'url':
            $sx .= '<div class="form-group" style="margin-bottom: 20px;">' . cr();
            $sx .= '<label for="' . $fld . '">' . lang($lib . $fld) . '</label>
                                <input type="string" class="form-control" id="' . $fld . '" name="' . $fld . '" value="' . $vlr . '" placeholder="' . lang($lib . $fld) . '">                                
                                ' . cr();
            $sx .= '</div>';
            break;

        default:
            $sx .= bsmessage('OPS - ' . $t, 1);
    }
    $sx .= '</tr>';
    return ($sx);
}
