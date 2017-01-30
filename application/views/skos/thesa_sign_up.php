<!--Pulling Awesome Font -->
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

<div class="container" style="margin-top: 100px;">
	<div class="row">
		<div class="col-md-offset-3 col-md-9">
			<div class="form-login">
				<form method="post" id="form">
					<h4 class="thesa"><?php echo msg('Welcome_signup'); ?>
					THESA</h4>
					<span><?php echo msg('fullName'); ?></span>
					<input type="text" name="fullName" id="fullName" class="form-control input-sm chat-input" placeholder="<?php echo msg('fullName'); ?>" value="<?php get("fullName");?>" />
					</br>
										
					<span><?php echo msg('Username'); ?></span>
					<input type="text" name="userName" id="userName" class="form-control input-sm chat-input" placeholder="<?php echo msg('Username'); ?>" value="<?php get("userName");?>" />
					</br>
					<span><?php echo msg('Password'); ?></span>
					<input type="password" name="userPassword" id="userPassword" class="form-control input-sm chat-input" placeholder="<?php echo msg('Password'); ?>" />
					</br>
					<div class="wrapper">
						<span class="group-btn" id="signup"> <a href="#" class="btn btn-primary btn-md"><?php echo msg('Sign Up Send'); ?>
							<i class="fa fa-sign-in"></i></a> </span>

						<span class="group-btn" id="return"> <a href="#" class="btn btn-default btn-md"><?php echo msg('Return'); ?>
							<i class="fa fa-sign-in"></i></a> </span>
					</div>
				</form>
				<br>
				<br>
				<!--
				<ul class="roboto">
					<li>
						Ainda não sou cadastrado
					</li>
					<li>
						Esqueci minha senha
					</li>
				</ul>
				-->
			</div>
		</div>

	</div>
</div>

<script>
	$("#signup").click(function() {
		$("#form").submit();
	});
	$("#return").click(function() {
		$(location).attr('href', '<?php echo base_url('index.php/skos/login');?>');
	});	
</script>

<style>
	body {
		-webkit-font-smoothing: antialiased;
	}

	.container {
		padding: 25px;
		position: fixed;
	}

	.form-login {
		background-color: #EDEDED;
		padding-top: 5px;
		padding-bottom: 5px;
		padding-left: 20px;
		padding-right: 20px;
		border-radius: 15px;
		border-color: #d2d2d2;
		border-width: 5px;
		box-shadow: 0 1px 0 #cfcfcf;
	}

	h4 {
		border: 0 solid #fff;
		border-bottom-width: 1px;
		padding-bottom: 10px;
		text-align: center;
	}

	.form-control {
		border-radius: 10px;
	}

	.wrapper {
		text-align: left;
	}
</style>
