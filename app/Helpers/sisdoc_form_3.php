<?php

function read_link($url, $read = 'CURL')
{
    switch ($read) {
        case 'file':
            if (substr($url, 0, 4) == 'http') {
                $headers = get_headers($url);
                $sta = sonumero($headers[0]);
                $sta = substr($sta, strlen($sta) - 3, 3);
            } else {
                $sta = '200';
            }

            if ($sta != '404') {
                $contents = file_get_contents($url);
            } else {
                $contents = '';
            }
            break;

            /******************************************************** CURL ******************/
        default:
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, false);
            $data = curl_exec($curl);
            curl_close($curl);
            return ($data);

            break;
    }
    return ($contents);
}

function menu($menu)
    {
        $sx = '<ul>';
        foreach($menu as $link=>$name)
            {
                $sx .= '<li><a href="' . $link . '">' . $name . '</a></li>';
            }
        $sx .= '</ul>';
        return $sx;
    }

function check_email($email)
    {
            $emailArray = explode("@", $email);
            if (checkdnsrr(array_pop($emailArray), "MX")) {
                return true;
            } else {
                return false;
            }        
    }

function pre($dt,$force=true)
{
    echo '<pre>';
    print_r($dt);
    echo '</pre>';
    if ($force) { exit; }
}

if (!function_exists("current_url")) {
    function current_url()
    {
        $url = getenv('app.baseURL');
        return $url;    
    }
}

if (!function_exists("site_url")) {
    function site_url()
    {
        $url = getenv('app.baseURL');
        return $url;    
    }
}


function hexdump($string)
{
    $sx = '';
    for ($i = 0; $i < strlen($string); $i++) {
        $sx .= str_pad(dechex(ord($string[$i])), 2, '0', STR_PAD_LEFT);
        $sx .= ' ';
    }
    return $sx;
}

function geturl()
    {
        $path = $_SERVER['REQUEST_URI'];
        if (strlen($path) == 0) { $path = $_SERVER['PATH_INFO']; }
        if (strlen($path) == 0) { $path = $_SERVER['PHP_SELF']; }
        return $path;
    }

function romano($n)
{
    $n = sonumero($n);
    $r = '';
    $u = array('', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VII', 'IX');
    $d = array('', 'X', 'XX', 'XXX', 'XL', 'L', 'LX', 'LXX', 'LXXX', 'XC');
    $c = array('', 'C', 'CC', 'CCC', 'CD', 'D,', 'DC', 'DCC', 'DCCC', 'CM');
    $m = array('', 'M', 'MM', 'MMM');

    if ($n < 3000) {
        $v1 = round(substr($n, strlen($n) - 1, 1));
        $r .= $u[$v1];
        $n = substr($n, 0, strlen($n) - 1);
        if (strlen($n) > 0) {
            $v1 = round(substr($n, strlen($n) - 1, 1));
            $r = $d[$v1] . $r;
            $n = substr($n, 0, strlen($n) - 1);
        }
        if (strlen($n) > 0) {
            $v1 = round(substr($n, strlen($n) - 1, 1));
            $r = $c[$v1] . $r;
            $n = substr($n, 0, strlen($n) - 1);
        }
        if (strlen($n) > 0) {
            $v1 = round(substr($n, strlen($n) - 1, 1));
            $r = $m[$v1] . $r;
            $n = substr($n, 0, strlen($n) - 1);
        }
    } else {
        $r = 'ERRO ' . $n;
    }
    return ($r);
}

function brtos($dt)
{
    $dt = sonumero($dt);
    $dt = substr($dt, 4, 4) . substr($dt, 2, 2) . substr($dt, 0, 2);
    return $dt;
}

function df($N, $pre = '', $pos = '')
{
    if (defined($N) == true) {
        $var = constant($N);
        $var = $pre . $var . $pos;
        return $var;
    }
    return '';
}
