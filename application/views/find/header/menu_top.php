<style>
    body {
        position: relative;
    }
    .navbar {
        background-color: #fefefe;
        border-bottom: 2px solid
    }
    #section1 {
        padding-top: 50px;
        height: 500px;
        color: #800303;
        background-color: #ffffff;
    }
    #section2 {
        padding-top: 50px;
        height: 500px;
        color: #fff;
        background-color: #ff9800;
    }
    #section3 {
        padding-top: 50px;
        height: 500px;
        color: #800303;
        background-color: #ffffff;
    }
    #section4 {
        padding-top: 50px;
        height: 500px;
        color: #fff;
        background-color: #ff9800;
    }
    #section5 {
        padding-top: 50px;
        height: 500px;
        color: #800303;
        background-color: #ffffff;
    }
</style>

<body data-spy="scroll" data-target=".navbar" data-offset="50">
	<nav class="navbar navbar-fixed-top" id="main-wrap">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?php echo base_url('index.php/catalog#welcome'); ?>"> 
					<span class="logo_title logo_mini"><span class="glyphicon glyphicon-book" aria-hidden="true" style="font-size: 120%; color: #AA3030"></span> 
					<span style="color: #9A1010; font-size: 150%;">FIND</span>
			</div>
			<div>
				<div class="collapse navbar-collapse" id="myNavbar">
					<ul class="nav navbar-nav">
						<?php 
						/***************************************************************/
						/* ADMIN *******************************************************/
						/***************************************************************/
						if (isset($_SESSION['nivel']))
                        {
                            $nivel = $_SESSION['nivel'];    
                        } else {
                            $nivel = 0;
                        }
                        						
						if ($nivel > 0) {  
						?>
                         <li class="dropdown">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo msg('cataloging'); ?><span class="caret"></span></a>
                      
                          <ul class="dropdown-menu">
                                <li>
                                <a href="<?php echo base_url('index.php/find/cataloging'); ?>" class="menu_sk"><?php echo msg('bibliografic'); ?></a>
                                </li>
                                <li>
                                <a href="<?php echo base_url('index.php/find/authority'); ?>" class="menu_sk"><?php echo msg('authority'); ?></a>
                                </li>
                                
                          </ul>
                        </li>						
                        <li>
                            <a href="<?php echo base_url('index.php/authority/find'); ?>" class="menu_sk"><?php echo msg('authority_find'); ?></a>
                        </li>					
						<li>
							<a href="<?php echo base_url('index.php/find/work'); ?>" class="menu_sk"><?php echo msg('work'); ?></a>
						</li>										
						<li>
							<a href="<?php echo base_url('index.php/find/nomen'); ?>" class="menu_sk"><?php echo msg('nomen'); ?></a>
						</li>
						<li>
							<a href="<?php echo base_url('index.php/find/classes'); ?>" class="menu_sk"><?php echo msg('classes'); ?></a>
						</li>
						<?php } ?>																
					</ul>
					
				      <ul class="nav navbar-nav navbar-right">
				      	<?php
                        if (isset($_SESSION['check'])) {
                            //echo '<li class="active"><a href="#">' . $_SESSION['nome'] . '</a></li>';
                            echo ' <li class="dropdown">
							          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">' . UpperCase($_SESSION['nome']) . '<span class="caret"></span></a>
							          <ul class="dropdown-menu">
							          	<!--
							            <li><a href="#">Action</a></li>
							            <li><a href="#">Another action</a></li>
							            <li><a href="#">Something else here</a></li>
							            <li role="separator" class="divider"></li>
							            <li><a href="#">Separated link</a></li>
							            <li role="separator" class="divider"></li>
							            -->
							            <li><a href="' . base_url('index.php/skos/login/out') . '">' . msg('logout') . '</a></li>
							          </ul>
							        </li>';
                        } else {
                            echo '<li style="background-color: orange;"><a href="' . base_url('index.php/skos/login') . '" style="color: white;">' . msg('sign_in') . '</a></li>';
                        }
				        ?>
				      </ul>
				</div>
			</div>
		</div>
	</nav>