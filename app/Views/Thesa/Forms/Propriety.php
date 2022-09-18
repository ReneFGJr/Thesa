<?= form_open(); ?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>Propriety - #ADMIN</h1>
            <input type="hidden" name="id_th" id="id_th" value="<?= get("th_name"); ?>">
        </div>

        <div class="col-12">
            <label>
                <?= lang('thesa.p_name'); ?>
                <?php
                if (isset($p_name)) {
                    echo '<span class="text-red">' . $p_name . '</span>';
                }
                ?>
            </label>
            <input type="text" name="th_name" id="th_name" class="form-control" value="<?= get("th_name"); ?>" />
        </div>
        <!--------- PROPs------------->
        <?php

        for ($t=1;$t <= 3;$t++)
            {
                $ln = $select_prop[$t];
                echo '<div class="col-4">' . cr();
                echo '<label>',lang('thesa.p_part_'.$t). '</label>' . cr();
                echo '<select name="p_part_'.$t.'" id="p_part_'.$t.'" class="form-control">'.cr();

                for ($r=0;$r < count($ln);$r++)
                    {
                        $line = $ln[$r];
                        echo '<option value="'.$line['id_pt'].'">'. lang('thesa.prop_'.$line['pt_name']).'</option>'.cr();
                    }
                echo '</select>'.cr();
                echo '</div>'.cr();
            }
        ?>

        <div class="col-6">
            <label><?= lang('thesa.th_achronic'); ?>
                <?php
                if (isset($th_achronic)) {
                    echo '<span class="text-red">' . $th_achronic . '</span>';
                }
                ?>
            </label>
            <input type="text" name="th_achronic" id="th_achronic" class="form-control" value="<?= get("th_achronic"); ?>" />
            <br />

            <label><?= lang('thesa.th_status'); ?></label>
            <select name="th_status" id="th_status" class="form-control">
                <?php
                $val = get("th_status");
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
            <br />

            <label><?= lang('thesa.th_type'); ?></label>
            <select name="th_type" id="th_type" class="form-control">
                <?php
                $val = get("th_type");
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
            <label><?= lang('thesa.p_description	'); ?></label>
            <textarea name="p_description	" id="p_description	" class="form-control" rows="8"><?= get("p_description	"); ?></textarea>
        </div>

        <div class="col-6">
            <input type="submit" value="<?= lang('thesa.btn_save'); ?>" class="btn btn-outline-primary" />
            <a href="<?= getenv("app.baseURL"); ?>/admin/" class="btn btn-outline-danger"><?= lang('thesa.btn_return'); ?></a>
        </div>
    </div>
</div>
<?= form_close(); ?>
<?= date("Y-m-d H:i:s"); ?>