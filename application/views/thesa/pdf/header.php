<body style="font-family: tahoma, Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 13px; line-height: 130%;">


<table width="100%" style="border: 1px solid #000000; padding: 10px;">
	<tr>
		<td colspan=2 align="right">
			<h2>Thesa: <?php echo $pa_name;?></h2>
		</td>
	</tr>
	<tr>		
		<td colspan=2>
			<img src="<?php echo $image_bk;?>" width="100%">
		</td>
	</tr>
	
	<tr>
		<td style="font-size: 9px;" align="right" valign="top"><?php echo msg("authors");?>:</td>
		<td><?php 
			for ($r=0;$r < count($authors);$r++)
				{
					echo $authors[$r]['us_nome'].'<br>';
				}
			
			?></td>
	</tr>	
	
	<tr><td colspan=2><br></td></tr>
	<tr style="font-size: 9px;">
		<td align="right"><?php echo msg("version");?>:</td>
		<td><span stlye=""<?php echo $pa_update;?></td>
	</tr>	
	
	<tr>
		<td style="font-size: 9px;" align="right" valign="top"><?php echo msg("printed");?>:</td>
		<td><?php echo date("d M Y").'<br>';?></td>
	</tr>	

	<tr>
		<td style="font-size: 9px;" align="right" valign="top"><?php echo msg("place");?>:</td>
		<td><a href="<?php echo base_url('index.php/main/th/'.$id_pa);?><?php echo base_url('index.php/main/th/'.$id_pa);?></a></td>
	</tr>	

</table>
</body>