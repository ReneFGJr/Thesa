<?php
if (!isset($action))
{
	$action = '';
}
?>
<div class="container">
	<div class="row supersmall">
		<div class="col-md-10"><?php echo msg('term'); ?></br><font class="xxxbig"><?php echo $rl_value; ?></font></div>
		<div class="col-md-2"><?php echo $action;?></div>
	</div>
</div>
