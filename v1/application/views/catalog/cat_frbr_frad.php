<h2><?php echo msg('FRAD'); ?></h2>
<?php
$group = lowercase('WORKFRAD');
$sql = "SELECT * FROM rdf_resource where rs_group = '$group' ORDER BY id_rs";
$qrlt = $this->db->query($sql);
$qrlt = $qrlt->result_array();

for ($r=0;$r < count($qrlt);$r++)
	{
		$line = $qrlt[$r];
		$link = ' <a href="#" onclick="newwin(\'' . base_url('index.php/catalog/input/'.$group.'/' . $ia_id_a . '/' . $line['id_rs'].'/'.checkpost_link($ia_id_a.$line['id_rs'])) . '\',500,500);">';
		$link .= '<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>';
		$link .= '</a>';
		
		echo '<h4>'.msg('title_'.$line['rs_propriety']).$link.'</h4>';
		$prop = $line['rs_propriety'];
		echo '<ul>';
		for ($y=0;$y < count($work_authority);$y++)
			{
				$xline = $work_authority[$y];
				$prop2 = $xline['rs_propriety'];
				 
				if ($prop == $prop2)
					{
						$link = '<a href="'.base_url('index.php/w/a/'.$xline['id_rl']).'" class="middle">';
						echo '<li>';
						echo $link;
						echo '<tt>'.$xline['rl_value'].'</tt>';
						echo '</a>';
						echo '</li>';
					}
			}
		echo '</ul>';
	}
?>