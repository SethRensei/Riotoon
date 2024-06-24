<?php

use Riotoon\DbConnexion;
use Riotoon\Entity\{User, Webtoon};
use Riotoon\Repository\UserRepository;

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

if (isset($_POST['login'])) {
    $user_repository = new UserRepository();
    if (!empty($_POST['user']) and !empty($_POST['password'])) {
        /** @var User|false */
        $user = $user_repository->find($_POST['user']);
        // dd($user);
        if ($user != false) {
            if (password_verify($_POST['password'], $user->getPassword())) {
                if ($user->getIsVerified() == 1) {
                    $_SESSION['User'] = $user->getId();
                    $_SESSION['pseudo'] = $user->getPseudo();
                    $_SESSION['fullname'] = unClean($user->getFullname());
                    $_SESSION['roles'] = $user->getCollectionsRoles();
                    echo "<script>window.history.back();</script>";
                } else {
                    $_SESSION['user_register'] = 'Veuillez confirmer votre compte';
                    header('Location:' . $router->url('verif', ['pseudo' => $user->getPseudo()]));
                }
            } else {
                echo "<script>window.history.back();</script>";
                echo "<script>alert('Identifiant incorrect');</script>";
            }
        } else{
            echo "<script>window.history.back();</script>";
            echo "<script>alert('Identifiant incorrect');</script>";
        }
    } else {
        echo "<script>window.history.back();</script>";
        echo "<script>alert('Saisir tous les champs');</script>";
    }
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
                    <li><button class="btn-login"><i class="fa-solid fa-right-to-bracket"></i> Connexion</button></li>
                <?php endif ?>
                <?php if (isset($_SESSION['User'])): ?>
                    <li><a href="<?= $router->url('profile', ['pseudo' => goodURL($_SESSION['pseudo'])])?>"><i class="fas fa-user"></i> Profil</a></li>
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

<div class="wrapper">
    <div class="content-login">
        <div class="form-box login">
            <span class="icon-close"><i class="fas fa-times"></i></span>
            <h2>Connexion</h2>
            <form method="post" id="rio-login">
                <div class="input-box">
                    <i class="fas fa-user"></i>
                    <input type="text" name="user" placeholder="Pseudo ou Email">
                    <span class="bar"></span>
                </div>
                <div class="input-box">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" id="password">
                    <span class="bar"></span>
                    <div class="view">
                        <i class="fas fa-eye"></i>
                        <i class="fas fa-eye-slash"></i>
                    </div>
                </div>
                <div class="ms-3"><a href="">Mot de passe oublié</a></div>
                <button name="login" type="submit" class="btn">Se connecter</button>
            </form>
        </div>
    </div>
</div>
<?php endif ?>