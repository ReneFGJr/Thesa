<br>
<br>
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

<div class="container" style="margin-top: 0px; margin-bottom: 50px;">
    <div class="row">
        <div class="col-4"></div>
        <div class="col-4" style="padding: 20px;">
            <div class="box100 text-center">
            <div class="alert alert-danger" role="alert">
                This is a danger alert—check it out!
            </div>                
            <img src="<?php echo base_url('img/icone/error.png'); ?>" class="img-fluid">
            <h1><?php echo msg('erro'); ?></h1>
            <p>
                <?php 
                    echo $content; 
                ?>
            </p>
            </div>
        </div>
        <div class="col-4"></div>
    </div>
</div>