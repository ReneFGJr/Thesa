<?php

require("acesso.php");
?>
<nav class="navbar navbar-expand-lg d-print-none" style="border-bottom: 2px solid <?= $bg_color; ?>">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="<?= getenv("app.baseURL"); ?>/img/favicons/favicon-32x32.png" style="height: 32px;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <?php
                    if (isset($_SESSION['th'])) {
                        echo '
                            <a class="nav-link" href="' . PATH . '/th/' . $_SESSION['th'] . '">Home</a>
                        ';
                    } else {
                        echo '
                            <a class="nav-link" aria-current="page" href="#">Home</a>
                        ';
                    }
                    ?>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= getenv("app.baseURL"); ?>/thopen"><?= lang('thesa.ThOpen'); ?></a>
                </li>
                <?php
                if (isset($_SESSION['th'])) {
                    echo '
                        <li class="nav-item">
                            <a class="nav-link" href="' . getenv("app.baseURL") . '/admin/terms/add">' . lang('thesa.add_terms') . '</a>
                        </li>';
                    echo '
                        <li class="nav-item">
                            <a class="nav-link" href="' . PATH . '/th/' . $_SESSION['th'] . '">' . lang('thesa.th_atual') . '</a>
                        </li>';

                    $Collaborators = new \App\Models\Thesa\Collaborators();
                    if ($Collaborators->own($_SESSION['th'])) {
                        echo '
                        <li class="nav-item">
                            <a class="nav-link" href="'.PATH.'/admin/config">'.lang('thesa.Configurations').'</a>
                        </li>';
                    }
                }
                ?>

            </ul>
            <form class="d-flex" role="search">
                <input class="form-control me-2 " type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-secondary" type="submit">Search</button>
            </form>
            <?php echo $acesso; ?>
        </div>
    </div>
</nav>