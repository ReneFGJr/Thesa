<span class="btn btn-outline-secondary mb-2" style="width: 150px;">
    <span class="small"><?= lang('thesa.Concepts'); ?></span>
    <br />
    <span class="big"><?= $nr_concepts; ?></span>
</span>
<span class="btn btn-outline-secondary mb-2" style="width: 150px;">
    <?php
    if ($access) {
        echo anchor(PATH . '/admin/terms/create_concept', '<span class="small">' . lang('thesa.Terms') . '</span><br><span class="big">' . $nr_terms . '</span>');
    } else {
        echo '<span class="small">' . lang('thesa.Terms') . '</span>';
        echo '<br><span class="big">' . $nr_terms . '</span>';
    }
    ?>
</span>
<?php
if (($access) and ($nr_terms_candidates > 0)) {
    echo '<span class="btn btn-outline-secondary mb-2" style="width: 150px;">';
    echo anchor(PATH . '/admin/terms/create_concept', '<span class="small">' . lang('thesa.Terms_candidate') . '</span><br>' . '<span class="big">' . $nr_terms_candidates . '</span>');
    echo '</span>';
}
?>

<span class="btn btn-outline-secondary mb-2" style="width: 150px;">
    <span class="small"><?= lang('thesa.Export'); ?></span>
    <br />
    <a href="<?= PATH . '/export/' . $th; ?>/pdf" class=" btn-outline-secondary">
        <span class="big btn-outline-secondary"><b>PDF</b></span>
    </a>
</span>
<br /><br />