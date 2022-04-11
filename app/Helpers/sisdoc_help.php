<?php

function help($pag='',$lang='pt')
    {
        $dir = '../_documentation/';

        $file = $pag.'-'.$lang.'.md';
        $filename = $dir.$file;
        if (!file_exists($filename)) {
            $file = $pag.'.md';
            $filename = $dir.$file;
        }

        if (!file_exists($filename))
            {
                $txt = h($pag,2);
                dircheck($dir);
                file_put_contents($dir.$file,$txt);                
            } else {
                $txt = file_get_contents($dir.$file);
            }
        $txt = troca($txt,chr(13),'<br>');
        return $txt;
    }