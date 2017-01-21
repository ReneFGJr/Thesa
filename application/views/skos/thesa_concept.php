<?php ?>	
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<span class="thesa-concept thesa thesa_under"><?php echo $rl_value; ?></span>
			<br>
			<br>
		</div>
	</div>
	<div class="row">
		<div class="col-md-5">
				<?php
				/******************************************** TG *******************/
				if (count($terms_bt) > 0) {
					for ($r = 0; $r < count($terms_bt); $r++) {
						$line = $terms_bt[$r];
						$link = '<a href="' . base_url('index.php/skos/c/' . $line['ct_concept']) . '" class="thesa_link">';
						echo 'TG: ';
						echo $link;
						echo $line['rl_value'];
						echo '</a>';
						echo cr();
						echo '<br>';
					}
					echo '<br>';
				}

				/******************************************** TE *******************/
				if (count($terms_nw) > 0) {

					for ($r = 0; $r < count($terms_nw); $r++) {
						$line = $terms_nw[$r];
						$link = '<a href="' . base_url('index.php/skos/c/' . $line['ct_concept']) . '" class="thesa_link">';
						echo 'TE: ';
						echo $link;
						echo $line['rl_value'];
						echo '</a>';
						echo cr();
						echo '<br>';
					}
					echo '<br>';
				}
				/**************************************************** usado por ****/
				if (count($terms_al) > 0) {
					for ($r = 0; $r < count($terms_al); $r++) {
						$line = $terms_al[$r];
						$link = '<a href="' . base_url('index.php/skos/c/' . $line['ct_concept']) . '" class="thesa_link">';
						echo 'UP: ';
						echo $link;
						echo $line['rl_value'];
						echo '</a>';
						echo cr();
						echo '<br>';
					}
					echo '<br>';
				}				

				/**************************************************** usado por ****/
				if ((count($terms_hd) > 0) and ($this->skoses->autho('',$ct_th))) {
					for ($r = 0; $r < count($terms_hd); $r++) {
						$line = $terms_hd[$r];
						$link = '<a href="' . base_url('index.php/skos/c/' . $line['ct_concept']) . '" class="thesa_link">';
						echo 'UP (hidden): ';
						echo $link;
						echo $line['rl_value'];
						echo '</a>';
						echo cr();
						echo '<br>';
					}
					echo '<br>';
				}				


				/******************************************** TR *******************/
				if (count($terms_tr) > 0) {
					for ($r = 0; $r < count($terms_tr); $r++) {
						$line = $terms_tr[$r];
						$link = '<a href="' . base_url('index.php/skos/c/' . $line['ct_concept']) . '" class="thesa_link">';
						echo 'TR: ';
						echo $link;
						echo $line['rl_value'];
						echo '</a>';
						echo cr();
						echo '<br>';
					}
					echo '<br>';
				}

				/******************************************** TR *******************/
				if (count($terms_ge) > 0) {
					for ($r = 0; $r < count($terms_ge); $r++) {
						$line = $terms_ge[$r];
						$link = '<a href="' . base_url('index.php/skos/c/' . $line['ct_concept']) . '" class="thesa_link">';
						$prop = trim($line['prefix_ref']).':'.trim($line['rs_propriety']);
						echo msg($prop).': ';
						echo $link;
						echo $line['rl_value'];
						echo '</a>';
						echo cr();
						echo '<br>';
					}
					echo '<br>';
				}
		?>
		</div>
		<div class="col-md-5">
		<?php
			/******************************************************* NOTES *******/
			if (count($notes) > 0) {
				for ($r = 0; $r < count($notes); $r++) {
					$line = $notes[$r];
					echo '<br>';
					echo '<span class="thesa_under thesa">';
					echo '<b>';
					echo msg($line['prefix_ref'] . ':' . $line['rs_propriety']);
					echo '</b>';
					echo '</span>';
					echo '<br><span class="thesa_texto">' . mst($line['rl_value']) . '</span>';
					echo '<br>';
				}

			}
				?>
		</div>		
		<div class="col-md-2 text-right">
			<span class="btn btn-default"><?php echo $c_concept; ?></span>
		</div>
	</div>
</div>


