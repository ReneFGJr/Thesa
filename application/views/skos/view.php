<?php
$pa = '';
?>
<div class="container">
	<div class="row">
		<div class="col-xs-8 big">	
			<span class="thesa thesa_under" style="font-size: 120%;"><b><?php echo $pa_name; ?></b></span>
		<br>
		<a href="<?php echo base_url('index.php/skos/th/'.$id_pa.'#'.$pa_name); ?>"><span style="font-size: 40%;"><?php echo base_url('index.php/skos/th/'.$id_pa.'#'.$pa_name); ?></span></a>
		</div>
		<div class="col-xs-4 text-right">
			<span style="font-size: 70%"><?php echo msg('author'); ?>:</span>
		</div>
	</div>
</div>
<br>
<div class="container">
	<div class="row">
		<div class="col-md-3 supersmall"><?php echo msg('linguage'); ?><br><font class="middle"><?php echo $pa; ?></font></div>
		<div class="col-md-3 supersmall"><?php echo msg('created'); ?><br><font class="middle"><?php echo stodbr($pa_created); ?></font></div>
		<div class="col-md-3 supersmall"><?php echo msg('updated'); ?><br><font class="middle"><?php echo stodbr($pa_update); ?></font></div>
	</div>	

	<div class="row">
		<div class="col-xs-12 supersmall"><?php echo msg('URI'); ?><br><font class="middle"><?php echo base_url('index.php/skos/'); ?></font></div>
	</div>
</div>
<br>