<?php
    $img_th = date("s");
    $img_th = $img_th % 4 + 1;
?>
<style>
    .parallax_background_1 {
        /* The image used */
        background-image: url("<?php echo URL.'img/background/background_'.$img_th.'.jpg';?>");
    }

.parallax {
    background-attachment: fixed;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
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
        background-color: hsla(0, 0%, 100%, .6);
        margin: 20px 0;
    }
</style>

<!-- Container element -->
<div class="parallax parallax_background_1" style="height: 280px;">
    <div class="container">
        <div class="row">
            <div class="col-md-5 text-center" style="margin-top: 50px;">
                <span class="logo_thesa_text">THESA</span>
                <br>
                <span class="logo_thesa_text_sub">Tesauro Semântico Aplicado</span>
            </div>
            <div class="col-md-2 text-center" style="margin-top: 50px;">
            </div>
            <div class="col-md-5 text-center" style="margin-top: 50px;">
                <span style="color: white;" class="h1"><?php echo lang('thesa.welcome_paralax_title');?></span><br/>
                <span style="color: white;"><?php echo lang('thesa.welcome_paralax_description');?></span>
                <div class="separador">
                    <div class="separator-line"></div>
                    <span style="color: white;">Já tem uma conta?</span>
                    <div class="separator-line"></div>
                </div>
                <a href="<?php echo URL.MODULE.'social/login';?>" style="text-decoration: none;">
                    <div style="padding: 10px; background-color: #ffffff; text-decoration: none; border-radius: 20px; color: black; font-size: 12px; font-family: Titillium+Web;"><?php echo lang('thesa.signup');?></div>
                </a>
            </div>
        </div>
    </div>
</div>