<?php
$socials = new socials;
?>
<style>
.parallax_background_1 {
/* The image used */
background-image: url("<?php echo base_url('/img/background/background_4.jpg'); ?>");
}

.parallax_background_2 {
/* The image used */
background-image: url("<?php echo base_url('img/background/background_2.jpg'); ?>
	");
	}
.separador {
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: horizontal;
    -webkit-box-direction: normal;
    -webkit-flex-direction: row;
    -ms-flex-direction: row;
    flex-direction: row;
    -webkit-box-pack: justify;
    -webkit-justify-content: space-between;
    -ms-flex-pack: justify;
    justify-content: space-between;
    -webkit-box-align: center;
    -webkit-align-items: center;
    -ms-flex-align: center;
    align-items: center;
    width: 100%;
    margin-top: 20px;
    margin-bottom: 20px;
   }
.separator-line {
    width: 125px;
    height: 1px;
    background-color: hsla(0,0%,100%,.6);
    margin: 20px 0;
}
</style>
<!-- Container element -->
<div class="parallax parallax_background_1" style="height: 380px;">
	<div class="container">
		<div class="row">
			<div class="col-md-5 text-center" style="margin-top: 100px;">
				<span class="logo_thesa_text">THESA</span>
				<br>
				<span class="logo_thesa_text_sub">Semantic Thesaurus</span>
			</div>
			<div class="col-md-2 text-center" style="margin-top: 100px;">
			</div>
			<div class="col-md-5 text-center" style="margin-top: 100px;">
				<?php
				if ($socials->user_id() > 0)
					{
						
					} else {
						
						echo '<a href="'.base_url('index.php/thesa/social/signup').'" style="text-decoration: none;">';
						echo '<div style="padding: 20px; background-color: #0000ff; text-decoration: none; border-radius: 20px; color: white; font-size: 30px; font-family: Titillium+Web;">'.msg("S I G N &nbsp; U P").'</div>'.cr();
						echo '</a>';
						echo '<div class="separador">';
						echo '<div class="separator-line"></div>';
						echo '<span style="color: white;">'.msg('already_an_account').'</span>';
						echo '<div class="separator-line"></div>';
						echo '</div>';
						echo '<a href="'.base_url('index.php/thesa/social/login').'" style="text-decoration: none;">';
						echo '<div style="padding: 20px; background-color: #ffffff; text-decoration: none; border-radius: 20px; color: black; font-size: 30px; font-family: Titillium+Web;">'.msg("L O G &nbsp; I N").'</div>'.cr();
						echo '</a>';
						
					}
				?>
			</div>
		</div>
	</div>
</div>

