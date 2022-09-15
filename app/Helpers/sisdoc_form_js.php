<?php

function jslog($txt)
{
    echo '<script> console.log("'.$txt.'"); </script>';
    return "";
}

function onclick($url, $x = 800, $y = 800, $class = "")
{
    $a = '<span ';
    $a .= ' class="' . $class . '" ';
    $a .= ' onclick = "';
    $a .= 'NewWindow=window.open(\'' . $url . '\',\'newwin\',\'scrollbars=no,resizable=no,width=' . $x . ',height=' . $y . ',top=10,left=10\'); ';
    $a .= 'NewWindow.focus(); void(0); ';
    $a .= '" style="cursor: pointer;">';
    return $a;
}

function confirm($url, $x = 800, $y = 800, $class = "")
{
    $a = '<span ';
    $a .= ' class="' . $class . '" ';
    $a .= ' onclick = "if (confirm(\'Excluir registro?\')) {';
    $a .= 'NewWindow=window.open(\'' . $url . '\',\'newwin\',\'scrollbars=no,resizable=no,width=' . $x . ',height=' . $y . ',top=10,left=10\'); ';
    $a .= 'NewWindow.focus(); void(0); }';
    $a .= '" style="cursor: pointer;">';
    return $a;
}

function btn_trash_popup($url, $class = 'text-secondary', $txt = '')
{
    $sx = '<a href="#" class="a ' . $class . ' text-danger" onclick="if (confirm(\'' . lang('brapci.exclusion?') . '\')) { NewWindow=window.open(\'' . $url . '\',\'newwin\',\'scrollbars=no,resizable=no,width=800,height=400,top=10,left=10\'); NewWindow.focus(); void(0); }" style="cursor: pointer;">' . bsicone('trash') . '</a>';
    return $sx;
}

function btn_recicle_popup($url, $class = 'text-secondary', $txt = '')
{
    $sx = '<a href="#" class="a ' . $class . ' text-success" onclick="if (confirm(\'' . lang('brapci.recicle?') . '\')) { NewWindow=window.open(\'' . $url . '\',\'newwin\',\'scrollbars=no,resizable=no,width=800,height=400,top=10,left=10\'); NewWindow.focus(); void(0); }" style="cursor: pointer;">' . bsicone('recycle') . '</a>';
    return $sx;
}

function btn_trash($url, $class = 'text-secondary', $txt = '')
{
    $sx = '<a href="' . $url . '" class="a text-danger ' . $class . '" onclick="return confirm(\'' . lang('sisdoc.exclusion?') . '\');">' . bsicone('trash') . '</a>';
    return $sx;
}
function btn_edit($url, $class = '')
{
    $sx = '<a href="' . $url . '" class="a ' . $class . '">' . bsicone('edit') . '</a>';
    return $sx;
}


function newwin($url, $x = 800, $y = 600)
{
    $a = '';
    $a .= 'NewWindow=window.open(\'' . $url . '\',\'newwin\',\'scrollbars=no,resizable=no,width=' . $x . ',height=' . $y . ',top=10,left=10\'); ';
    $a .= 'NewWindow.focus(); void(0); ';
    return $a;
}

function clipboard()
{
    $sx = '
        <script>
            function copytoclipboard($element) {
            var copyText = document.getElementById($element);
            copyText.select();
            document.execCommand("Copy");
            alert("Copied the text: " + copyText.value);
            }
        </script>';
    return $sx;
}

function wclose($tp = '')
{
    if ($tp != '') {
        $a = '
                    <script>
                        close(); 		
                    </script>
                    ';
    } else {
        $a = '
                    <script>
                        window.opener.location.reload();
                        close(); 		
                    </script>
                    ';
    }
    return $a;
}
