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
function msg($txt)
    {
        global $msg;
        if (isset($msg[$txt]))
            {
                $txt = $msg[$txt];
            }
        return($txt);
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
        $d = strtoupper($d);
        return $d;
    }    
    
    function UpperCaseSQL($d) {
        $d = ascii($d);
        $d = strtoupper($d);
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

function linkdel($url)
    {
        global $js_del;
        $sx = '';
        $sx .= anchor($url,'&nbsp;X&nbsp;',['class'=>'btn-primary small','onclick'=>'return confirma();','style'=>'border: 1px solid #00000; border-radius: 5px;']);
        if ($js_del == '')
            {
                $sx .= '
                <script>
                function confirma()
                    {
                        if (!confirm("Excluir registro?"))
                            {
                                return false;
                            }
                    }
                </script>';
                $js_del = true;
            }
        return($sx);
    }

function linked($url)
    {
        $sx = anchor($url,'&nbsp;ed&nbsp;',['class'=>'btn-warning small','style'=>'border: 1px solid #00000; border-radius: 5px;']);
        return($sx);        
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






    function tableview($th,$dt=array())
        {
            $url = base_url(PATH.$th->path);

            /********** Campos do formulário */
            $fl = $th->allowedFields;
            if (isset($_POST['action']))
                {
                    $search = $_POST["search"];
                    $search_field = $_POST["search_field"];
                    $th->like($fl[1],$search);
                    $_SESSION['srch_'] = $search;
                    $_SESSION['srch_tp'] = $search_field;
                } else {
                    //
                    $search = '';
                    $search_field = 0;
                    if (isset($_SESSION['srch_']))
                        {
                            $search = $_SESSION['srch_'];
                            $search_field = $_SESSION['srch_tp'];        
                        }
                    if (strlen($search) > 0)
                        {
                            $th->like($fl[$search_field],$search);
                        }
                }            
            $th->orderBy($fl[$search_field]);
            $v = $th->paginate(15);
            $p = $th->pager;

            /**************************************************************** TABLE NAME */
            $sx = bsc('<h1>'.$th->table.'</h1>',12);
    
            $st = '<table width="100%" border=1>';
            $st .= '<tr><td>';
            $st .= form_open();
            $st .= '</td><td>';
            $st .= '<select name="search_field" class="form-control">'.cr();
            for ($r=1;$r < count($fl);$r++)
                {
                    $sel = '';
                    if ($r==$search_field) { $sel = 'selected'; }
                    $st .= '<option value="'.$r.'" '.$sel.'>'.msg($fl[$r]).'</option>'.cr();
                }
            $st .= '</select>'.cr();
            $st .= '</td><td>';
            $st .= '<input type="text" class="form-control" name="search" value="'.$search.'">';
            $st .= '</td><td>';
            $st .= '<input type="submit" class="btn btn-primary" name="action" value="FILTER">';
            $st .= form_close();
            $st .= '</td><td align="right">';
            $st .=  $th->pager->links();
            $st .= '</td><td align="right">';
            $st .= $th->pager->GetTotal();
            $st .= '/'.$th->countAllResults();
            $st .= '/'.$th->pager->getPageCount();    
            $st .= '</td>';

            /*********** NEW */
            $st .= '<td align="right">';
            $st .= anchor($url.'/edit/',lang('new'),'class="btn btn-primary"');
            $st .= '</td></tr>';
            $st .= '</table>';

            $sx .= bs($st,12);

            $sx .= '<table class="table" border="1">';
    
            /* Header */
            $heads = $th->allowedFields;
            $sx .= '<tr>';
            $sx .= '<th>#</th>';
            for($h=1;$h < count($heads);$h++)
                {
                    $sx .= '<th>'.lang($heads[$h]).'</th>';
                }            
            $sx .= '</tr>'.cr();
    
            /* Data */
            for ($r=0;$r < count($v);$r++)
                {
                    $line = $v[$r];
                    $sx .= '<tr>';
                    foreach($fl as $field)
                        {
                            $vlr = $line[$field];
                            if (strlen($vlr) == 0) { $vlr = ' '; }
                            $sx .= '<td>'.anchor(($url.'/viewid/'.$line[$fl[0]]),$vlr).'</td>';
                        }   
                    /* Botoes */
                    $sx .= '<td>';
                    $sx .= linked($url.'/edit/'.$line[$fl[0]],'[ed]').'&nbsp;';
                    $sx .= linkdel($url.'/delete/'.$line[$fl[0]],'[x]');
                    $sx .= '</td>';

                    $sx .= '</tr>'.cr();
                }
            $sx .= '</table>';
            $sx .=  $th->pager->links();
            $sx .= bsdivclose();
            $sx .= bsdivclose();
            $sx .= bsdivclose();
            return($sx);    
        }        
?>