<?php
$link_user = PATH . '/user/' . $id_us;
?>
<div class="Box-row clearfix d-flex flex-items-center p-1 mb-2" style="background: #FFF; border-radius: 20px;">
    <div class="mx-3">
        <a href="<?= $link_user ?>">
            <img class="avatar avatar-user" data-hovercard-type="user" data-hovercard-url="/users/henriquefernandes95/hovercard" data-octo-click="hovercard-link-click" data-octo-dimensions="link_type:self" src="https://avatars.githubusercontent.com/u/18196158?s=64&amp;v=4" width="32" height="32" alt="@henriquefernandes95">
        </a>
    </div>

    <div class="d-flex flex-column flex-auto col-6">
        <a href="<?= $link_user; ?>"><strong><?= $us_nome; ?></strong></a>
        <span class="f6 color-fg-muted">
            <?= $us_login; ?> * <?= $us_email; ?>
        </span>
    </div>

    <div class="d-flex flex-items-center col-3">
    </div>

    <div class="col-3 d-flex flex-justify-end">

        <!-- '"` -->
        <!-- </textarea></xmp> -->
        <details class="dropdown details-reset details-overlay">
            <summary aria-haspopup="menu" data-view-component="true" class="btn-sm btn" role="button">
            <span class="text-normal color-fg-muted"><?= lang('thesa.perfil'); ?>: </span>
                <span data-menu-button="" class="css-truncate css-truncate-target">
                    <?= lang($pf_name); ?>
                </span>
                <br />
                <span class="text-danger"><?= bsicone('trash', 12); ?> excluir</span>
            </summary>
        </details>
    </div>
</div>