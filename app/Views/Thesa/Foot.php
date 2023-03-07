<?php
echo view('Theme/Standard/Foot');
?>

<footer id="footer" class="mt-1 container-fluid" style="min-height: 50px; background-color: #FFF; border-top: 10px solid #EFEFEF;">
    <div class="row">
        <div class="col-1">
            <a href="<?= PATH; ?>">
                <img src="<?= URL; ?>/img/logo/logo_thesa.svg" class="img-fluid">
            </a>
        </div>
        <div class="col-1">
            <span class="small">
                &copy;2017-<?= date("Y"); ?>
            </span>
        </div>

        <div class="col-4 text-end">
            <?= anchor('https://github.com/ReneFGJr/Thesa', '<img src="' . URL . '/img/logo/github.svg" style="height:25px;">'); ?>
        </div>

        <div class="col-2 text-end">
        </div>

        <div class="col-2">
            <img class="img-fluid p-4" src="<?= URL; ?>/img/logo/logo_orcalab.jpeg" class="float-right">
        </div>
        <div class="col-2">
            <img class="img-fluid p-4" src="<?= URL; ?>/img/logo/logo_ppgcin.png" class="float-right">
        </div>
    </div>
</footer>