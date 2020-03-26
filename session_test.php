<?php
session_start();

// makes an array
$colors=array('<font color="red">red</font>', 'yellow', '<font color="blue">blue</font>');
// adds it to our session
$_SESSION['color']=$colors;
//$_SESSION['size']='small';
//$_SESSION['shape']='round';
?>

sess2.php with the following code:

<?php
// this starts the session
session_start();

// echo variable from the session, we set this on our other page
echo "<br>Our color value is ".$_SESSION['color'][2];
echo "<br>Our size value is ".$_SESSION['size'];
echo "<br>Our shape value is ".$_SESSION['shape'];
?>