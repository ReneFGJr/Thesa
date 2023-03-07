<div class="container-fluid">
    <div class="row">
        <div class="col-5">
            <table class="full">
                <?php
                foreach ($values as $prop => $value) {
                    echo '<div class="border-top border-secondary full">';
                    echo '<h6 class="lora mt-2">' . lang('thesa.' . $prop) . '</h6>';
                    echo '<p class="ms-3">'.$value. '</p>';
                    echo '</div>';
                }
                ?>
            </table>
        </div>

        <div class="col-5">
            <?php

            if (isset($broader) and ($broader != ''))
                {
                    echo '<h6 class="lora mt-2">' . lang('thesa.broader') . '</h6>';
                    echo $broader;
                }

            if (isset($narrow) and ($narrow != '')) {
                echo '<h6 class="lora mt-2">' . lang('thesa.narrow') . '</h6>';
                echo $narrow;
            }
            foreach ($notes as $prop => $value) {
                if ($value != '')
                    {
                    echo '<div class="border-top border-secondary full">';
                    echo '<h6 class="lora mt-2">' . lang('thesa.' . $prop) . '</h6>';
                    echo $value;
                    echo '</div>';
                    }
            }
            ?>

        </div>

        <div class="col-2 border-start border-secondary text-end mt-2">
            <?= $midias; ?>
        </div>
    </div>
</div>