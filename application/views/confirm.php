<form method="post" action="<?php echo $link;?>">
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<?php
			if (isset($content)) { echo $content;
			}
			?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<hr>
			<input type="submit" class="btn btn-primary" value="Confirmar">
			<input type="hidden" value="1" name="confirm">
			<input type="hidden" value="<?php echo get("dd0");?>" name="dd0">
			<button class="btn btn-default" onclick="wclose();">Cancelar</button>
		</div>
	</div>
</div>
</form>