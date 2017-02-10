<!--Pulling Awesome Font -->
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

<div class="container" style="margin-top: 100px;">
	<div class="row">
		<div class="col-md-offset-3 col-md-4">
			<div class="form-login">
				<form method="post" id="form">
					<h4 class="thesa logo_title logo_mini"><?php echo UpperCase(msg('Welcome')); ?>
					THESA</h4>
					<span><?php echo msg('Username'); ?></span>
					<input type="text" name="userName" id="userName" class="form-control input-sm chat-input" placeholder="<?php echo msg('Username'); ?>" value="<?php get("userName");?>" />
					</br>
					<span><?php echo msg('Password'); ?></span>
					<input type="password" name="userPassword" id="userPassword" class="form-control input-sm chat-input" placeholder="<?php echo msg('Password'); ?>" />
					</br>
					<span><?php echo $email_ok;?></span>
					<div class="wrapper">
						<span class="group-btn" id="signin"> <a href="#" class="btn btn-primary btn-md"><?php echo msg('Sign In'); ?>
							<i class="fa fa-sign-in"></i></a> </span>

						<span class="group-btn" id="signup"> <a href="#" class="btn btn-default btn-md"><?php echo msg('Sign Up'); ?>
							<i class="fa fa-sign-in"></i></a> </span>
					</div>
				</form>
				<br>
				<br>
			</div>
		</div>

	</div>
</div>

<script>
	$("#signin").click(function() {
		$("#form").submit();
	});
	$("#signup").click(function() {
		$(location).attr('href', '<?php echo base_url('index.php/skos/login_sign_up');?>');
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
