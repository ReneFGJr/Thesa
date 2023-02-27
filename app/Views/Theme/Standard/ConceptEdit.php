<div class="container">
    <div class="row">
        <div class="col-2 border-bottom border-secondary"><?= $action; ?></div>
        <div class="col-10 border-bottom border-secondary"><?= $header; ?></div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-4">
            <?php
            foreach ($forms as $tag => $content) {
                $onclick = ' onclick="newwin(\'' . PATH . '/admin/popup_' .
                    $tag . '/' . $id . '\',600,600);"';
                $plus = '<span class="text-primary ms-1 handle" ' . $onclick . '>' . bsicone('plus') . '</span>';
                echo '<div class="border-bottom border-secondary mb-1 mt-1 pb-2">';
                echo h(lang('thesa.' . $tag) . $plus, 6, 'lora');
                echo $content . '</div>';
            }
            ?>
        </div>

        <div class="col-4">
            <?php
                foreach($relations as $tag=>$line)
                    {
                        $onclick = ' onclick="newwin(\'' . PATH . '/admin/popup_' .
                            $tag . '/' . $id . '\',800,600);"';
                        $plus = '<span class="text-primary ms-1 handle" ' . $onclick . '>' . bsicone('plus') . '</span>';

                        if (($tag == 'broader') and ($line != '')) { $plus = ''; }

                        echo h(lang('thesa.' . $tag) . $plus, 6, 'lora mt-1');
                        echo '<div class="border-bottom border-secondary mb-1 mt-1 pb-2">';
                        echo $line;
                        echo '</div>';

                    }

            ?>

            <?php
            $tag = 'midia';
            $onclick = ' onclick="newwin(\'' . PATH . '/admin/popup_' .
                $tag . '/' . $id . '\',600,300);"';
            $plus = '<span class="text-primary ms-1 handle" ' . $onclick . '>' . bsicone('plus') . '</span>';
            echo h(lang('thesa.' .$tag) . $plus, 6, 'lora');
            ?>
            <?php
            echo $midias;
            ?>
        </div>

        <div class="col-4">
            <?php
            foreach ($notes as $tag => $content) {
                $onclick = ' onclick="newwin(\'' . PATH . '/admin/notes/' .
                    $tag . '/' . $id . '\',600,600);"';
                $plus = '<span class="text-primary ms-1 handle" ' . $onclick . '>' . bsicone('plus') . '</span>';
                echo '<div class="border-bottom border-secondary">';
                echo h(lang('thesa.' . $tag) . $plus, 6, 'lora mt-1 mb-1');
                echo '<p class="notes">';
                echo $content;
                echo '</p>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</div>