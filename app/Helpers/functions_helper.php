<?php
function my_reasearchs($id)
{
    $Mark = new \App\Models\Base\Mark();
    $sx = $Mark->listMark($id);
    return $sx;
}
