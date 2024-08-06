<?php

/**
 * CodeIgniter Form Helpers
 *
 * @package     CodeIgniter
 * @subpackage  Forms SisDoc
 * @category    Helpers
 * @author      Rene F. Gabriel Junior <renefgj@gmail.com>
 * @link        http://www.sisdoc.com.br/CodIgniter
 * @version     v0.21.06.24
 */
//$sx .= form($url,$dt,$this);
require('sisdoc_form_1.php');
require('sisdoc_form_2.php');
require('sisdoc_form_3.php');
require('sisdoc_date.php');
require('sisdoc_cookies.php');
require('sisdoc_form_js.php');
require('sisdoc_drag_drop.php');
require('sisdoc_help.php');
require('sisdoc_email.php');

function clog($msg)
{
    $time = date("Y-m-d H:i:s");
    $tela = '<script>';
    $tela .= " console.log('$time - $msg');";
    $tela .= '</script>';
    echo $tela;
}

function sn($v)
    {
        if ($v == 0)
            {
                return lang("brapci.NO");
            } else {
                return lang("brapci.YES");
            }
    }

function msg($var)
{
    return lang($var);
}


function get($var)
{
    $vlr = '';
    if (isset($_GET[$var])) {
        $vlr = $_GET[$var];
    }
    if (isset($_POST[$var])) {
        $vlr = $_POST[$var];
    }
    //$vlr = str_replace($vlr,"'","~");
    return $vlr;
}

/* Funcao troca */
function troca($qutf, $qc, $qt)
{
    if (!is_array($qc)) {
        $qc = array($qc);
    }
    if (!is_array($qt)) {
        $qt = array($qt);
    }
    return (str_replace($qc, $qt, $qutf));
}

function perfil($tp = '')
{
    $access = false;
    if ((isset($_SESSION['check'])) and (isset($_SESSION['access']))) {
        $check = $_SESSION['check'];
        $priv = $_SESSION['access'];
        $access = true;
    }
    return $access;
}

function ascii($d)
{    //$d = strtoupper($d);

    $gr = array();
    $gr['-'] = '–';
    $gr['.'] = 'º';
    $gr['A'] = 'Â;À;Á;Ä;Ã;Å';
    $gr['a'] = 'â;ã;à;á;ä';
    $gr['E'] = 'Ê;È;É;Ë';
    $gr['e'] = 'ê;è;é;ë';
    $gr['I'] = 'Î;Í;Ì;Ï';
    $gr['i'] = 'î;í;ì;ï';
    $gr['O'] = 'Ô;Õ;Ò;Ó;Ö';
    $gr['o'] = 'ô;õ;ò;ó;ö';
    $gr['U'] = 'Û;Ù;Ú;Ü';
    $gr['u'] = 'û;ú;ù;ü';
    $gr['c'] = 'ç';
    $gr['C'] = 'Ç';

    foreach ($gr as $lt => $gpa) {
        $grp = explode(';', $gpa);
        for ($r = 0; $r < count($grp); $r++) {
             $d = troca($d, $grp[$r], $lt);
        }
    }
    $name = '';
    for ($r=0;$r < strlen($d);$r++)
    {
        $c = substr($d,$r,1);
        $o = ord($c);
        if ($o <= 127)
        {
            $name .= $c;
        }
    }
    return $name;
}

function UpperCase($d)
{
    $d = mb_strtoupper($d);
    return $d;
}

function UpperCaseSQL($d)
{
    $d = ascii($d);
    $d = mb_strtoupper($d);
    return $d;
}

function LowerCase($term)
{
    $d = mb_strtolower($term);
    return ($d);
}

function LowerCaseSQL($term)
{
    $term = ascii($term);
    $term = mb_strtolower($term);
    return ($term);
}


/* checa e cria diretorio */
function dircheck($dir)
{
    return checkdirs($dir);
}

function checkdirs($dir)
{
    $dirs = troca($dir,'\\','/');
    $dirs = explode('/', $dir);
    $dir = '';
    for ($r=0;$r < count($dirs);$r++)
        {
            $dir .= $dirs[$r] . '/';
            if (!is_dir($dir)) {
                mkdir($dir);
                $rlt = fopen($dir . '/index.php', 'w');
                fwrite($rlt, 'acesso restrito');
                fclose($rlt);
            }
        }
    return "";
}

function delTree($dir)
{
    if (is_dir($dir)) {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    } else {
        return '<br>Not dir ' . $dir;
    }
}

function sonumero($t)
{
    return preg_replace('/[^0-9]/', '', $t);
}

function metarefresh($url, $time = 0)
{
    $sx = '<meta http-equiv="refresh" content="' . $time . ';url=' . $url . '" />';
    return $sx;
}

function redireciona($url = '/main/service', $time = 2)
{
    $sx = redirect()->to($url);
    return ($sx);
}

function form_del($th)
{
    $sx = '';
    $id = $th->id;

    if ($th->delete($id)) {
        $sx .= bsmessage('Item excluído', 1);
    } else {
        $sx .= bsmessage('Erro de exclusão', 2);
    }

    $url = base_url($_SERVER['REQUEST_URI']);
    $url = substr($url, 0, strpos($url, '/delete'));
    $sx .= anchor($url, 'Voltar', ['class' => 'btn btn-danger']);
    $sx = redireciona($url);
    return ($sx);
}

function cr($tp=1)
{
    switch($tp)
        {
        case 0:
            return (chr(13));
            break;
        default:
            return (chr(13) . chr(10));
            break;

    }
}
