<div class="container" style="margin-top: 100px;">
	<div class="row">
		<div class="col-md-6 col-md-offset-3" style="background-color: #e0e0ff; border-radius: 20px; padding: 20px;">
			<form method="post" id="form">
			<h4 class="thesa logo_title logo_mini"><?php echo UpperCase(msg('Welcome')); ?> THESA</h4>
			<div class="btn-group btn-group-justified" role="group" aria-label="...">
				<span class="glyphicon glyphicon-envelope big" aria-hidden="true" style="width: 40px;"></span>
				<span><?php echo msg('Username'); ?></span>
				</br>
				<input type="text" name="userName" id="userName" class="form-control big" placeholder="<?php echo msg('Username'); ?>" value="<?php get("userName"); ?>" />
			</div>
			</br>
			<div class="btn-group btn-group-justified" role="group" aria-label="...">
				<span class="glyphicon glyphicon-lock big" aria-hidden="true" style="width: 40px;"></span>
				<span><?php echo msg('Password'); ?></span>
				<br>
				<input type="password" name="userPassword" id="userPassword" class="form-control big" placeholder="<?php echo msg('Password'); ?>" />
			</div>	
			</br>
			<span><?php echo $email_ok; ?></span>
			
			<!-- Button trigger modal -->
			<?php if ($error==-9) { ?>
			<div class="alert alert-danger" role="alert">
				  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
				  <span class="sr-only">Error:</span>
				  <?php echo msg("user_invalid_password"); ?>
				 </br>
				<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#myModal">
			  		<?php echo msg('resend_password');?>
				</button>				  
			</div>
									

			
			<!-- Modal -->
			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        <h4 class="modal-title" id="myModalLabel"><?php echo msg('resend_password');?></h4>
			      </div>
			      <div class="modal-body">
			        <?php echo msg('resend_password_text');?>
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo msg('cancel');?></button>
			        <button type="button" id="sendmail" class="btn btn-primary"><?php echo msg('send_password');?></button>
			      </div>
			      <div class="modal-footer" id="email" style="display: none;">
			      	<img src="<?php echo base_url('img/bx_loader.gif');?>"> Enviando e-mail para Usuário.
			      </div>
			    </div>
			  </div>
			</div>
			<?php } ?>

			<div class="wrapper">
				<span class="group-btn" id="signin"> <a href="#" class="btn btn-primary btn-md"><?php echo msg('Sign In'); ?>
					<i class="fa fa-sign-in"></i></a> </span>

				<span class="group-btn" id="signup"> <a href="#" class="btn btn-default btn-md"><?php echo msg('Sign Up'); ?>
					<i class="fa fa-sign-in"></i></a> </span>
			</div>
			</form>					
		</div>		
	</div>
</div>

<script>
	$("#signin").click(function() {
		$("#form").submit();
	});
	$("#sendmail").click(function() {
		$("#email").fadeIn("slow");
		$.ajax({
		  url: '<?php echo base_url('index.php/skos/login_ajax/email/');?>',
		  success: function(data) {
		      $('#email').html(data);
		  }
		});
	});	
	$("#signup").click(function() {
		$(location).attr('href', '<?php echo base_url('index.php/skos/login_sign_up'); ?>
			');
			});
</script>
