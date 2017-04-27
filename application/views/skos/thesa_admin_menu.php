<div class="btn-group" role="group" aria-label="...">
  <button type="button" class="btn btn-default">&nbsp;<span class="glyphicon glyphicon-print" aria-hidden="true"></span>&nbsp;</button>
  <button type="button" class="btn btn-default">&nbsp;<span class="glyphicon glyphicon-align-left" aria-hidden="true"></span>&nbsp;</button>
  <button type="button" class="btn btn-default">&nbsp;<span class="glyphicon glyphicon-blackboard" aria-hidden="true"></span>&nbsp;</button>
  
  <div class="btn-group" role="group">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <?php echo msg('add');?>
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
      <li><a href="#"><?php echo msg('terms');?></a></li>
      <li><a href="<?php echo base_url('index.php/skos/collaborators');?>"><?php echo msg('collaborators');?></a></li>
    </ul>
  </div>
</div>