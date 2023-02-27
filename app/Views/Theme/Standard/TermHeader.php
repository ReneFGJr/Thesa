<div class="container">
    <div class="row">
        <div class="col-9">
            <span class="none lora big" id="cpl"><?= $prefLabel; ?></span>
            <sup><?= $lang; ?></sup>
            <img src="<?= URL . '/img/icons/copy.png'; ?>" height="16" style="margin-top: -30px;" class="handle" onclick="text2clipboard('cpl');">

            <br />
            <?= $edit; ?>
            <i class="supersmall">URI:</i>
            <input id="cpc" class="none small" type=" text" name="cpc" value="<?= $url; ?>">
        </div>
        <div class="col-3">
            <?= $others; ?>
        </div>
    </div>
</div>
