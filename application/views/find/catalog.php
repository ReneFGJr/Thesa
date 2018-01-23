<div class="container">
	<div class="row">
		<div class="col-md-1">
			<tt>WORK</tt>
		</div>
	</div>

	<div class="row">
		<div class="col-md-1">
			<tt>EXPRESSION</tt>
		</div>
	</div>

	<div class="row">
		<div class="col-md-1">
			<tt>MANIFESTATION</tt>
		</div>
	</div>

	<div class="row">
		<div class="col-md-1">
			<tt>ITEM</tt>
		</div>
	</div>

</div>

<div class="container">
	<div class="row">
		<?php echo form_open(); ?>
		<textarea name="dd2" class="form-control" style="height: 400px;"><?php echo get("dd2");?></textarea>
		<br/>
		<input type="submit" value="<?php echo msg('send');?>" class="btn btn-secondary">
		<?php echo form_close(); ?>
	</div>
</div>
