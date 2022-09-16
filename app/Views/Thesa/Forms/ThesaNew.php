<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>Thesa - #ADMIN</h1>
        </div>

        <div class="col-12">
            <label><?= lang('thesa.th_name'); ?></label>
            <input type="text" name="th_name" id="th_name" class="form-control" value="<?= get("th_name"); ?>" />
        </div>

        <div class="col-6">
            <label><?= lang('thesa.th_achronic'); ?></label>
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
            <label><?= lang('thesa.th_description'); ?></label>
            <textarea name="th_description" id="th_description" class="form-control" rows="8"><?= get("th_description"); ?></textarea>
        </div>

        <div class="col-6">
            <input type="submit" value="<?= lang('thesa.btn_save'); ?>" class="btn btn-outline-primary" />
            <a href="<?=getenv("app.baseURL");?>/admin/" class="btn btn-outline-danger"><?= lang('thesa.btn_return'); ?></a>
        </div>
    </div>
</div>