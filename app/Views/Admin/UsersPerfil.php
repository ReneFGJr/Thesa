<?php
$link_user = PATH . '/user/' . $id_us;
?>
<div class="col-5 mt-2"><?= $us_nome; ?></div>
<div class="col-5 mt-2"><?= $us_email; ?></div>
<div class="col-1 mt-2 text-end"><?= lang($pf_name); ?>
    <?=confirm(PATH.'/admin/collaborators/del/'. $id_th_us.'/confirm',"A");?><?= bsicone('trash', 12); ?></a>
</div>