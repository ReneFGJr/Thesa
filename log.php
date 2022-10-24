<?php
echo 'PHP version: ' . phpversion();
echo '<hr>';

$file = 'writable/logs/log-2022-10-24.log';
if (file_exists($file))
{
    $txt = file_get_contents($file);
    echo '<pre>' . $txt . '</pre>';
    unlink($file);
} else {
    echo "Log not found";
}
