<?php
if (!isset($action)) {
	$action = '';
}
?>
<div class="container">
	<div class="row">
		<div class="col-md-1 col-xs-2 col-sm-2 text-right"><span class="btn btn-secondary"><?php echo $c_concept; ?></span></div>
		<div class="col-md-11 col-xs-10 col-sm-10"><tt><font class="big"><?php echo $rl_value; ?></font></tt></div>		
	</div>
</div>

<div class="container">
	<div class="row">
		<div class="col-md-6 col-xs-6 col-sm-12">
			<?php
			if ((count($terms_bt)) > 0) {
				echo '<h4>';
				echo msg('concept_BT');
				echo '</h4>';
				echo $this -> skoses -> concepts_show_rp($terms_bt, 'TG');
			}
			if ((count($terms_nw)) > 0) {
				echo '<h4>';
				echo msg('concept_NR');
				echo '</h4>';
				echo $this -> skoses -> concepts_show_rp($terms_nw, 'TE');
			}

			if ((count($terms_tr)) > 0) {
				echo '<h4>';
				echo msg('concept_TR');
				echo '</h4>';
				echo $this -> skoses -> concepts_show_rp($terms_tr, 'TR');
			}
			?>
		</div>
		<div class="col-md-6 col-xs-6 col-sm-12">
			<?php
			if ((count($notes)) > 0) {
				echo '<h4>';
				echo msg('concept_DEF');
				echo '</h4>';
				echo $this -> skoses -> notes_show($notes);
			}

			if ((count($terms_al)) > 0) {
				echo '<span class="middle">';
				echo msg('concept_ALT');
				echo '</span>';
				echo $this -> skoses -> concepts_show_rp($terms_al, 'UP');
			}
			if ((count($terms_hd)) > 0) {
				echo '<h4>';
				echo msg('concept_HID');
				echo '</h4>';
				echo $this -> skoses -> concepts_show_rp($terms_hd);
			}
				?>				
			</div>
	</div>
</div>

