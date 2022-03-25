<?php 
function dif_date($d1,$d2)
    {

    $di = new DateTime($d1);
    $df = new DateTime($d2);

    // Resgata diferença entre as datas
    $dateInterval = $di->diff($df);
    echo $dateInterval->days;       
    }