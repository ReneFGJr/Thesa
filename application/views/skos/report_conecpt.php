<?php
if (!isset($action)) {
	$action = '';
}
?>
<div class="container">
	<div class="row">
		<div class="col-md-10"><?php echo msg('pref_term'); ?></br><font class="xxxbig"><?php echo $rl_value; ?></font></div>
		<div class="col-md-2"><?php echo msg('concept'); ?></br><font class="xxxbig"><?php echo $c_concept; ?></font></div>
	</div>
</div>

<div class="container">
	<div class="row">
		<div class="col-md-5">
			<?php
			if ((count($terms_bt))  > 0) {
				echo '<h4>';
				echo msg('concept_BT');
				if (count($terms_bt) == 0)
					{
					//echo ' <a href="#" onclick="newwin(\''.base_url('index.php/skos/tg/'.$id_c.'/'.checkpost_link($id_c)).'\',500,500);">';
					echo '<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>';
					//echo '</a>';
					}				
				echo '</h4>';
				echo $this -> skoses -> concepts_show_rp($terms_bt,'TG');
			}
			if ((count($terms_nw)) > 0) {
				echo '<h4>';
				echo msg('concept_NR');		
				echo '</h4>';
				echo $this -> skoses -> concepts_show_rp($terms_nw,'TE');
			}
			
			if ((count($terms_tr))  > 0) {
				echo '<h4>';
				echo msg('concept_TR');
				if (count($terms_tr) == 0)
					{
					//echo ' <a href="#" onclick="newwin(\''.base_url('index.php/skos/tr/'.$id_c.'/'.checkpost_link($id_c)).'\',500,500);">';
					echo '<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>';
					//echo '</a>';
					}				
				echo '</h4>';
				echo $this -> skoses -> concepts_show_rp($terms_tr,'TR');
			}
			?>
		</div>
		<div class="col-md-5">
			<?php
			if ((count($notes)) > 0) 
				{
					echo '<h4>';
					echo msg('concept_DEF');
					//echo ' <a href="#" onclick="newwin(\''.base_url('index.php/skos/tf/'.$id_c.'/'.checkpost_link($id_c)).'\',500,500);">';
					echo '<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>';
					//echo '</a>';
					echo '</h4>';
					echo $this->skoses->notes_show($notes);
				}
			
			if ((count($terms_al)) > 0) 
				{
					echo '<h4>';
					echo msg('concept_ALT');
					//echo ' <a href="#" onclick="newwin(\''.base_url('index.php/skos/te/'.$id_c.'/'.checkpost_link($id_c)).'\',500,500);">';
					echo '<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>';
					//echo '</a>';
					echo '</h4>';
					echo $this->skoses->concepts_show_rp($terms_al,'TA');
				}
			if ((count($terms_hd)) > 0) 
				{
					echo '<h4>';
					echo msg('concept_HID');
					//echo ' <a href="#" onclick="newwin(\''.base_url('index.php/skos/tz/'.$id_c.'/'.checkpost_link($id_c)).'\',500,500);">';
					echo '<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>';
					//echo '</a>';
					echo '</h4>';
					echo $this->skoses->concepts_show_rp($terms_hd);
				}
			if ((count($terms_fl)) > 0) 
				{
					echo '<h4>';
					echo msg('concept_FLE');
					//echo ' <a href="#" onclick="newwin(\''.base_url('index.php/skos/tx/'.$id_c.'/'.checkpost_link($id_c)).'\',500,500);">';
					echo '<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>';
					//echo '</a>';
					echo '</h4>';
					echo $this->skoses->concepts_show_rp($terms_fl);
				}								
				?>				
			</div>
		<div class="col-md-2">
			<a href="<?php echo base_url('index.php/skos/json/'.$c_concept);?>" target="_new" class="btn btn-default">JSON</a>
			<h4><?php echo msg('concept_IMG'); ?></h4>
			<img src="/projeto/Biblioteca_Facil/_acervo/thumb/0000000_287px.jpg" border=1 class="img-rounded" style="width: 100%">
		</div>
		<div>
			Metadados administrativo
			<ul>
				<li>
					Autor
				</li>
				<li>
					Data de criação
				</li>
				<li>
					Data de atualização
				</li>
				<li>
					descrição da ação
				</li>
			</ul>
		</div>
	</div>
</div>
