<?php
/**
* CodeIgniter Form Helpers
*
* @package     CodeIgniter
* @subpackage  NBR SisDoc
* @category    Helpers
* @author      Rene F. Gabriel Junior <renefgj@gmail.com>
* @link        http://www.sisdoc.com.br/CodIgniter
* @version     v0.21.08.26
*/
//$sx .= form($url,$dt,$this);

function nbr_title($t)
    {
        $prep = array();
        $file = '../.temp/oa/prepositions-pt-BR.php';
        if (file_exists($file))    { eval(file_get_contents($file)); }

        $country = array();
        $file = '../.temp/oa/country-pt-BR.php';
        if (file_exists($file))    { eval(file_get_contents($file)); }
            
        $tl = mb_strtolower($t);
        $tu = mb_strtoupper($t);

        $wdl = explode(' ',$tl);
        $wdu = explode(' ',$tu);

        $sx = '';
        $rl = 0;
        for ($r=0;$r < count($wdl);$r++)
            {
                if ($rl == 0)
                    {
                        if (in_array($wdl[$r],$prep))
                            {
                                $sx .= $wdl[$r];
                            } else {
                                $sx .= substr($wdu[$r],0,1).substr($wdl[$r],1,strlen($wdl[$r]));
                            }   
                            $rl = 1;                     
                    } else {
                        /* Excessões *****************************************/
                        if (in_array($wdl[$r],$country))
                            {
                                $sx .= substr($wdu[$r],0,1).substr($wdl[$r],1,strlen($wdl[$r]));
                            } else {
                                $sx .= $wdl[$r];
                            }
                        
                    }
                $sx .= ' ';
            }
        $sx = trim($sx);
        return $sx;
    }

function nbr_author($xa, $tp) 
    {
        $xa = troca($xa,'JúNIOR','JÚNIOR');
        $xa = utf8_decode($xa);    
        $xa = troca($xa,', ,',',');
    
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
        $er1 = array(utf8_decode('JÚNIOR'),"JUNIOR", "NETTO", "NETO", "SOBRINHO", "FILHO", "JR.", "JR");
        
        ///////////////////////////// SEPARA NOMES
        $xop = 0;
        for ($qk = count($xp) - 1; $qk >= 0; $qk--) 
        {
            $xa = trim($xa . ' - ' . $xp[$qk]);
    
            /* Primeira operação */
            if ($xop == 0) 
            { 
                $xp1 = trim($xp[$qk] . ' ' . $xp1);
                $xop = -1;
            } else { 
                $xp2 = trim($xp[$qk] . ' ' . $xp2);
            }  
            /* Checa os nomes */ 
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
                    if ((substr($xa, $r, 1) == ' ') or (substr($xa, $r, 1) == '-')) 
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
            $xa = troca($xa, ' Em ', ' em ');
            $xa = troca($xa, ' Para ', ' para ');
            $xa = troca($xa, ' The ', ' the ');
            $xa = troca($xa, ' And ', ' and ');
            $xa = troca($xa, ' Of ', ' of ');
            $xa = troca($xa, ' To ', ' to ');
            $xa = troca($xa, ' For ', ' for ');
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
