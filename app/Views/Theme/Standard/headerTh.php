<div class="container mb-4">
    <div class="row">
        <div class="col-1 p-2 text-end">
            <a href="<?php echo PATH . '/th/' . $id_th; ?>">
                <img src="<?= $icone; ?>" class="img-fluid">
            </a>
        </div>

        <div class="col-11">
            <small><?= lang("thesa.ThesauruName"); ?></small>
            <a href="<?php echo PATH . '/th/' . $id_th; ?>" style="text-decoration: none;">
                <h1 class="m-0 p-0" style="line-height: 0.8;"><?= $th_name; ?></h1>
                <h4 class=" m-0 p-0 pt-1" style="line-height: 0.8"><?= $th_achronic; ?></h4>
            </a>
        </div>
    </div>
</div>