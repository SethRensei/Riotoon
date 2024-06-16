<nav class="navbar navbar-light bg-dark fixed-top">
    <div class="container-fluid">
        <img src="<?= BASE_URL ?>Images/favicons/logo.svg" alt="Mon logo" width="320" height="60" style="color: black;">
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
            aria-controls="offcanvasNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title text-black">Seth Rensei</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body text-bg-dark">
                <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                    <li class="nav-item">
                        <a class="nav-link <?= $pg_title === "Page d'accueil | RioToon - Administration" ? "active" : "" ?>"
                            href="<?= $router->url("home-admin") ?>">Accueil</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= $pageTitle === "Ajouter un scan | RioToon - Administration" || $pageTitle === "Inscription | RioToon - Administration" ? "active" : "" ?>"
                            href="#" id="offcanvasNavbarDropdown" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Actions
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="offcanvasNavbarDropdown">
                            <li><a class="dropdown-item <?= $pg_title === "Ajouter un Webtoon | RioToon - Administration" ? "active" : "" ?>"
                                    href="<?= $router->url("add-webt") ?>">Ajouter un Webtoon</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<!-- <div style="margin-top: 95px;"></div> -->