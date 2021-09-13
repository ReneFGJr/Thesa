<?php
if (!isset($content)) {
	$content = msg('success_action');
}
?>
<div class="alert alert-success" role="alert">
	<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
	<span class="sr-only">Sucesso:</span>
	<?php echo $content; ?>
</div>