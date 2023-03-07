<?php
echo view('Theme/Standard/Foot');
?>

<footer id="footer" class="mt-1 container-fluid" style="min-height: 50px; background-color: #EEE; border-color: #00EFEF;">
    <div class="row">
        <div class="col-12 border-bottom border-secondary mt-3">
        </div>
    </div>
    <div class=" row">
            <div class="col-1">
                <a href="<?= PATH; ?>">
                    <img src="<?= URL; ?>/img/logo/logo_thesa.svg" class="img-fluid p-1 mt-5">
                </a>
            </div>
            <div class="col-1 mt-5">
                <span class="small">
                    &copy;2017-<?= date("Y"); ?>
                </span>
            </div>

            <div class="col-4 text-end mt-5">
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