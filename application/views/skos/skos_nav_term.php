<?php
$pga = array('', '', '', '', '', '', '', '', '', '');
if (!isset($pg)) { $pg = 0;
}
switch($pg) {
	case '1' :
		$pga[1] = 'active';
		break;
	case '2' :
		$pga[2] = 'active';
		break;
	case '3' :
		$pga[3] = 'active';
		break;
	default :
		$pga[0] = 'active';
		break;
}
?>
<div class="container">
<ul class="nav nav-tabs">
  <li role="presentation" class="<?php echo $pga[0]; ?>"><a href="<?php echo base_url('index.php/thesa/show'); ?>"><?php echo msg('Home'); ?></a></li>
  <li role="presentation" class="<?php echo $pga[1]; ?>"><a href="<?php echo base_url('index.php/thesa/terms'); ?>"><?php echo msg('Terms'); ?></a></li>
  <li role="presentation" class="dropdown">
    <a class="dropdown-toggle <?php echo $pga[2]; ?>" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
      <?php echo msg('Terms_action'); ?> <span class="caret"></span>
    </a>
    <ul class="dropdown-menu">
    	<?php
		if ($ct_concept == '') {
			echo '<li role="presentation" ><a href="' . base_url('index.php/thesa/concept_create/' . $lt_thesauros . '/' . checkpost_link($lt_thesauros) . '/' . $id_lt) . '">' . msg('Terms_create_concept') . '</a></li>';
		} else {
			echo '<li role="presentation" ><a href="' . base_url('index.php/thesa/concept_subordinate/' . $lt_thesauros . '/' . checkpost_link($lt_thesauros) . '/' . $id_lt) . '">' . msg('Terms_subordinate') . '</a></li>';
		}
		?>
	</ul>
  </li>
  <li role="presentation" class="<?php echo $pga[3]; ?>"><a href="<?php echo base_url('index.php/thesa/concept_add'); ?>"><?php echo msg('Concept_add'); ?></a></li>
</ul>
</div>