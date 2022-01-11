<?php
function romano($n)
{
    $n = sonumero($n);
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