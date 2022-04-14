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

    function clog($msg)
        {
            $time = date("Y-m-d H:i:s");
            $tela = '<script>';
            $tela .= " console.log('$time - $msg');";
            $tela .= '</script>';
            echo $tela;
        }

    function msg($var)
        {
            return lang($var);
        }


    function get($var)
        {
            $vlr = '';
            if (isset($_GET[$var]))
                {
                    $vlr = $_GET[$var];
                }
            if (isset($_POST[$var]))
                {
                    $vlr = $_POST[$var];
                }
            //$vlr = str_replace($vlr,"'","~");
            return $vlr;
        }

    /* Funcao troca */
    function troca($qutf, $qc, $qt) 
    {
        if (!is_array($qc))
        {
            $qc = array($qc);
        }
        if (!is_array($qt))
        {
            $qt = array($qt);
        }        
        return (str_replace($qc, $qt, $qutf));
    }

    function perfil($tp='')
    {
        $access = false;
        if ((isset($_SESSION['check'])) and (isset($_SESSION['access'])))
            {                
                $check = $_SESSION['check'];
                $priv = $_SESSION['access'];
                $access = true;
            }
        return $access;
    }    

    function ascii($d)
    {    //$d = strtoupper($d);
        
        /* acentos agudos */
        $d = (str_replace(array('á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'), array('a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'), $d));
        
        /* acentos til */
        $d = (str_replace(array('ã', 'õ', 'Ã', 'Õ'), array('a', 'o', 'A', 'O'), $d));
        
        /* acentos cedilha */
        $d = (str_replace(array('ç', 'Ç', 'ñ', 'Ñ'), array('c', 'C', 'n', 'N'), $d));
        
        /* acentos agudo inverso */
        $d = (str_replace(array('à', 'è', 'ì', 'ò', 'ù', 'À', 'È', 'Ì', 'Ò', 'Ù'), array('a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'), $d));
        
        /* acentos agudo cinconflexo */
        $d = (str_replace(array('â', 'ê', 'î', 'ô', 'û', 'Â', 'Ê', 'Î', 'Ô', 'Û'), array('a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'), $d));
        
        /* trema */
        $d = (str_replace(array('ä', 'ë', 'ï', 'ö', 'ü', 'Ä', 'Ë', 'Ï', 'Ö', 'Ü'), array('a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'), $d));
        
        
        /* Especiais */
        $d = (str_replace(array('Å'), array('A'), $d));
        return $d;
    }

    function UpperCase($d) {
        $d = mb_strtoupper($d);
        return $d;
    }    
    
    function UpperCaseSQL($d) {
        $d = ascii($d);
        $d = mb_strtoupper($d);
        return $d;
    }
    
    function LowerCase($term) {
        $d = mb_strtolower($term);
        return ($d);
    }
    
    function LowerCaseSQL($term) {
        $term = ascii($term);
        $term = mb_strtolower($term);    
        return ($term);
    }    


/* checa e cria diretorio */
function dircheck($dir) {
    $ok = 0;
    if (is_dir($dir)) { $ok = 1;
    } else {
        mkdir($dir);
        $rlt = fopen($dir . '/index.php', 'w');
        fwrite($rlt, 'acesso restrito');
        fclose($rlt);
    }
    return ($ok);
}

function delTree($dir) 
    {
        if (is_dir($dir))    
        {
            $files = array_diff(scandir($dir), array('.','..'));
                foreach ($files as $file) {
                (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
                }
                return rmdir($dir);
        } else {
            return '<br>Not dir '.$dir;
        }
    }

function sonumero($t)
    {
        return preg_replace('/[^0-9]/', '', $t);
    }

function metarefresh($url,$time=0)
    {
        $sx = '<meta http-equiv="refresh" content="'.$time.';url='.$url.'" />';
        return $sx;
    }

function redireciona($url='/main/service',$time=2)
    {
        $sx = redirect()->to($url);
        return ($sx);
    }

function form_del($th)
    {
        $sx = '';
        $id = $th->id;
 
        if ($th->delete($id))
            {
                $sx .= bsmessage('Item excluído',1);
            } else {
                $sx .= bsmessage('Erro de exclusão',2);
            }

        $url = base_url($_SERVER['REQUEST_URI']);
        $url = substr($url,0,strpos($url,'/delete'));
        $sx .= anchor($url,'Voltar',['class'=>'btn btn-danger']);
        $sx = redireciona($url);
        return($sx);
    }

function cr()
    {
        return (chr(13).chr(10));
    }


function stodbr($dt)
    {
        $rst = substr($dt,6,2).'/'.substr($dt,4,2).'/'.substr($dt,0,4);
        return $rst;
    }
       
?>