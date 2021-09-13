<?php
function bs_pages($ini,$stop,$link='')
    {
        $sx = '';
        $sx .= '<nav aria-label="Page navigation example">'.cr();
        $sx .= '<ul class="pagination">'.cr();
        for ($r=$ini;$r <= $stop;$r++)
            {
                $xlink = base_url($link.'/'.chr($r));
                $sx .= '<li class="page-item"><a class="page-link" href="'.$xlink.'">'.chr($r).'</a></li>'.cr();
            }
        $sx .= '</ul>';
        $sx .= '</nav>';
        return($sx);
    }
function bs_alert($type = '', $msg = '') {
    $ok = 0;
    switch($type) {
        case 'success' :
            $ok = 1;
            break;
        case 'secondary' :
            $ok = 1;
            break;
        case 'danger' :
            $ok = 1;
            break;
        case 'warning' :
            $ok = 1;
            break;
        case 'info' :
            $ok = 1;
            break;
        case 'light' :
            $ok = 1;
            break;
        case 'dark' :
            $ok = 1;
            break;
        default :
            $sx = 'TYPE: primary, secondary, success, danger, warning, info, light, dark';
    }
    if ($ok == 1) {
        $sx = '<br><div class="alert alert-' . $type . '" role="alert">
                ' . $msg . '
               </div>' . cr();
    }
    return($sx);
}
?>