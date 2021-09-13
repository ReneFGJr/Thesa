<?php
$dd11 = get("dd11");
$dd12 = get("dd12");
if (strlen($dd11) == 0) { $dd11 = '1970'; }
if (strlen($dd12) == 0) { $dd12 = date("Y"); }
?>
<div class="container">
	<div class="row">
		<div class="col-xs-8 col-md-8">
			<b><span class="middle"><?php echo msg('find_term'); ?></span></b>
			<div class="input-group" style="padding: 5px; ">
				<input type="text" class="form-control selector"  name="dd4a" id="dd4a" placeholder="Busca por..." value=""/>
				<span class="input-group-btn">
					<input type="submit" class="btn btn-primary" value="pesquisar">
				</span>
			</div><!-- /input-group -->
		</div>
		
		<div class="col-xs-12 col-md-4" style="background-color: #C0C0FF; padding: 10px;">
			<b><span class="middle"><?php echo msg('find_filter'); ?></span></b>
			<br>
			<?php echo msg('language'); ?>
			<select name="dd10" size="1" class="table">
				<option value="pt"><?php echo msg('por'); ?></option>
			</select>
			<?php echo msg('year'); ?>
			<div class="input-group" style="padding: 5px;">
				<div style="float: left;">
					<input type="text" class="form-control" name="dd11" style="width:100px;" value="<?php echo $dd11; ?>" >
				</div>
				<div  style="float: left;">&nbsp;-&nbsp;</div>
				<div style="float: left;">
					<input type="text" class="form-control" name="dd12" style="width:100px;" value="<?php echo $dd12; ?>" >
				</div>
			</div>
		</div>
	</div>
</div>
