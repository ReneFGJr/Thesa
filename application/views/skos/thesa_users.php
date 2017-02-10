<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h3><?php echo msg("collaborators"); ?></h3>
			<form>
			<span class="small"><?php echo msg('email'); ?></span><br>
		    <div class="input-group">
		      <input type="text" class="form-control" name="emailCollaborator" placeholder="<?php echo msg('email'); ?>" value="<?php echo get("emailCollaborator"); ?>">
		      <span class="input-group-btn">
		        <input type="submit" name="btn_emailCollaborator" class="btn btn-secondary" type="button" value="<?php echo msg('to_invite'); ?>">
		      </span>
		      </form>
		    </div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">	
			<?php echo $content; ?>
		</div>
	</div>	
</div>