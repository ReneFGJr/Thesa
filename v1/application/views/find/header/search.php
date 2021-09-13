<div class="container" style="margin-top: 100px;">
	<div class="row">
		<div class="col-sm-12">
			<?php echo msg('catalog_search'); ?>
			<?php echo form_open(); ?>
			<div class="input-group" style="padding: 5px; ">
				<input type="text" class="form-control selector" name="dd2" id="search" placeholder="Busca por..." value="<?php echo $dd2;?>">
				<span class="input-group-btn">
					<input type="submit" class="btn btn-secondary" value="pesquisar">
				</span>
			</div>
			</form>
		</div>
	</div>
</div>
