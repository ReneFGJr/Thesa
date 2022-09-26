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
                    <a class="nav-link" aria-current="page" href="<?= getenv("app.baseURL"); ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= getenv("app.baseURL"); ?>/admin">Admin (home)</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= getenv("app.baseURL"); ?>/admin/thesaurus">Thesaurus</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= getenv("app.baseURL"); ?>/admin/terms">Terms</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= getenv("app.baseURL"); ?>/admin/proprity">Proprity</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= getenv("app.baseURL"); ?>/admin/ontology">Ontology</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= getenv("app.baseURL"); ?>/admin/config"><?=lang('thesa.Configurations');?></a>
                </li>
            </ul>
            <form class="d-flex" role="search">
                <input class="form-control me-2 " type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-secondary" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>