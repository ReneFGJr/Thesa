<span class="btn btn-outline-secondary" style="width: 150px;">
    <?= lang('thesa.Concepts'); ?>: <span class="big"><?= $nr_concepts; ?></span>
</span>
<span class="btn btn-outline-secondary" style="width: 150px;">
    <?php
    if ($access) {
        echo anchor(PATH . '/admin/terms/create_concept', lang('thesa.Terms') . ': <span class="big">' . $nr_terms . '</span>');
    } else {
        echo lang('thesa.Terms');
        echo ': <span class="big">' . $nr_terms . '</span>';
    }
    ?>
</span>
<?php
if (($access) and ($nr_terms_candidates > 0)) {
    echo '<span class="btn btn-outline-secondary" style="width: 150px;">';
    echo anchor(PATH . '/admin/terms/create_concept', lang('thesa.Terms_candidate') . ': <span class="big">' . $nr_terms_candidates . '</span>');
    echo '</span>';
}
?>

<span class="btn btn-outline-secondary" style="width: 150px;">
    <a href="<?= PATH . '/export/' . $th; ?>">
        <span class="big"><b>PDF</b></span>
    </a>
</span>
<br /><br />