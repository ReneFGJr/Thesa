<?php
echo cr();
echo '<!--- div start --->'.cr();

if (!isset($class)) { $class = ''; }
if (isset($br) and ($br == true)) { echo '<br>'; }
if (isset($fluid) == 1) {
    echo '<div class="container-fluid '.$class.'">' . cr();
} else {
    echo '<div class="container" class="'.$class.'">' . cr();
}
/*********** TITLE ************/
if (isset($title) and (strlen($title) > 0)) {
    echo '<h1>' . $title;
    if (isset($submenu)) {
        echo '<br>' . $submenu . '</br>';
    }
    echo '</h1>';
}
/************ CONTENT *************/
echo $content;
echo '</div>';

echo cr();
echo '<!--- div stop --->'.cr();
echo cr();
?>
