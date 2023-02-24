<span class="btn btn-outline-secondary" style="width: 150px;">
    <?= lang('thesa.Concepts'); ?>: <span class="big"><?= $nr_concepts; ?></span>
</span>
<span class="btn btn-outline-secondary" style="width: 150px;">
    <?php
    if ($access) {
        echo anchor(PATH . '/admin/terms/create_concept', lang('thesa.Terms'). ': <span class="big">' . $nr_terms . '</span>');
    } else {
        echo lang('thesa.Terms');
        echo ': <span class="big">' . $nr_terms.'</span>';
    }
    ?>
</span>
</a>
</span>
<br /><br />