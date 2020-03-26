<style>
    .box100 {
        border: 2px solid #cccccc;
        border-radius: 5px;
        padding: 20px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    }
    .form_title {
        font-size: 300%;
        line-height: 100%;
    }
</style>
<div class="container" style="margin-top: 100px;">
	<div class="row">
		<div class="col-md-4"></div>
		<div class="col-md-4 box100">
			<form method="post" action="<?php echo base_url(PATH.'social/forgot');?>">
				<span class="form_title"> BRAPCI <sup>2.0</sup> </span>
				<br/>
				<span class="login100-form-title p-b-48"> <i class="zmdi zmdi-font">information science database</i> </span>
				<br/>
				<br/>
				<center>
                <h2><?php echo msg('Forgot');?></h2>
                </center>
                
				<div class="" data-validate = "Valid email is: a@b.c">
				    <span><?php echo msg('e-mail'); ?></span>
					<input class="form-control" type="text" name="user_login">
					<span class="focus-input100" data-placeholder="Email"></span>
				</div>
				<br/>
				<br/>
				<div class="">
					<div class="">
						<input type="submit" class="btn btn-primary" style="width: 100%;" value="<?php echo msg("login_recover_password");?>">
					</div>
				</div>

                <br>
				<div class="text-center p-t-115">
					<a class="txt2" href="<?php echo base_url(PATH.'social/login');?>"> <?php echo msg('return');?> </a>
				</div>
				<br>
			</form>
		</div>
		<div class="col-md-4"></div>
	</div>
</div>

<script>
	(function($) {
		"use strict";

		/*==================================================================
		 [ Focus input ]*/
		$('.input100').each(function() {
			$(this).on('blur', function() {
				if ($(this).val().trim() != "") {
					$(this).addClass('has-val');
				} else {
					$(this).removeClass('has-val');
				}
			})
		})
		/*==================================================================
		 [ Validate ]*/
		var input = $('.validate-input .input100');

		$('.validate-form').on('submit', function() {
			var check = true;

			for (var i = 0; i < input.length; i++) {
				if (validate(input[i]) == false) {
					showValidate(input[i]);
					check = false;
				}
			}

			return check;
		});

		$('.validate-form .input100').each(function() {
			$(this).focus(function() {
				hideValidate(this);
			});
		});

		function validate(input) {
			if ($(input).attr('type') == 'email' || $(input).attr('name') == 'email') {
				if ($(input).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/) == null) {
					return false;
				}
			} else {
				if ($(input).val().trim() == '') {
					return false;
				}
			}
		}

		function showValidate(input) {
			var thisAlert = $(input).parent();

			$(thisAlert).addClass('alert-validate');
		}

		function hideValidate(input) {
			var thisAlert = $(input).parent();

			$(thisAlert).removeClass('alert-validate');
		}

		/*==================================================================
		 [ Show pass ]*/
		var showPass = 0;
		$('.btn-show-pass').on('click', function() {
			if (showPass == 0) {
				$(this).next('input').attr('type', 'text');
				$(this).find('i').removeClass('zmdi-eye');
				$(this).find('i').addClass('zmdi-eye-off');
				showPass = 1;
			} else {
				$(this).next('input').attr('type', 'password');
				$(this).find('i').addClass('zmdi-eye');
				$(this).find('i').removeClass('zmdi-eye-off');
				showPass = 0;
			}

		});

	})(jQuery); 
</script>