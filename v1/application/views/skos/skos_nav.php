<?php
$pga = array('','','','','','','','','','');
if (!isset($pg)) { $pg = 0; }
switch($pg)
	{
	case '1':
		$pga[1] = 'active';
		break;
	case '2':
		$pga[2] = 'active';
		break;
	case '3':
		$pga[3] = 'active';
		break;	
	case '4':
		$pga[4] = 'active';
		break;						
	default:
		$pga[0] = 'active';
		break;
	}
?>
<div class="container">
<ul class="nav nav-tabs">
  <li role="presentation" class="<?php echo $pga[0];?>"><a href="<?php echo base_url('index.php/thesa/show');?>"><?php echo msg('Home');?></a></li>
  <li role="presentation" class="<?php echo $pga[1];?>"><a href="<?php echo base_url('index.php/thesa/terms');?>"><?php echo msg('Terms');?></a></li>
 <!---
  <li role="presentation" class="dropdown">
    <a class="dropdown-toggle <?php echo $pga[2];?>" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
      Dropdown <span class="caret"></span>
    </a>
    <ul class="dropdown-menu">
      ...
    </ul>
  </li>
   --->
  <li role="presentation" class="<?php echo $pga[3];?>"><a href="<?php echo base_url('index.php/thesa/concept_add');?>"><?php echo msg('Concept_add');?></a></li>
</ul>
</div>