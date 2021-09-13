<?php

function breadcrumb()
{
	$path = $_SERVER['REQUEST_URI'];
	$a = array(0,1,2,3,4,5,6,7,8,9);
	for ($r=0;$r < count($a);$r++)
	{
		$path = troca($path,$a[$r],'');
	}

	/****************************************************************/
	$sx = '
	<div class="row">
	<nav aria-label="breadcrumb" style="margin-top: 20px;">
	<ol class="breadcrumb">
	<li class="breadcrumb-item"><a href="'.base_url(PATH.'/').'">Home</a></li>'.cr();

	/*************************************** Monta estrutura *********/
	$path = substr($path,strpos($path,'main/')+5,strlen($path)).'/';
	$ph = '';
	while (strpos(' '.$path,'/') > 0)
	{
		$link = substr($path,0,strpos($path,'/'));
		$ph .= $link.'/';
		if ((trim($link) != '/') and (strlen($link) > 0))
		{
			$sx .= '<li class="breadcrumb-item"><a href="'.base_url(PATH.$ph).'">'.msg($link).'</a></li>'.cr();
		}
		$path = substr($path,strpos($path,'/')+1,strlen($path));
	}
	/*****************************************************************/
	$sx .= '</ol></nav>
	</div>
	</div>
	';
	return($sx);
}

/**************** IMAGEMS ************************************************/
function image_resize($file, $w, $h, $crop=FALSE) {
	list($width, $height) = getimagesize($file);
	$r = $width / $height;
	if ($crop) {
		if ($width > $height) {
			$width = ceil($width-($width*abs($r-$w/$h)));
		} else {
			$height = ceil($height-($height*abs($r-$w/$h)));
		}
		$newwidth = $w;
		$newheight = $h;
	} else {
		if ($w/$h > $r) {
			$newwidth = $h*$r;
			$newheight = $h;
		} else {
			$newheight = $w/$r;
			$newwidth = $w;
		}
	}
	$src = imagecreatefromjpeg($file);
	$dst = imagecreatetruecolor($newwidth, $newheight);
	imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

	imagejpeg($dst, $file, 100);
	imagedestroy($dst);
	return 1;
} 
function png2jpg($originalFile, $outputFile, $quality) {
	$image = imagecreatefrompng($originalFile);
	imagejpeg($image, $outputFile, $quality);
	imagedestroy($image);
}
?>