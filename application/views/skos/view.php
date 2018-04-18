<?php
$pa = '';
?>
<div class="container">
	<div class="row">
		<div class="col-xs-12 big">	
			<span class="thesa thesa_under" style="font-size: 120%;"><b><?php echo $pa_name; ?></b></span>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-8 big">
		<a href="<?php echo base_url('index.php/thesa/terms/'.$id_pa.'#'.$pa_name); ?>" class="supersmall">
		    <span class="small"><?php echo 'thesa:'.'th/'.$id_pa.'#'.$pa_name; ?></span></a>
		</div>
		<div class="col-xs-4 col-md-4 text-right">
			<span style="font-size: 70%"><?php echo msg('author'); ?>:</span>
		</div>
	</div>
</div>
<br>
<div class="container">
	<div class="row">
		<div class="col-md-2"><span class="supersmall"><?php echo msg('linguage'); ?></span>
		    <br><font class="middle"><?php echo $pa; ?></font></div>
		<div class="col-md-2"><span class="supersmall"><?php echo msg('created'); ?></span>
		    <br><font class="middle"><?php echo stodbr($pa_created); ?></font></div>
		<div class="col-md-2"><span class="supersmall"><?php echo msg('updated'); ?></span>
		    <br><font class="middle"><?php echo stodbr($pa_update); ?></font></div>
		<div class="col-md-6"><span class="supersmall"><?php echo msg('URI'); ?></span>
		    <br><font class="middle">
		    <?php echo base_url('index.php/thesa/terms/'.$id_pa); ?></font></div>
	</div>
</div>
<br>