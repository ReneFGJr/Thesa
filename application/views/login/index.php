<link rel="stylesheet" href="<?php echo base_url('css/style_login.css');?>">

  <div class="login-wrap">
	<div class="login-html">
		<form method="post">
		<input id="tab-1" type="radio" name="tab" class="sign-in" checked><label for="tab-1" class="tab"><?php echo msg('SIGN IN');?></label>
		<input id="tab-2" type="radio" name="tab" class="sign-up"><label for="tab-2" class="tab"><?php echo msg('SIGN UP');?></label>
		<div class="login-form">
			<div class="sign-in-htm">
				<div class="group">
					<label for="user" class="label"><?php echo msg('Username');?></label>
					<input id="user" name="user" type="text" class="input">
				</div>
				<div class="group">
					<label for="pass" class="label"><?php echo msg('Password');?></label>
					<input id="pass" name="password" type="password" class="input" data-type="password">
				</div>
				<div class="group">
					<input id="check" type="checkbox" class="check" checked>
					<label for="check"><span class="icon"></span> <?php echo msg('Keep me Signed in');?></label>
				</div>
				<div class="group">
					<input type="submit" class="button" value="<?php echo msg('Sign In');?>">
				</div>
				<div class="hr"></div>
				<div class="foot-lnk">
					<a href="#forgot"><?php echo msg('Forgot Password');?>?</a>
				</div>
			</div>
			<div class="sign-up-htm">
				<div class="group">
					<label for="user" class="label">Username</label>
					<input id="user" type="text" class="input">
				</div>
				<div class="group">
					<label for="pass" class="label">Password</label>
					<input id="pass" type="password" class="input" data-type="password">
				</div>
				<div class="group">
					<label for="pass" class="label">Repeat Password</label>
					<input id="pass" type="password" class="input" data-type="password">
				</div>
				<div class="group">
					<label for="pass" class="label">Email Address</label>
					<input id="pass" type="text" class="input">
				</div>
				<div class="group">
					<input type="submit" class="button" value="Sign Up">
				</div>
				<div class="hr"></div>
				<div class="foot-lnk">
					<label for="tab-1">Already Member?</a>
				</div>
			</div>
		</div>
		</form>
	</div>
</div>