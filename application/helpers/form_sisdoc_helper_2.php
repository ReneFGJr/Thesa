<?php

function nbr_author($xa, $tp) 
{
    $xa = utf8_decode($xa);
    if (strpos($xa, ',') > 0) 
    {
        $xb = trim(substr($xa, strpos($xa, ',') + 1, 100));
        $xa = trim(substr($xa, 0, strpos($xa, ',')));
        $xa = trim(trim($xb) . ' ' . $xa);
    }
    $xa = $xa . ' ';
    $xp = array();
    $xx = "";
    for ($qk = 0; $qk < strlen($xa); $qk++)
    {
        if (substr($xa, $qk, 1) == ' ') 
        {
            if (strlen(trim($xx)) > 0) 
            {
                array_push($xp, trim($xx));
                $xx = '';
            }
        } else {
            $xx = $xx . substr($xa, $qk, 1);
        }
    }

    $xa = "";

    /////////////////////////////
    $xp1 = "";
    $xp2 = "";
    $er1 = array("JUNIOR", "JÚNIOR", "JúNIOR", "NETTO", "NETO", "SOBRINHO", "FILHO", "JR.");
    ///////////////////////////// SEPARA NOMES
    $xop = 0;
    for ($qk = count($xp) - 1; $qk >= 0; $qk--) 
    {
        $xa = trim($xa . ' - ' . $xp[$qk]);
        if ($xop == 0) 
        { 
            $xp1 = trim($xp[$qk] . ' ' . $xp1);
            $xop = -1;
        } else { 
            $xp2 = trim($xp[$qk] . ' ' . $xp2);

            if ($xop == -1) 
            {
                $xop = 1;
                for ($kr = 0; $kr < count($er1); $kr++) 
                {
                    if (trim(UpperCaseSQL($xp[$qk])) == trim($er1[$kr])) 
                    {
                        $xop = 0;
                    }
                }
            }
        }
    }

    ////////// 1 e 2
    $xp2a = strtolower($xp2);
    $xa = trim(trim($xp2) . ' ' . trim($xp1));
    if (($tp == 1) or ($tp == 2)) 
    {
        if ($tp == 1) { $xp1 = UpperCase($xp1);
        }
        $xa = trim(trim($xp1) . ', ' . trim($xp2));
        if ($tp == 2) 
        { 
            $xa = UpperCaseSQL(trim(trim($xp1) . ', ' . trim($xp2)));
        }
    }
    if (($tp == 3) or ($tp == 4)) {
        if ($tp == 4) { $xa = UpperCaseSQL($xa);
        }
    }

    if (($tp >= 5) or ($tp <= 6)) 
    {
        $xp2a = str_word_count(lowerCaseSQL($xp2), 1);
        $xp2 = '';
        for ($k = 0; $k < count($xp2a); $k++) 
        {
            if ($xp2a[$k] == 'do') 
            { 
                $xp2a[$k] = '';
            }
            if ($xp2a[$k] == 'da') 
            { 
                $xp2a[$k] = '';
            }
            if ($xp2a[$k] == 'de') 
            { 
                $xp2a[$k] = '';
            }
            if (strlen($xp2a[$k]) > 0) 
            { 
                $xp2 = $xp2 . substr($xp2a[$k], 0, 1) . '. ';
            }
        }
        $xp2 = trim($xp2);
        if ($tp == 6) 
        { 
            $xa = UpperCaseSQL(trim(trim($xp2) . ' ' . trim($xp1)));
        }
        if ($tp == 5) 
        { 
            $xa = UpperCaseSQL(trim(trim($xp1) . ', ' . trim($xp2)));
        }
    }

    ////////////////////////////////////////////////////////////////////////////////////
    if (($tp == 7) or ($tp == 8)) 
    {
        $mai = 1;
        $xa = strtolower($xa);
        for ($r = 0; $r < strlen($xa); $r++) 
        {
            if ($mai == 1) 
            { 
                $xa = substr($xa, 0, $r) . UpperCase(substr($xa, $r, 1)) . substr($xa, $r + 1, strlen($xa));
                $mai = 0;
            } else {
                if (substr($xa, $r, 1) == ' ') 
                { 
                    $mai = 1;
                }
            }
        }
        $xa = troca($xa, 'De ', 'de ');
        $xa = troca($xa, 'Da ', 'da ');
        $xa = troca($xa, 'Do ', 'do ');
        $xa = troca($xa, ' O ', ' o ');
        $xa = troca($xa, ' E ', ' e ');
        $xa = troca($xa, ' Em ', ' e ');
        $xa = troca($xa, ' Para ', ' para ');
    }

    ////////////////////////////////////////////////////////////////////////////////////
    if (($tp == 17) or ($tp == 18)) 
    {
        $mai = 1;
        $xa = substr($xa,0,1).strtolower(substr($xa,1,strlen($xa)));
    }  
    $xa = utf8_encode($xa);  
    return $xa;
}

function formatKMTbytes($n=0)
{
    $nn = number_format($n,1,'.',',').' bytes';
    /************* KILO bytes ************/
    if ($n >= 1024)
    {
        $n = $n / 1024;
        $nn = number_format($n,1,'.',',').'k bytes';
    }
    /************* MEGA bytes ************/
    if ($n >= 1024)
    {
        $n = $n / 1024;
        $nn = number_format($n,1,'.',',').'M bytes';
    }
    /************* GIGA bytes ************/
    if ($n >= 1024)
    {
        $n = $n / 1024;
        $nn = number_format($n,1,'.',',').'G bytes';
    }
    /************* TERA bytes ************/
    if ($n >= 1024)
    {
        $n = $n / 1024;
        $nn = number_format($n,1,'.',',').'T bytes';
    }
    return($nn);
}
function check_dir($dir) {
    if (is_dir($dir)) {
        return (1);
    } else {
        mkdir($dir);
        $rlt = fopen($dir . '/index.php', 'w+');
        fwrite($rlt, '<TT>Acesso negado</tt>');
        fclose($rlt);

        /* conteudo do arquivo */
        $content = 'deny from all';

        /* Bloqueia todo acesso */
        $rlt = fopen($dir . '/.htaccess', 'w');
        fwrite($rlt, $content);
        fclose($rlt);
    }
}
function redirect2($url,$time=0)
{

    if ($time <= 0)
    {
        redirect($url);
    } else {
        $sx = '
        <a href="'.$url.'"><span id="countdown" class="btn btn-secondary">'.msg('return_in').' '.($time+1).'s</span></a>
        <script>
        var count = '.$time.';
        var countdown = setInterval(
        function() { 
            html = "'.msg('return_in').'";
            $("#countdown").html(html + " " + count + "s");
            if (count == 1) 
            {
              clearInterval(countdown);
              window.open(\''.$url.'\', "_self");
          }
          count--;
          }, 1000);
          </script>     
          ';
          return($sx);
      }
  }
  function romano($n)
  {
    $r = '';
    $u = array('','I','II','III','IV','V','VI','VII','VII','IX');
    $d = array('','X','XX','XXX','XL','L','LX','LXX','LXXX','XC');
    $c = array('','C','CC','CCC','CD','D,','DC','DCC','DCCC','CM');
    $m = array('','M','MM','MMM');

    if ($n < 3000)
    {
        $v1 = round(substr($n,strlen($n)-1,1));
        $r .= $u[$v1];
        $n = substr($n,0,strlen($n)-1);
        if (strlen($n) > 0)
        {
            $v1 = round(substr($n,strlen($n)-1,1));
            $r = $d[$v1].$r;
            $n = substr($n,0,strlen($n)-1);                        
        }
        if (strlen($n) > 0)
        {
            $v1 = round(substr($n,strlen($n)-1,1));
            $r = $c[$v1].$r;
            $n = substr($n,0,strlen($n)-1);                        
        }                
        if (strlen($n) > 0)
        {
            $v1 = round(substr($n,strlen($n)-1,1));
            $r = $m[$v1].$r;
            $n = substr($n,0,strlen($n)-1);                        
        }
    } else {
        $r = 'ERRO '.$n;
    }
    return($r);               
}

function isbn10to13($isbn)
{
   $isbn = trim($isbn);
 if(strlen($isbn) == 12){ // if number is UPC just add zero
   $isbn13 = '0'.$isbn;}
   else
   {
      $isbn2 = substr("978" . trim($isbn), 0, -1);
      $sum13 = genchksum13($isbn2);
      $isbn13 = "$isbn2$sum13";
  }
  return ($isbn13);
}

function isbn13to10($isbn) {
    if (preg_match('/^\d{3}(\d{9})\d$/', $isbn, $m)) {
        $sequence = $m[1];
        $sum = 0;
        $mul = 10;
        for ($i = 0; $i < 9; $i++) {
            $sum = $sum + ($mul * (int) $sequence{$i});
            $mul--;
        }
        $mod = 11 - ($sum%11);
        if ($mod == 10) {
            $mod = "X";
        }
        else if ($mod == 11) {
            $mod = 0;
        }
        $isbn = $sequence.$mod;
    }
    return $isbn;
}

function genchksum13($isbn)
{
   $isbn = trim($isbn);
   $tb = 0;
   for ($i = 0; $i <= strlen($isbn); $i++)
   {
      $tc = substr($isbn, -1, 1);
      $isbn = substr($isbn, 0, -1);
      $ta = ($tc*3);
      $tci = substr($isbn, -1, 1);
      $isbn = substr($isbn, 0, -1);
      $tb = $tb + $ta + $tci;
  }

  $tg = ($tb / 10);
  $tint = intval($tg);
  if ($tint == $tg) { return 0; }
  $ts = substr($tg, -1, 1);
  $tsum = (10 - $ts);
  return $tsum;
} 

function message($l,$t=0)
{    
    $sx = '';
    $cl = array('success','primary','secondary','danger','warning','info','light','dark');
    $class = "alert-".$cl[$t];
    $sx .= '</br>
    <div class="alert '.$class.'" role="alert">
    ' . $l . '
    </div>';
    return($sx);
}
function refresh($url='',$time=0)
{
    if ($url=='')
    {
        $sx = '<meta http-equiv="refresh" content="'.$time.'" />';
    } else {
        $sx = '<meta http-equiv="refresh" content="'.$time.';url='.$url.'" />';
    }

    return($sx);
}

function age($data)
{
    // Separa em dia, mês e ano
    $data = sonumero($data);
    $dia = substr($data,6,2);
    $mes = substr($data,4,2);
    $ano = substr($data,0,4);

    // Descobre que dia é hoje e retorna a unix timestamp
    $hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));

    // Descobre a unix timestamp da data de nascimento do fulano
    $nascimento = mktime( 0, 0, 0, $mes, $dia, $ano);

    // Depois apenas fazemos o cálculo já citado :)
    $idade = floor((((($hoje - $nascimento) / 60) / 60) / 24) / 365.25);        
    return($idade);
} 

function view($sx,$dt=array())
{
    $CI = &get_instance();
    $dt['content'] = $sx;
    if (!isset($dt['fluid']))
        { $dt['fluid'] = ''; }
    if (!isset($dt['title']))
        { $dt['title'] = ''; }
    $CI->load->view('screen',$dt);
    return('1');
}


function le($par)
{
    $CI = &get_instance();
    if (isset($par['id']))
    {
        $sql = "select * from ".$par['table'].' where '.$par['cp'][0][1].' = '.$par['id'];
        $rlt = $CI->db->query($sql);
        $rlt = $rlt->result_array();
        if (count($rlt) > 0)
        {
            return($rlt[0]);
        }        
    }
    return(array());
}

function row2($par=array())
{
    $CI = &get_instance();
    if ((count($par) == 0) or (!is_array($par)))
    {
        $sx = '<pre>'.cr();;
        $sx .= 'table = &lt;nome da tabela&gt;'.cr();
        $sx .= 'type = &lt;tipo de visualização&gt; ex: 0,1,2,...'.cr();
        $sx .= 'cp = Array com o campo das tabelas';
        $sx .= '</pre>'.cr();
        return($sx);
    }

    /************************************************** View *********/
    if (isset($par['id']))
    {
        if (!isset($par['order']))
        {
            $par['order'] = $par['cp'][0][1];
        }
        $sql = "select * from ".$par['table'].' where '.$par['cp'][0][1].' = '.$par['id'];
        $sql .= ' order by '.$par['order'];

        $rlt = $CI->db->query($sql);
        $rlt = $rlt->result_array();
        $sx = '<!-- Classe de produtos -->'.cr();
        $sx .= '<div class="col-md-12">';
        $sx .= '<h1>'.msg('Table').': '.$par['table'].'</h1>'.cr();
        $sx .= '<table id="fields" class="table">'.cr();
        $sx .= '<tr><th>Field</th></th>Value</th></tr>'.cr();
        $i = 0;
        foreach ($rlt[0] as $key => $value) {
            $i++;
            $sx .= '<tr>';

            $sx .= '<td align="right">';
            $sx .= (string)$key;
            $sx .= '</td>';
            $sx .= '<td><b>';
            $sx .= (string)$value;
            $sx .= '</b></td>';
            $sx .= '</tr>'.cr();
        }
        $sx .= '</table>'.cr();
        $sx .= '</div>';
        return($sx);
    }        


    /************************************************** Row **********/
    if (isset($par['cp']))        
    {
        $pag = round(get("pag"));
        $order = get("order");
        $filt = get("filter");
        $filter = '';
        if ((strlen($order) == 0) and (count($par['cp']) > 0)) 
        { 
            $order = 'order by '.(string)$par['cp'][1][1]; 
        }
        $cp = $par['cp'];
        $limit = 'limit 50';
        $cps = '';
        foreach ($cp as $key => $value) {
            if ((strlen($value[5]) > 0) or ($cps == ''))
            {
                if (strlen($cps) > 0) 
                { 
                    $cps .= ', '; 
                    if (strlen($filt) > 0) 
                    { 
                        $filter .= ' OR '; 
                    }
                }
                $cps .= $value[1];
                if (strlen($filt) > 0)
                {
                    $filter .= '('.$value[1] .' like \'%'.$filt.'%\')';
                }
            }
        }
        /* Caso a tabela esteja vazia */
        if (count($cp) == 0)
        {
            $cps = '*';
        }
        /*************************** QUERY *****************/
        if (strlen($filter) > 0)
        { 
            $where = ' where ('.$filter.')'; 
        } else {
            $where = '';
        }

        if (isset($par['where']))
        {
            if (strlen($where) > 0) 
            {
                $where .= ' AND ';
            } else {
                $where = ' where ';
            }
            $where .= ' ('.$par['where'].')';
        }

        $sql = "select $cps 
        from ".$par['table']."
        $where 
        $order
        $limit ";

        /************************* EXECUTA QUERY *************/
        $rlt = $CI->db->query($sql);
        $rlt = $rlt->result_array();
        $sx = '<table class="table">';
        /* Filter */
        $sx .= '<tr>';
        $sx .= '<td colspan=10><form method="get">
        <div class="input-group mb-3">
        <input type="text" name="filter" class="form-control" placeholder="'.msg("name_to_filter").'" aria-label="'.msg("name_to_filter").'" aria-describedby="basic-addon2">
        <div class="input-group-append">
        <input type="submit" value="'.msg("bt_filter").'" class="input-group-text" id="basic-addon2">
        </div>

        <div class="input-group-append">
        <a href="'.$par['path'].'edit/0'.'"class="input-group-text" id="basic-addon3">Novo Registro</a>
        </div>

        </div>
        </form></td>';
        $sx .= '</tr>';
        /* header */
        $sx .= '<tr>';
        foreach ($cp as $key => $value) {   
            if ($value[5] == true)                 
            {
                $cps = trim((string)$value[1]);
                $sx .= '<th>'.msg($cps).'</th>';
            }
        }
        $sx .= '</tr>';
        /* Datas */            
        for ($r=0;$r < count($rlt);$r++)
        {
            $line = $rlt[$r];
            $link = '<a href="'.$par['path'].'view/'.$line[$cp[0][1]].'">';
            $linka = '</a>';
            $sx .= '<tr>';

            foreach ($cp as $key => $value) {   
                if ($value[5] == true)                 
                {                    
                    $value = $line[$value[1]];
                    $sx .= '<td>'.$link.$value.$linka.'</td>';                   
                }
            }
            $sx .= '</tr>';

        }
        $sx .= '</table>';
        return($sx);
    }

    /************* Sem os campos *******************************************/
    if (!isset($par['cp']))
    {
        $sql = "select * from ".$par['table'].' limit 1';
        $rlt = $CI->db->query($sql);
        $rlt = $rlt->result_array();
        $sx = '<!-- Classe de produtos -->'.cr();
        $sx .= '<div class="col-md-12">';
        $sx .= '<h1>'.msg('Table').': '.$par['table'].'</h1>'.cr();
        $sx .= '<table id="fields" class="table">'.cr();
        $sx .= '<tr><th>Field</th></th>Value</th></tr>'.cr();
        $i = 0;
        foreach ($rlt[0] as $key => $value) {
            $i++;
            $sx .= '<tr>';

            $sx .= '<td>'.$i.'</td>';
            $sx .= '<td>';
            $sx .= (string)$key;
            $sx .= '</td>';
            $sx .= '<td>';
            $sx .= (string)$value;
            $sx .= '</td>';

            $sx .= '<td>';

            if ($i == 1) {
                $sx .= 'array_push($cp,array(\'$H8\',"'.$key.'","'.msg($key).'",True,True,True)); ';
            } else {
                $sx .= 'array_push($cp,array(\'$S100\',"'.$key.'","'.msg($key).'",True,True,True)); ';
            }
            $sx .= '</td>';

            $sx .= '</tr>'.cr();
        }
        $sx .= '</table>'.cr();
        $sx .= '</div>';
        return($sx);
    }

} 