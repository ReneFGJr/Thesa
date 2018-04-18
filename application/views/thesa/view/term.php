<?php
if (!isset($action))
{
	$action = '';
}
?>
<div class="container" style="margin-top: 30px;">
	<div class="row">
		<div class="col-md-10"><h4><?php echo msg('term'); ?></h4></br><font class="xxxbig"><?php echo $rl_value; ?></font></div>
		<div class="col-md-2"><?php echo $action;?></div>
	</div>
</div>
