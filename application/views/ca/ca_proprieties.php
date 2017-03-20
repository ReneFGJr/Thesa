<div class="container">
	<div class="row">
		<div class="col-md-12 col-xs-12 col-sm-12">
			<h4><tt>Proprieties</tt></h4>
		</div>

		<div class="col-md-4 col-xs-4 col-sm-4">
			<tt><?php echo msg('Type of name') . ': ' . $ty_name; ?></tt>
		</div>

		<div class="col-md-4 col-xs-4 col-sm-4">
			<tt><?php echo msg('update') . ': ' . stodbr($a_created); ?></tt>
		</div>
		
		<div class="col-md-12 col-xs-12 col-sm-12">
			<table class="table small" width="100%">
				<tr>
					<th width="20%"><?php echo msg('propriety');?></th>
					<th width="70%"><?php echo msg('value');?></th>
					<th width="10%"><?php echo msg('agency');?></th>					
				</tr>
				
				<tr>
					<td><tt><?php echo 'rdf:class';?></tt></td>
					<td class="middle"><tt><?php echo $class;?></tt></td>										
				</tr>				
				
			<?php
				for ($r = 0; $r < count($related); $r++) {
					$line = $related[$r];
					$prop = $line['rs_propriety'];
					$pref = $line['prefix_ref'];
					$value = $line['rl_value'];
					$agency = $line['ag_id'];
					
					echo '<tr>';
					echo '<td><tt>'.msg($pref.':'.$prop).'</tt></td>';
					echo '<td class="middle"><tt>'.$value.'</tt></td>';
					echo '<td class="middle"><tt>'.$agency.'</tt></td>';
					echo '</tr>';
				}
			?>
		</table>
		</div>
	</div>
</div>
