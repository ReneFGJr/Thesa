<?php
function dif_date($d1,$d2)
    {

    $di = new DateTime($d1);
    $df = new DateTime($d2);

    // Resgata diferença entre as datas
    $dateInterval = $di->diff($df);
    echo $dateInterval->days;
    }

function stodbr($dt)
{
    $dt = sonumero($dt);
    $rst = substr($dt, 6, 2) . '/' . substr($dt, 4, 2) . '/' . substr($dt, 0, 4);
    return $rst;
}