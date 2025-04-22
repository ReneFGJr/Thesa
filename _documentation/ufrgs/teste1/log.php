<?php
$file = '../writable/logs/log-'.date("Y-m-d").'.log';

echo 'PHP version: ' . phpversion();
echo '<br>'.$file;
echo '<hr>';


if (file_exists($file))
{
    $txt = file_get_contents($file);
    echo '<pre>' . $txt . '</pre>';
    unlink($file);
} else {
    echo "Log not found";
}
