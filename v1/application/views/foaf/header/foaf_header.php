<?php
if (!isset($title)) { $title = 'EasyLibrary';}
?>
<header>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title><?php echo $title;?></title>
	
	<!-- STYLES -->
	<link href=<?php echo base_url('css/bootstrap.css');?> rel=stylesheet>
	<link href=<?php echo base_url('css/style_foaf.css');?> rel=stylesheet>
	
	<!--- JAVASCRIPT -->
	
	<script src=<?php echo base_url('js/jquery-2.0.0.js');?>></script>
	<script src=<?php echo base_url('js/bootstrap.js');?>></script>
	<script src=<?php echo base_url('js/js_windows.js');?>></script>
	<script src=<?php echo base_url('js/animatescroll.min.js'); ?>></script>
	
	
	<!--- ICONE --->
	<link rel="shortcut icon" href="<?php echo base_url('img/icone/favicon.png');?>">
	<link href="<?php echo base_url('img/icone/favicon.ico');?>" rel="icon" type="image/x-icon"/>
</header>