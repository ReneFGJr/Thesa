 <h2 class="center"><?= $th_name; ?></h2>
 <h1 class="center"><?= lang("thesa.index_alphabetic"); ?></h1>
 <div class="content">
     <?php
        foreach ($alphabetic as $term => $content) {
            echo '<br/>';
            echo '<h1 class="term_h">' . $term . '</h1>';
            if (isset($content['desc'])) {
                echo '<table border=0 style="margin-left: 15px;">' . $content['desc'] . '</table>';
            }
        }
        ?>
 </div>
 <div class="page_break"></div>