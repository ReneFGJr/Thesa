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
		min-height: 500px;
		color: #1f1fff;
		background-color: #afafff;
	}
	#section2 {
		padding-top: 50px;
		min-height: 500px;
		color: #1f1fff;
		background-color: #ffffff;
	}
	#section3 {
		padding-top: 50px;
		min-height: 500px;
		color: #1f1fff;
		background-color: #afafff;
	}
	#section4 {
		padding-top: 50px;
		min-height: 500px;
		color: #1f1fff;
		background-color: #ffffff;
	}
	#section5 {
		padding-top: 50px;
		min-height: 500px;
		color: #1f1fff;
		background-color: #afafff;
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
				<a class="navbar-brand" href="<?php echo base_url('index.php/skos#welcome'); ?>"> <span class="logo_title logo_mini"><span class="glyphicon glyphicon-tags" aria-hidden="true" style="font-size: 80%;"></span> THESA</span> </a>
			</div>
			<div>
				<div class="collapse navbar-collapse" id="myNavbar">
					<ul class="nav navbar-nav">
						<li>
							<a href="<?php echo base_url('index.php/thesa/thesa'); ?>" class="menu_sk"><?php echo msg('thesaurus_open'); ?></a>
						</li>
						<? if (isset($_SESSION['check'])) { ?>
						<li>
							<a href="<?php echo base_url('index.php/thesa/myth'); ?>"><?php echo msg('thesaurus_myth'); ?></a>
						</li>
						<? } ?>
						<li>
							<a href="<?php echo base_url('index.php/skos'); ?>#section1"><?php echo msg('presentation'); ?></a>
						</li>
						<li>
							<a href="<?php echo base_url('index.php/skos'); ?>#section2"><?php echo msg('about'); ?></a>
						</li>
						<!--
						<li>
							<a href="<?php echo base_url('index.php/skos'); ?>#section3"><?php echo msg('download'); ?></a>
						</li>
						-->
						<li>
							<a href="<?php echo base_url('index.php/skos'); ?>#section4"><?php echo msg('contact'); ?></a>
						</li>
						<?php if (isset($_SESSION['id']) and (strlen($_SESSION['id']) > 0)) { ?>
						<li>
							<a href="<?php echo base_url('index.php/thesa/bug_report'); ?>" title="<?php echo msg('report_a_bug');?>"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span></a>
						</li>										
						<?php } ?>
						<li>
							<a href="<?php echo base_url('index.php/thesa/user_manual'); ?>#section2"><?php echo msg('MANUAL'); ?></a>
						</li>						
					</ul>
					
				      <ul class="nav navbar-nav navbar-right">
				      	<?php
						if (isset($_SESSION['check'])) {
							//echo '<li class="active"><a href="#">' . $_SESSION['nome'] . '</a></li>';
							echo ' <li class="dropdown">
							          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'.UpperCase($_SESSION['nome']).'<span class="caret"></span></a>
							          <ul class="dropdown-menu">
							          	<!--
							            <li><a href="#">Action</a></li>
							            <li><a href="#">Another action</a></li>
							            <li><a href="#">Something else here</a></li>
							            <li role="separator" class="divider"></li>
							            <li><a href="#">Separated link</a></li>
							            <li role="separator" class="divider"></li>
							            -->
							            <li><a href="'.base_url('index.php/thesa/login_change').'">'.msg('change_my_data').'</a></li>
							            <li><a href="'.base_url('index.php/thesa/login/out').'">'.msg('logout').'</a></li>
							          </ul>
							        </li>';
						} else {
							echo '<li style="background-color: orange;"><a href="' . base_url('index.php/thesa/login') . '" style="color: white;">' . msg('sign_in') . '</a></li>';
						}
				        ?>
				      </ul>
				</div>
			</div>
		</div>
	</nav>