<style>
    .white, .white a, .white a:hover  
        {
            color: #ffffff;
            text-decoration: none;
        }
	.parallax {
	
		/* Create the parallax scrolling effect */
		background-attachment: fixed;
		background-position: center;
		background-repeat: no-repeat;
		background-size: cover;
	}

	.parallax_background_1 {
		/* The image used */
		background-image: url("<?php echo base_url('/img/background/background_4.jpg');?>");
	}

	.parallax_background_2 {
		/* The image used */
		background-image: url("<?php echo base_url('img/background/background_2.jpg');?>");
	}
	
    .parallax_background_3 {
        /* The image used */
        background-image: url("<?php echo base_url('img/background/background_3.jpg');?>");
    }
    	
	.logo_thesa_text
		{
			margin-top : 50px;
			font-size: 850%;
			line-height: 100%;
			color: #ffffff;
			font-family: "Roboto Thin";
		}
	.logo_thesa_text_sub
		{
			font-size: 150%;
			line-height: 50%;
			color: #ffffff;
			font-family: "Roboto Thin";
		}
	.box
	{
	    margin-top: 40px;
        border: 5px solid #00000;
        background-color: #C0C0C0; opacity: 0.7;
        border-radius: 10px;
        margin: 10px;	    
	}
</style>

<!-- Container element -->
<div class="parallax parallax_background_1" style="height: 350px;">
	<div class="container">
		<div class="row">
			<div class="col-md-5 text-center" style="margin-top: 100px;">
				<span class="logo_thesa_text">THESA</span>
				<br>
				<span class="logo_thesa_text_sub">Semantic Thesaurus</span>
			</div>
		</div>
	</div>
</div>

