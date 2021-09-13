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
<div class="container">
    <div class="row">
        <div class="col-2"></div>
        <div class="col-8" style="padding: 20px;">
            <div class="box100 text-center">
            <img src="<?php echo base_url('img/icone/checked.jpg'); ?>">
            <h1><?php echo msg('signup_success'); ?></h1>
            <p>
                <?php 
                    $txt = msg('signup_success_msg');
                    $txt = troca($txt,'$name',$us_nome);
                    $txt = troca($txt,'$email',$us_email);
                    echo $txt; 
                ?>
            </p>
            </div>
        </div>
        <div class="col-2"></div>
    </div>
</div>