<?php
if (isset($br) and ($br == true)) { echo '<br>'; }
if (isset($fluid) == 1) {
	echo '<div class="container-fluid">' . cr();
} else {
	echo '<div class="container">' . cr();
}
/*********** TITLE ************/
if (isset($title)) {
	echo '<h1>' . $title;
	if (isset($submenu)) {
		echo '<br>' . $submenu . '</br>';
	}
	echo '</h1>';
}
/************ CONTENT *************/
echo $content;
echo '</div>';
?>
