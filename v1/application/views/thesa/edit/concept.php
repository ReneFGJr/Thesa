<?php
$alter_pref_onclick = ' onclick="newwin(\''.base_url('index.php/thesa/concept_change_preflabel/'.$id_c.'/'.$c_th.'/'.checkpost_link($id_c.$c_th)).'\',600,600);" ';
$multiLanguage = 1;
if (!isset($action)) {
	$action = '';
}
?>
<div class="container">
	<div class="row">
		<div class="col-md-9">
			<?php echo msg('pref_term'); ?></br>
			<a href="<?php echo base_url(PATH.'c/'.$id_c);?>">
			<span class="big"><?php echo $rl_value; ?>
			</a>
			<sup>(<?php echo $rl_lang;?>)</sup>
		</span>	
			<!---- alter_pref_onclick -->
			<br/>
			<a href="#" <?php echo $alter_pref_onclick;?> class="small"><?php echo msg('change_prefLabel');?></a>				
		</div>
		<div class="col-md-1">
			<a href="#" onclick="newxy('<?php echo base_url('index.php/thesa/cremove/'.$id_c.'/'.checkpost_link($id_c));?>',1024,500);" title="<?php echo msg('delete concept');?>">
			<img src="<?php echo base_url('img/icone/exclud.png');?>" class="img-fluid trash">
			</a>
		</div>
		<div class="col-md-2 btn btn-secondary">
			<?php echo msg('concept'); ?></br><font class="xxxbig"><?php echo $c_concept; ?></font>
		</div>
	</div>
</div>
<br>
<?php

?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
				
		</div>
	</div>
</div>
<br>
<div class="container">
	<div class="row">
		<div class="col-md-5">
			<?php
			/************* MULTI IDIOMAS */
			if ($multiLanguage == 1)
					{
					echo '<h4>';
					echo msg('prefLabel');
					echo '<a href="#" onclick="newxy(\''.base_url('index.php/thesa/tp/'.$id_c.'/'.checkpost_link($id_c)).'\',1024,500);">';
					echo '<img src="'.base_url('img/icone/plus.png').'" width="32">';
					echo '</a>';
					echo '</h4>';
					echo $this -> skoses -> concepts_show($terms_pref);
					}
			?>
			<?php
			if ((count($terms_bt) + $editar)  > 0) {
				echo '<h4>';
				echo msg('concept_BT');
				if (count($terms_bt) == 0)
					{
					echo ' <a href="#" onclick="newxy(\''.base_url('index.php/thesa/tg/'.$id_c.'/'.checkpost_link($id_c)).'\',1024,500);">';
					echo '<img src="'.base_url('img/icone/plus.png').'" width="32">';
					echo '</a>';
					}				
				echo '</h4>';
				echo $this -> skoses -> concepts_show($terms_bt);
			}
			if ((count($terms_nw)) > 0) {
				echo '<h4>';
				echo msg('concept_NR');		
				echo '</h4>';
				echo $this -> skoses -> concepts_show($terms_nw);
			}
			
			if ((count($terms_tr) +$editar)  > 0) {
				echo '<h4>';
				echo msg('concept_TR');
				echo ' <a href="#" onclick="newxy(\''.base_url('index.php/thesa/tr/'.$id_c.'/'.checkpost_link($id_c)).'\',1024,500);">';
				echo '<img src="'.base_url('img/icone/plus.png').'" width="32">';
				echo '</a>';
				echo '</h4>';
				echo $this -> skoses -> concepts_show($terms_tr);
			}
			?>
		</div>
		<div class="col-md-5">
			<?php
			if ((count($notes)  +$editar) > 0) 
				{
					echo '<h4>';
					echo msg('concept_DEF');
					echo ' <a href="#" onclick="newxy(\''.base_url('index.php/thesa/tf/'.$id_c.'/'.checkpost_link($id_c)).'\',1024,500);">';
					echo '<img src="'.base_url('img/icone/plus.png').'" width="32">';
					echo '</a>';
					echo '</h4>';
					echo $this->skoses->notes_show($notes,$editar);
				}
			
			if ((count($terms_al)  +$editar) > 0) 
				{
					echo '<h4>';
					echo msg('concept_ALT');
					echo ' <a href="#" onclick="newxy(\''.base_url('index.php/thesa/te/'.$id_c.'/'.checkpost_link($id_c)).'\',1024,500);">';
					echo '<img src="'.base_url('img/icone/plus.png').'" width="32">';
					echo '</a>';
					echo '</h4>';
					echo $this->skoses->concepts_show($terms_al);
				}
			if ((count($terms_hd)  +$editar) > 0) 
				{
					echo '<h4>';
					echo msg('concept_HID');
					echo ' <a href="#" onclick="newxy(\''.base_url('index.php/thesa/tz/'.$id_c.'/'.checkpost_link($id_c)).'\',1024,500);">';
					echo '<img src="'.base_url('img/icone/plus.png').'" width="32">';
					echo '</a>';
					echo '</h4>';
					echo $this->skoses->concepts_show($terms_hd);
				}
				?>				
			</div>
		<div class="col-md-2">
			<h4><?php echo msg('concept_IMG'); ?>
			<?php
			if ($editar == 1)
				{
				echo ' <a href="#" onclick="newxy(\''.base_url('index.php/thesa/timg/'.$id_c.'/'.checkpost_link($id_c)).'\',1024,500);">';
				echo '<img src="'.base_url('img/icone/plus.png').'" width="32">';
				echo '</a>';
				}
			?>				
			</h4>
			<?php for ($r=0;$r < count($images);$r++)
				{
					echo '<img src="'.base_url($images[$r]).'" border=1 class="img-rounded" style="width: 100%">';		
				}
			?>
		</div>
	</div>
</div>
