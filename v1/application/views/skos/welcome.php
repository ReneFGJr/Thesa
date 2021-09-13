<link href="https://fonts.googleapis.com/css?family=Roboto|Unica+One" rel="stylesheet">
<style>
</style>
<div class="container-fluid" id="welcome">
	<div class="row" style="margin-top: 10%;">
		<div class="col-md-12 text-center">
			<span class="logo_title"><span class="glyphicon glyphicon-tags" aria-hidden="true" style="font-size: 80%;"></span> THESA</span>			
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 text-center">
			<span class="logo_subtitle">Tesauro Semântico Aplicado</span>			
		</div>
	</div>	
	<!--
	<div class="row" style="padding-top: 50px;">
		<div class="col-md-12 text-center">
			<a href="<?php echo base_url('index.php/thesa/myskos');?>" class="btn btn-secondary">ENTRAR</a>			
		</div>
	</div>
	-->
</div>
<br/>
<br/>
<br/>
<div class="container-fluid" style="padding: 30px; border: 1px 0px 1px 0px; background-color: #afafff;">
<div class="container">	
	<div class="row">
		<div class="col-xs-4 col-md-2 col-md-offset-2 text-center">
			<div style="padding: 7px; border-radius: 60px; background-color: #dfdfff;">
				<h1><?php echo number_format($nr_thesaurus, 0, '.', '.'); ?></h1>
				<?php echo msg('thesaurus'); ?>
			</div>
		</div> 
		<div class="col-xs-4 col-md-2 col-md-offset-1 text-center">
			<div style="padding: 7px; border-radius: 60px; background-color: #dfdfff;">
				<h1><?php echo number_format($nr_users, 0, '.', '.'); ?></h1>
				<?php echo msg('users'); ?>
			</div>
		</div>
		<div class="col-xs-4 col-md-2 col-md-offset-1 text-center">
			<div style="padding: 7px; border-radius: 60px; background-color: #dfdfff;">
				<h1><?php echo number_format($nr_concept, 0, '.', '.'); ?></h1>
				<?php echo msg('concepts'); ?>
			</div>
		</div>	
		<!--
		<div class="col-md-2 col-md-offset-1 text-center">
			<div style="padding: 7px; border-radius: 60px; background-color: #dfdfff;">
				<h1><?php echo number_format($nr_terms,0,'.','.');?></h1>
				<?php echo msg('terms');?>
			</div>
		</div>
		-->				
		
	</div>	
</div>
</div>

<div style="height: 600px;"></div>

