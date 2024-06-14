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
                <li><a href="<?= $router->url('home') ?>">Accueil</a></li>
                <li><a href="">S'incrire</a></li>
                <li><button>Connexion</button></li>
                <li>
                    <form method="post" action="">
                        <button type="submit">Deconnexion</button>
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</header>