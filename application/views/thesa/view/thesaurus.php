<style>
.parallax_background_5 {
        /* The image used */
        background-image: <?php echo 'url("' . base_url('/img/background/background_4.jpg') . '")'; ?>
            }
            .bg_cor {
                color: #ffffff;
            }
</style>
<?php
$pa = '';
?>
<div class="container-fluid parallax_background_5 parallax bg_color" style="padding: 30px;">
	<div class="row">
	    <div class="col-xs-2 col-md-2 small text-right">
	        <?php echo msg("thesauro"); ?>
	    </div>
		<div class="col-xs-8 col-md-8 big">
			<span class="thesa thesa_under" style="font-size: 120%;"><b><?php echo $pa_name; ?></b></span>
		</div>
        <div class="col-xs-2 col-md-2 big">
            &nbsp;
        </div>
	</div>
	
	<!-------------------------------------------------------- parte II------>
	<div class="row">
        <div class="col-xs-2 col-md-2">
            &nbsp;
        </div>	    
		<div class="col-xs-4 col-md-4 bg_color">
		<a href="<?php echo base_url('index.php/thesa/terms/' . $id_pa . '#' . $pa_name); ?>" class="bg_cor">
		    <span class="small"><?php echo 'thesa:' . 'th/' . $id_pa . '#' . $pa_name; ?></span></a>
		</div>
		<div class="col-xs-4 col-md-4">
			<span style="font-size: 70%"><?php echo msg('author'); ?>:</span>
		</div>
	</div>
	
	<!-------------------------------------------------------- parte III ------>
	<div class="row  bg_cor" style="margin-top: 30px;">
        <div class="col-xs-2 col-md-2 big">
            &nbsp;
        </div>  	    
		<div class="col-md-1"><span class="small"><?php echo msg('linguage'); ?></span>
		    <br><font class="bg_cor"><?php echo $pa; ?></font></div>
		<div class="col-md-1"><span class="small"><?php echo msg('created'); ?></span>
		    <br><font class="bg_cor"><?php echo stodbr($pa_created); ?></font></div>
		<div class="col-md-1"><span class="small"><?php echo msg('updated'); ?></span>
		    <br><font class="bg_cor"><?php echo stodbr($pa_update); ?></font></div>
		<div class="col-md-4"><span class="small"><?php echo msg('URI'); ?></span>
		    <br><font class="bg_cor">
		    <?php echo base_url('index.php/thesa/terms/' . $id_pa); ?></font></div>
	</div>
</div>
<br>