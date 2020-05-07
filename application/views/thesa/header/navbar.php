<?php
$ac = array('', '', '', '', '', '', '', '', '', '', '', '', '');
if (!isset($pag)) { $pag = 0;
}
$ac[$pag] = 'active';
if (isset($_SESSION['id']) and ($_SESSION['id'] != '')) {
	$loged = 1;
} else {
	$loged = 0;
}
?>

<nav class="navbar navbar-toggleable-md navbar-light bg-faded ">
	<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<a class="navbar-brand" href="<?php echo base_url('index.php/thesa'); ?>"><img src="<?php echo base_url('img/logo_thesa.jpg'); ?>" style="height: 30px;"></a>

	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav mr-auto">
			<li class="nav-item active">
				<a class="nav-link" href="<?php echo base_url('index.php/thesa/thesaurus_open'); ?>"><?php echo msg('open_thesauros'); ?> <span class="sr-only">(current)</span></a>
			</li>
			<?php 
			if ((perfil('#ADM')==1) or (isset($_SESSION['id']) and (strlen($_SESSION['id']) >0))) { ?>
            <li class="nav-item active">
                <a class="nav-link" href="<?php echo base_url('index.php/thesa/thesaurus_my'); ?>"><?php echo msg('my_thesauros'); ?></a>
            </li>
            <?php } ?>

            <?php
            if (isset($_SESSION['skos']))
				{
					$ids = round($_SESSION['skos']);
					$link = base_url(PATH.'terms/'.$ids);
					echo '
					<li class="nav-item">
					<a class="nav-link" href="'.$link.'">'.msg('TH_atual').'</a>
					</li>
					';

				}
            ?>
			<li class="nav-item">
				<a class="nav-link" href="<?php echo base_url('index.php/thesa/contact'); ?>"><?php echo msg("Contact");?></a>
			</li>
			
			<!------ ADMIN CONFIG ---->
            <?php 
            if (perfil('#ADM')==10) 
            { ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="<?php echo base_url('index.php/main/config'); ?>" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo msg('menu_config'); ?></a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="<?php echo base_url('index.php/main/config/msg'); ?>"><?php echo msg("menu_msg"); ?></a>
                    <a class="dropdown-item" href="<?php echo base_url('index.php/main/config/forms'); ?>"><?php echo msg("menu_forms"); ?></a>
                    <a class="dropdown-item" href="<?php echo base_url('index.php/main/config/authority'); ?>"><?php echo msg("menu_authority"); ?></a>
                    <a class="dropdown-item" href="<?php echo base_url('index.php/thesa/admin_thesauros'); ?>"><?php echo msg("admin_thesauros"); ?></a>
                </div>
			</li>   
            <?php } ?> 
			</ul>

			<ul class="navbar-nav">	
			<!-- language -->
			<?php 
			$language = new language;
			echo $language -> menu_language(); 
			?>

			<!-- user -->						
			<?php 
			$socials = new socials;
			echo $socials -> menu_user(); 
			?>
			</ul>
		</ul>
	</div>
</nav>

