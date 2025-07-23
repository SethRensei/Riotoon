<header>
    <div class="header-1">
        <a href="<?= $router->url('home') ?>" class="logo"> <img src="<?= BASE_URL ?>favicons/logo.svg">
        </a>
        <div class="s" data-controller="search">
            <form  data-search-target="form" data-action="submit->search#prevent click->search#stopPropagation">
                <input id="search" data-search-target="input" data-action="input->search#onInput" placeholder="Titre, auteur" autocomplete="off">
                <label for="search" class="fas fa-search"></label>
            </form>
            <div id="resultat" data-search-target="container" hidden>
                <ul data-search-target="results"></ul>
            </div>
        </div>
    </div>
    <div class="header-2">
        <div id="menu" class="fas fa-bars"></div>
        <nav class="nav_bar">
            <ul>
                <li><a href="<?= $router->url('home') ?>"><i class="fas fa-home"></i> Accueil</a></li>
                <?php if (!isset($_SESSION['User'])): ?>
                    <li><a href="#"><i class="fas fa-user-plus"></i> S'incrire</a></li>
                    <li><button class="btn-login"><i class="fas fa-right-to-bracket"></i> Connexion</button></li>
                <?php endif ?>
                <?php if (isset($_SESSION['User'])): ?>
                    <li><a href="#"><i class="fas fa-user"></i> Profil</a></li>
                    <?php if (in_array('ROLE_ADMIN', $_SESSION['roles'])): ?>
                    <li><a href="<?= $router->url('admin_index')?>"><i class="fas fa-user-tie"></i></i> Admin</a></li>
                    <?php endif; ?>
                    <li>
                        <form method="post" action="#">
                            <button type="submit">Deconnexion</button>
                        </form>
                    </li>
                <?php endif ?>
            </ul>
        </nav>
    </div>
</header>