<?php
echo view('Theme/Standard/Foot');
?>

<footer id="footer" class="mt-1 container-fluid" style="min-height: 50px; background-color: #fff; border-top: 1px solid #eee;">
    <div class=" row">
        <div class="col-6  col-sm-1 mt-2">
            <a href="<?= PATH; ?>">
                <img src="<?= URL; ?>/img/logo/logo_thesa.svg" class="img-fluid p-1">
            </a>
        </div>
        <div class="col-6 col-sm-7 mt-2 text-end">
            <span class="small">
                &copy;2017-<?= date("Y"); ?>
            </span>
        </div>

        <div class="col-4  col-sm-2 text-end mt-2">
            <?= anchor('https://github.com/ReneFGJr/Thesa', '<img src="' . URL . '/img/logo/github.svg" style="height:25px;">'); ?>
        </div>

        <div class="col-4 col-sm-1">
            <img class="img-fluid p-0 mt-2 float-right" src="<?= URL; ?>/img/logo/logo_orcalab.jpeg">
        </div>
        <div class="col-4 col-sm-1">
            <img class="img-fluid p-0 mt-2 float-right" src="<?= URL; ?>/img/logo/logo_ppgcin.png">
        </div>
    </div>
</footer>