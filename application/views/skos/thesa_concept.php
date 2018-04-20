<?php
$link = base_url('index.php');
$link .= '/thesa/c/' . $id_c . '/' . $c_th;

$linkc = $this -> skoses -> url;
$linkc .= 'index.php/thesa/c/' . $id_c;
//.'#'.$rl_value;


$linke = 'index.php/thesa/cedit/' . $id_c;
$linke = base_url($linke);

$linkr = base_url('index.php');
$linkr .= '/thesa/c/' . $id_c . '/rdf';
?>	
<div class="container">
	<div class="row">
		<div class="col-md-7 col-xs-7 col-sm-7">
			<a  href="<?php echo $link; ?>"  class="thesa-concept thesa thesa_under"><?php echo $rl_value; ?></a>
			<br><input type="text" id="cpc" value = "<?php echo $linkc; ?>" class="small" style="width: 100%; border: 0px;" readonly >
			<br>
			<br>
		</div>
		<div class="col-md-5 text-right">
			<nobr>
            <?php if ($edit == 1) 
                {
                    echo '<a href="'.$linke.'" class="btn btn-secondary">'.msg('edit').'</a>';        
                }			    
			?>
			<a href="<?php echo $linkc; ?>" class="btn btn-secondary"><?php echo $this -> skoses -> prefix . ':' . $c_concept; ?></a>
			<button class="btnc btn btn-secondary" data-clipboard-target="#cpc" title="<?php echo msg('copy_to_clipboard'); ?>" onclick="copytoclipboard('cpc');">
    			<img src="<?php echo base_url('img/icone/copy.png');?>" height="18">
			</button>					

			<a href="<?php echo $linkr; ?>" class="btn btn-secondary" title="<?php echo msg("rdf_link");?>">
			<img src="<?php echo base_url('img/icone/rdf_w3c.svg');?>" height="18">
			</a>
			</nobr>
		</div>		
	</div>
	<div class="row">
		<div class="col-md-5">
				<?php
				/******************************************** TG *******************/
				if (count($terms_bt) > 0) {
					for ($r = 0; $r < count($terms_bt); $r++) {
						$line = $terms_bt[$r];
						$link = '<a href="' . base_url('index.php/thesa/c/' . $line['ct_concept']) . '" class="thesa_link">';
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
						$link = '<a href="' . base_url('index.php/thesa/c/' . $line['ct_concept']) . '" class="thesa_link">';
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
						$link = '<a href="' . base_url('index.php/thesa/c/' . $line['ct_concept']) . '" class="thesa_link">';
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
				if ((count($terms_hd) > 0) and ($this -> skoses -> autho('', $ct_th))) {
					for ($r = 0; $r < count($terms_hd); $r++) {
						$line = $terms_hd[$r];
						$link = '<a href="' . base_url('index.php/thesa/c/' . $line['ct_concept']) . '" class="thesa_link">';
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
						$link = '<a href="' . base_url('index.php/thesa/c/' . $line['ct_concept']) . '" class="thesa_link">';
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
						$link = '<a href="' . base_url('index.php/thesa/c/' . $line['ct_concept']) . '" class="thesa_link">';
						$prop = trim($line['prefix_ref']) . ':' . trim($line['rs_propriety']);
						echo msg($prop) . ': ';
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
		<div class="col-md-4">
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
		<div class="col-md-3">
            <?php for ($r=0;$r < count($images);$r++)
                {
                    echo '<img src="'.base_url($images[$r]).'" border=1 class="img-rounded img-responsive">';      
                }
            ?>
		</div>		
	</div>
</div>


