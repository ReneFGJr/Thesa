<div class="container" style="margin-bottom: 10px;">
	<div class="row">
		<div class="col-md-6">
			<?php
			if ($edit == 1) {
				echo '<a href="' . base_url("index.php/skos/cedit/" . $id_c) . '" class="btn btn-default">editar</a>' . cr();
			}
			?>
		</div>
		
		<div class="col-md-6 text-right">
			schema: <a href="<?php echo base_url('index.php/skos/terms/' . $ct_th); ?>" class="thesa"><?php echo $pa_name; ?></a>
		</div>
	</div>
</div>

