<?php
if (!isset($action))
{
	$action = '';
}
?>
<div class="container" style="margin-top: 30px;">
	<div class="row">
		<div class="col-md-10"><?php echo msg('term'); ?>
		    
		<br/>
		  <font class="xxxbig"><h1><?php echo $rl_value; ?></h1></font>
		</div>
		<div class="col-md-2"><?php echo $action;?></div>
	</div>
</div>
