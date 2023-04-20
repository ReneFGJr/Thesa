<div class="container">
    <div class="row">
        <div class="col-md-3 p-1 text-center box handle" onclick="location.href='<?= PATH . '/thopen'; ?>';">
            <span class="small"><b><?= lang('thesa.Thesaurus'); ?></b></span>
            <br>
            <span class="big"><b><?= $nr_thesaurus; ?></b></span>
        </div>
        <div class="col-md-3 p-1 text-center box handle" onclick="location.href='<?= PATH . '/search'; ?>';">
            <span class="small"><b><?= lang('thesa.Concepts'); ?></b></span>
            <br>
            <span class="big"><b><?= $nr_concepts; ?></b></span>
        </div>
        <div class="col-md-3 p-1 text-center box handle" onclick="location.href='<?= PATH . '/search'; ?>';">
            <span class="small"><b><?= lang('thesa.Terms'); ?></b></span>
            <br>
            <span class="big"><b><?= $nr_terms; ?></b></span>
        </div>
    </div>
</div>