<?php

use Riotoon\DbConnexion;
use Riotoon\Entity\Webtoon;

$pdo = DbConnexion::connection();

if (isset($_POST['search'])) {

    if (!empty($_POST['search'])) {
        $search = clean($_POST['search']);
        $query = $pdo->prepare("SELECT id, title, cover FROM webtoon WHERE title LIKE ? OR author LIKE ? LIMIT 6");
        $query->execute(['%' . $search . '%', '%' . $search . '%']);

        /** @var Webtoon|false */
        $rows = $query->fetchAll(PDO::FETCH_CLASS, Webtoon::class);

        if ($rows == false)
            echo '<li style="color: white;">Aucune correspondance trouvée</li>';

        foreach ($rows as $row) {
            echo '<li><img src="' . BASE_URL . $row->getCover() . '">' . '<a href="' . $router->url('show-webt', ['id' => $row->getId(), 'title' => goodURL($row->getTitle())]) . '">' . $row->getTitle() . '</a></li><hr>';
        }
        exit();
    } else
        exit();
}

?>
<?php if (!isset($navbar)) : ?>
<header>
    <div class="header-1">

        <a href="<?= $router->url('home') ?>" class="logo"> <img src="<?= BASE_URL ?>images/favicons/logo.svg">
        </a>

        <div class="s">
            <form method="post">
                <input type="text" placeholder="Titre, auteur" id="search">
                <label for="search" class="fas fa-search"></label>
            </form>
            <div id="resultat">
                <ul>
                    <?php foreach ($rows as $row):?>
                    <li><?= $row->getTitle?></li>
                    <?php endforeach?>
                </ul>
            </div>
        </div>
    </div>

    <div class="header-2">

        <div id="menu" class="fas fa-bars"></div>

        <nav class="nav_bar">
            <ul>
                <li><a href="<?= $router->url('home') ?>"><i class="fas fa-home"></i> Accueil</a></li>
                <?php if (!isset($_SESSION['User'])): ?>
                    <li><a href="<?= $router->url('signup')?>"><i class="fas fa-user-plus"></i> S'incrire</a></li>
                    <li><button><i class="fa-solid fa-right-to-bracket"></i> Connexion</button></li>
                <?php endif ?>
                <?php if (isset($_SESSION['User'])): ?>
                    <li><a href=""><i class="fas fa-user"></i> Profil</a></li>
                    <li>
                        <form method="post" action="<?= $router->url('logout') ?>">
                            <button type="submit">Deconnexion</button>
                        </form>
                    </li>
                <?php endif ?>
            </ul>
        </nav>
    </div>
</header>
<?php endif ?>