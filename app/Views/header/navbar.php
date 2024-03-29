<?php
require("acesso.php");
?>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <!-- Container wrapper -->
    <div class="container-fluid">
        <!-- Toggle button -->
        <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <a href="<?= PATH; ?>">
                <img src="<?= URL . '/img/logo/logo_thesa.svg'; ?>" height="25" alt="Thesa Logo" loading="lazy" />
            </a>
        </button>

        <!-- Collapsible wrapper -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Navbar brand -->
            <a class="navbar-brand mt-2 mt-lg-0" href="<?= PATH; ?>">
                <img src="<?= URL . '/img/logo/logo_thesa.svg'; ?>" height="25" alt="Thesa Logo" loading="lazy" />
            </a>
            <!-- Left links -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="<?= PATH . '/thopen'; ?>"><?= lang("thesa.ThOpen"); ?></a>
                </li>
            </ul>
            <!-- Left links -->
        </div>
        <!-- Collapsible wrapper -->

        <!-- Right elements -->
        <div class="d-flex align-items-center">
            <a class="text-reset me-3" href="<?= PATH . '/about'; ?>"><?= lang("thesa.about"); ?></a>
            <!-- Icon -->
            <?php
            $Thesa = new \App\Models\Thesa\Index();
            if (!isset($th)) {
                $th = 0;
                if (isset($_SESSION['th']))
                    {
                        $th = $_SESSION['th'];
                    }
            }

            if ($th > 0)
            {
                $Collaborators = new \App\Models\Thesa\Collaborators();
                $Admin = $Collaborators->own($th);
                if ($Admin > 0) {
                    echo '<a class="text-reset me-3" href="' . PATH . '/admin/tools' . '">
                        ' . bsicone('process') . '</a>';

                    echo '<a class="text-reset me-3" href="' . PATH . '/admin/config' . '">
                        ' . bsicone('gear') . '</a>';
                } else {
                    //echo '[[[[th:' . $th . ']]]]';
                }
            }
            ?>
            <!-- Socials -->
            <?php echo $acesso; ?>
        </div>
    </div>
    <!-- Right elements -->
    </div>
    <!-- Container wrapper -->
</nav>
<!-- Navbar -->