<!--- SPOT #1 --->
<?php
global $lang;
?>
<!-- Container element -->
<div style="background-color:#ffffff; padding: 20px;">
	<div class="container">
		<div class="row">
			<div class="col-md-10">
				<h3><?php echo msg('presentation_thesa');?></h3>
				<p>
					<?php echo msg('presentation_thesa_1');?>
				</p>
				<p>
					<?php echo msg('presentation_thesa_2');?>
				</p>
				<p>
					<?php echo msg('presentation_thesa_3');?>
				</p>
				<p>
					<?php echo msg('presentation_thesa_4');?>
				</p>
			</div>
			<div class="col-md-2">
				<a class="btn btn-outline-primary" style="width: 100%;" href="<?php echo base_url(PATH.'thesaurus_open');?>"><?php echo msg('open_thesauros');?></a>
				<? if (isset($_SESSION['user'])) { ?>
				<br/><br/>
				<a class="btn btn-outline-primary" style="width: 100%;" href="<?php echo base_url(PATH.'thesaurus_my');?>"><?php echo msg('my_thesauros');?></a>
				<?php } ?>
			</div>
		</div>
	</div>
</div>

<div class="parallax parallax_background_2"  style="padding: 100px;">
	<div class="container">
		<div class="row">
			<div class="col-md-3 text-center box">
			    <span class="small"><b><?php echo msg("thesauros");?></b></span>
			    <br>
				<span class="big"><b><?php echo number_format($nr_thesaurus,0,',','.'); ?></b></span>
			</div>
			<div class="col-md-3 text-center box">
			    <span class="small"><b><?php echo msg("concepts");?></b></span>
			    <br>
				<span class="big"><b><?php echo number_format($nr_concept,0,',','.'); ?></b></span>
			</div>
			<div class="col-md-3 text-center box">
			    <span class="small"><b><?php echo msg("terms");?></b></span>
			    <br>
				<span class="big"><b><?php echo number_format($nr_terms,0,',','.'); ?></b></span>
			</div>
		</div>
	</div>
</div>

<!-- Container element -->
<div style="background-color:#ffffff; padding: 20px;">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h3><?php echo msg('about_thesa');?></h3>
				<p>
					<?php echo msg('about_thesa_1');?>
				</p>

				<p>
					<?php echo msg('about_thesa_2');?>
				</p>

				<p>
					<?php echo msg('about_thesa_3');?>
				</p>
			</div>
		</div>
	</div>
</div>