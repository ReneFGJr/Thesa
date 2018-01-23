<botton class="btn btn-secondary"><?php echo msg('term_add'); ?></botton>
<botton class="btn btn-secondary"><?php echo msg('term_add_icon'); ?></botton>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
  Launch demo modal
</button>

<!-- Modal -->
<form method="post" action="<?php echo base_url('index.php/skos/ti/'.$id_c.'/'.$c_th);?>">
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo msg('associate_new_term');?></h4>
      </div>
      <div class="modal-body">
        <textarea name="dd1" rows="10" cols="100%" class="form-control"></textarea>
        <select name="dd2">
        	<option value="por">Português</option>
        	<option value="eng">Inglês</option>
        </select>
        <font class="small"><?php echo msg("associate_new_term_inf");?></font>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo msg('cancel');?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo msg('associate_new_term');?>"></button>
      </div>
    </div>
  </div>
</div>
</form>