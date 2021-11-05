<?php
function onclick($url,$x=800,$y=800,$class="")
{
    $a = '<a href="#" ';
    $a .= ' class="'.$class.'" ';
    $a .= ' onclick = "';
    $a .= 'NewWindow=window.open(\''.$url.'\',\'newwin\',\'scrollbars=no,resizable=no,width='.$x.',height='.$y.',top=10,left=10\'); ';  
    $a .= 'NewWindow.focus(); void(0); ';
    $a .= '">';
    return $a;
}

function wclose()
    {
        $a = '
            <script>
	            window.opener.location.reload();
	            close(); 		
            </script>
 	        ';
        return $a;
    }