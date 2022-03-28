<?php
function onclick($url,$x=800,$y=800,$class="")
{
    $a = '<a href="#" ';
    $a .= ' class="'.$class.'" ';
    $a .= ' onclick = "';
    $a .= 'NewWindow=window.open(\''.$url.'\',\'newwin\',\'scrollbars=no,resizable=no,width='.$x.',height='.$y.',top=10,left=10\'); ';  
    $a .= 'NewWindow.focus(); void(0); ';
    $a .= '">';

    $a = '<span ';
    $a .= ' class="'.$class.'" ';
    $a .= ' onclick = "';
    $a .= 'NewWindow=window.open(\''.$url.'\',\'newwin\',\'scrollbars=no,resizable=no,width='.$x.',height='.$y.',top=10,left=10\'); ';  
    $a .= 'NewWindow.focus(); void(0); ';
    $a .= '" style="cursor: pointer;">';
    return $a;
}

function newwin($url,$x=800,$y=600)
    {
    $a = '';
    $a .= 'NewWindow=window.open(\''.$url.'\',\'newwin\',\'scrollbars=no,resizable=no,width='.$x.',height='.$y.',top=10,left=10\'); ';  
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

function wclose($tp='')
    {
        if ($tp != '')
            {
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
