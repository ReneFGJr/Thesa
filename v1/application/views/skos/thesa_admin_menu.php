<?php
if (!isset($_SESSION['skos']))
	{
		$th = 0;
		if (isset($id_pa)) { $th = $id_pa; $_SESSION['skos'] = $th; }
	} else {
		$th = $_SESSION['skos'];	
	}

if (!isset($_SESSION['id']))
	{
		$id = 0;
	} else {
		$id = $_SESSION['id'];
	}
?>
<div class="container">
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo base_url('index.php/thesa/terms/'.$th);?>"><?php echo msg('home'); ?></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="<?php echo base_url('index.php/thesa/terms/'.$th);?>"><?php echo msg('glossario');?></a></li>
        <li><a href="<?php echo base_url('index.php/thesa/thes/'.$th);?>"><?php echo msg('conceitual_map');?></a></li>
        <?php if ($this -> skoses -> autho($id, $th) == 1) { ?>			
			
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo msg('adds'); ?><span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo base_url('index.php/thesa/concept_add/'); ?>"><?php echo msg('terms'); ?></a></li>
            <li><a href="<?php echo base_url('index.php/thesa/collaborators'); ?>"><?php echo msg('collaborators'); ?></a></li>
            <li role="separator" class="divider"></li>
          </ul>
        </li>
        
        <!-- RULES -->
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo msg('rules'); ?><span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo base_url('index.php/thesa/check_all/'); ?>"><?php echo msg('check_all'); ?></a></li>
            <li><a href="<?php echo base_url('index.php/thesa/th_edit/'.$th.'/'.checkpost_link($th)); ?>"><?php echo msg('preferences'); ?></a></li>
			<li><a href="<?php echo base_url('index.php/thesa/terms_list/');?>"><?php echo msg('terms_list'); ?></a></li>
			<li><a href="<?php echo base_url('index.php/thesa/terms_from_to/');?>"><?php echo msg('remissiva_de_para'); ?></a></li>                
          </ul>
        </li>        
        
        <?php } ?>
         
      	<!-- RULES -->
      	<li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo msg('outputs'); ?><span class="caret"></span></a>
          <ul class="dropdown-menu">
          	<li><a href="<?php echo base_url('index.php/thesa/thas/'.$th);?>"><?php echo msg('printer_alfabetic'); ?></a></li>
            <li><a href="<?php echo base_url('index.php/thesa/thrs/'.$th);?>"><?php echo msg('printer'); ?></a></li>
            <li><a href="<?php echo base_url('index.php/thesa/thri/'.$th);?>"><?php echo msg('printer_sistematic'); ?></a></li>
          </ul>
        </li>
        
        
                
      </ul>          
      
      <ul class="nav navbar-nav navbar-right">
      <form class="navbar-form navbar-left">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Search">
        </div>
        <button type="submit" class="btn btn-secondary"><?php echo msg('search'); ?></button>
      </form>
      </ul>
  </div><!-- /.container-fluid -->
</nav>
</div>