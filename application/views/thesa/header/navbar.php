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
<nav class="navbar navbar-toggleable-md navbar-light bg-faded">
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
			if (perfil('#ADM')==1) { ?>
            <li class="nav-item active">
                <a class="nav-link" href="<?php echo base_url('index.php/thesa/thesaurus_my'); ?>"><?php echo msg('my_thesauros'); ?></a>
            </li>
            <?php } ?>
			<li class="nav-item">
				<a class="nav-link" href="<?php echo base_url('index.php/thesa/contact'); ?>">Contato</a>
			</li>
			<!------ catalog ---->
			<?php 
			if (perfil('#ADM')==1) 
			{ ?>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Catalogação </a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
				    <a class="dropdown-item" href="<?php echo base_url('index.php/main/authority'); ?>"><?php echo msg("authority"); ?></a>
				    <a class="dropdown-item" href="<?php echo base_url('index.php/main/catalog'); ?>">Preparo técnico</a>
                    <a class="dropdown-item" href="<?php echo base_url('index.php/main/authority'); ?>">Autoridade</a>
                    <a class="dropdown-item" href="<?php echo base_url('index.php/main/vocabulary'); ?>">Vocabulários controlados</a>
				</div>
			</li>
			<?php } ?>
			
			<!------ ADMIN CONFIG ---->
            <?php 
            if (perfil('#ADM')==1) 
            { ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="<?php echo base_url('index.php/main/config'); ?>" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo msg('menu_config'); ?></a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="<?php echo base_url('index.php/main/config/msg'); ?>"><?php echo msg("menu_msg"); ?></a>
                    <a class="dropdown-item" href="<?php echo base_url('index.php/main/config/forms'); ?>"><?php echo msg("menu_forms"); ?></a>
                    <a class="dropdown-item" href="<?php echo base_url('index.php/main/config/authority'); ?>"><?php echo msg("menu_authority"); ?></a>
                </div>
            </li>                
            <?php } ?>
            <li class="nav-item navbar-toggler-right">
                <?php echo $this -> socials -> menu_user(); ?>
            </li>           
                       			
		</ul>
	</div>
</nav>
