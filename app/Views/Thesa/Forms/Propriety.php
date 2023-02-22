<?= form_open(); ?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <input type="hidden" name="id_pcst" id="id_pcst" value="<?= $id_pcst; ?>">
            <input type="hidden" name="pcst_th" id="pcst_th" value="<?= $pcst_th; ?>">
            <h1>Propriety - #ADMIN</h1>
        </div>

        <div class="col-12">
            <label>
                <?= lang('thesa.p_name'); ?>
                <?php
                if (isset($p_name)) {
                    echo '<span class="text-red">' . $pcst_name . '</span>';
                }
                ?>
            </label>
            <input type="text" name="pcst_name" id="pcst_name" class="form-control" value="<?= get("pcst_name"); ?>" />
        </div>
        <!--------- PROPs------------->
        <?php

        for ($t = 1; $t <= 3; $t++) {
            $ln = $select_prop[$t];
            echo '<div class="col-4">' . cr();
            echo '<label>', lang('thesa.p_part_' . $t) . '</label>' . cr();
            echo '<select name="pcst_part_' . $t . '" id="pcst_part_' . $t . '" class="form-control">' . cr();
            $vlr = get('pcst_part_' . $t);
            for ($r = 0; $r < count($ln); $r++) {
                $line = $ln[$r];
                $sel = '';
                if ($vlr == $line['id_pt']) {
                    $sel = 'selected';
                }
                echo '<option value="' . $line['id_pt'] . '" ' . $sel . '>' . lang('thesa.prop_' . $line['pt_name']) . '</option>' . cr();
            }
            echo '</select>' . cr();
            echo '</div>' . cr();
        }
        ?>

        <div class="col-6">
            <label><?= lang('thesa.th_achronic'); ?>
                <?php
                if (isset($th_achronic)) {
                    echo '<span class="text-red">' . $pcst_achronic . '</span>';
                }
                ?>
            </label>
            <input type="text" name="pcst_achronic" id="pcst_achronic" class="form-control" value="<?= get("pcst_achronic"); ?>" />

            <label><?= lang('thesa.th_status'); ?></label>
            <select name="pcst_aplicable" id="pcst_aplicable" class="form-control">
                <?php
                $val = get("pcst_aplicable");
                $opt = array('1' => 'Restrict', '2' => 'Public');
                for ($r = 1; $r <= count($opt); $r++) {
                    $sel = '';
                    if ($val == $r) {
                        $sel = 'selected';
                    }
                    echo '<option value="' . $r . '" ' . $sel . '>' . lang('thesa.' . $opt[$r]) . '</option>';
                }
                ?>
            </select>

            <label><?= lang('thesa.th_class'); ?></label>
            <select name="pcst_class" id="pcst_class" class="form-control">
                <?php
                $val = get("pcst_class");
                $opt = array('1' => 'AltLabel', '2' => 'Related');
                for ($r = 1; $r <= count($opt); $r++) {
                    $sel = '';
                    if ($val == $r) {
                        $sel = 'selected';
                    }
                    echo '<option value="' . $r . '" ' . $sel . '>' . lang('thesa.' . $opt[$r]) . '</option>';
                }
                ?>
            </select>

            <label><?= lang('thesa.th_type'); ?></label>
            <select name="pcst_type" id="pcst_type" class="form-control">
                <?php
                $val = get("pcst_type");
                $opt = array('4' => 'Ontology', '3' => 'Thesaurus', '2' => 'Taxonomy', '1' => 'Classification');
                for ($r = 1; $r <= count($opt); $r++) {
                    $sel = '';
                    if ($val == $r) {
                        $sel = 'selected';
                    }
                    echo '<option value="' . $r . '" ' . $sel . '>' . lang('thesa.' . $opt[$r]) . '</option>';
                }
                ?>
            </select>
            <br />

        </div>

        <div class="col-6">
            <label><?= lang('thesa.p_description'); ?></label>
            <textarea name="pcst_description" id="pcst_description" class="form-control" rows="8"><?= get("pcst_description"); ?></textarea>
        </div>

        <div class="col-6">
            <input type="submit" name="action" id="action" value="<?= lang('thesa.btn_save'); ?>" class="btn btn-outline-primary" />
            <a href="<?= getenv("app.baseURL"); ?>/admin/" class="btn btn-outline-danger"><?= lang('thesa.btn_return'); ?></a>
        </div>
    </div>
</div>
<?= form_close(); ?>
<?= date("Y-m-d H:i:s"); ?>