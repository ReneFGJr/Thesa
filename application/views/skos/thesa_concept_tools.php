<?php
$onclick = ' onclick="newxy2(\''.base_url('index.php/thesa/concept_change_preflabel/'.$id_c.'/'.$c_th.'/'.checkpost_link($id_c.$c_th)).'\',600,600);" ';
?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<ul>
				<li><a href="#" <?php echo $onclick;?>><?php echo msg('change_prefLabel');?></a></li>
			</ul>
		</div>
	</div>
</div>
