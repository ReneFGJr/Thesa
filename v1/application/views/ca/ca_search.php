<div class="container-fluid bg_thesa nopr" style="margin-top: 50px;">
	<div class="row" style="height: 70px;">
		<div class="col-md-12">
			<form method="post" action="<?php echo base_url('index.php/authority/search/');?>">
			<?php echo msg('authority_search');?>
			<div class="input-group" style="padding: 5px; ">
				<input type="text" class="form-control selector" name="dd1" id="search" placeholder="Busca por..." value="">
				<span class="input-group-btn">
					<input type="submit" class="btn btn-secondary" value="pesquisar">
				</span>
			</div>
			</form>
		</div>
	</div>
</div>