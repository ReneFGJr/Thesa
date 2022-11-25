<?php
function dif_date($d1,$d2)
    {

    $di = new DateTime($d1);
    $df = new DateTime($d2);

    // Resgata diferença entre as datas
    $dateInterval = $di->diff($df);
    echo $dateInterval->days;
    }

function mes_extenso($xmes)
    {
        $xmes = round($xmes);
        $mes = array('janeiro','fevereiro','março','abril','maio','junho','julho','agosto','setembro','outubro','novembro','dezembro');
        if (($xmes > 0) and ($xmes < 13))
            {
                return $mes[$xmes-1];
            } else {
                return 'ERRO MES:'.$xmes;
            }
    }

function mes_dia($data)
    {
        $dia = sonumero($data);
        $dia = round(substr($data,6,2));
        if ($dia == 1) { $dia = '1º'; }
        return $dia;
    }

function range_data($d1,$d2)
    {
        $sx = '';
        $d1 = sonumero($d1);
        $d2 = sonumero($d2);

        if (($d1=='20990101') or ($d1=='19000101'))
            {
                return "";
            }

        $dia1 = mes_dia($d1);
        $dia2 = mes_dia($d2);

        if ($dia1=='0') { return ""; }

        if (substr($d1, 0, 8) == substr($d2, 0, 8))
            {
                $sx .= $dia1.' de '.mes_extenso(substr($d1, 4, 2)).' de '.substr($d1, 0, 4);
            } else {
                if (substr($d1,0,6) == substr($d1,0,6))
                    {
                        $sx .= $dia1.' a '. $dia2.' de '.mes_extenso(substr($d1,4,2)).' de '.substr($d1,0,4);
                    } else {
                        if (substr($d1,0,4) == substr($d1,0,4))
                            {
                                $sx .= 'r2';
                                $sx .= $dia1 . ' de ' . substr($d1, 0, 4).' a '. $dia2 . ' de ' . substr($d2, 0, 4) . ' de ' . substr($d1, 0, 4);
                            } else {
                                $sx .= 'r3';
                                $sx .= $d1.' a '.$d2;
                            }
                    }
            }
            return $sx;
    }

function brtos($dt)
{
    $dt = sonumero($dt);
    $dt = substr($dt, 4, 4) . substr($dt, 2, 2) . substr($dt, 0, 2);
    return $dt;
}

function stodbr($dt)
{
    $dt = sonumero($dt);
    $rst = substr($dt, 6, 2) . '/' . substr($dt, 4, 2) . '/' . substr($dt, 0, 4);
    return $rst;
}

function stodus($dt)
{
    $dt = sonumero($dt);
    $rst = substr($dt, 0, 4) . '-' . substr($dt, 4, 2) . '-' . substr($dt, 6, 2);
    return $rst;
}