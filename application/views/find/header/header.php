<?php
if (!isset($title)) { $title = 'Thesa - Semantic Thesaurus';}
?>
<header>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title><?php echo $title;?></title>
	
	<!-- STYLES -->
	<link href=<?php echo base_url('css/bootstrap.css');?> rel=stylesheet>
	<link href=<?php echo base_url('css/style.css');?> rel=stylesheet>
	<link href=<?php echo base_url('css/jquery-ui.css');?> rel=stylesheet>
	<link href=<?php echo base_url('css/style_form_sisdoc.css');?> rel=stylesheet>
	<link href=<?php echo base_url('css/find/style_find.css');?> rel=stylesheet>
	
	<!--- JAVASCRIPT -->
	
	<script src="<?php echo base_url('js/jquery-2.0.0.js');?>"></script>
	<script src="<?php echo base_url('js/jquery-ui.js');?>"></script>
	<script src="<?php echo base_url('js/bootstrap.js');?>"></script>
	<script src="<?php echo base_url('js/js_windows.js');?>"></script>
	<script src="<?php echo base_url('js/animatescroll.min.js'); ?>"></script>
	<script src="<?php echo base_url('js/clipboard.js'); ?>"></script>
	<script src="<?php echo base_url('js/autocomplete.js'); ?>"></script>
	<script>
		new Clipboard('.btnc');	
	</script>
	
	
	<!--- ICONE --->
	<link rel="shortcut icon" href="<?php echo base_url('favicon.png');?>">
	<link href="<?php echo base_url('img/icone/favicon.png');?>" rel="icon" type="image/x-icon"/>
</header>