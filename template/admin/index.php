<?php

use Riotoon\Entity\Webtoon;
use Riotoon\Repository\{ChapterRepository, GenreRepository, UserRepository, WebtoonRepository};

$is_admin = true;
$active = 'home';

$pg_title = "Page d'accueil | RioToon - Administration";

$repository = new WebtoonRepository();

/** @var Webtoon */
$webtoons = $repository->findAll();

$webt_count = count($webtoons);
$user_count = count((new UserRepository())->findAll());
$chap_count = count((new ChapterRepository())->findAll());
$genr_count = count((new GenreRepository())->findAll());

?>
<form class="container mb-4" style="margin-top: 95px;">
    <div class="form-group mb-2">
        <input type="text" name="q" class="form-control" placeholder="Rechercher par titre"
            value="<?= clean($_GET['q'] ?? '') ?>">
    </div>
    <button class="btn btn-primary">Rechercher</button>
</form>
<div class="card-box">
    <div class="rio-card">
        <div>
            <div class="numbers"><?= $user_count?></div>
            <div class="card-name">Utilisateurs</div>
        </div>
        <div class="icon-bx">
            <i class="fas fa-user-group"></i>
        </div>
    </div>
    <div class="rio-card">
        <div>
            <div class="numbers"><?= $webt_count ?></div>
            <div class="card-name">Webtoons</div>
        </div>
        <div class="icon-bx">
            <i class="fas fa-book-open-reader"></i>
        </div>
    </div>
    <div class="rio-card">
        <div>
            <div class="numbers"><?= $chap_count ?></div>
            <div class="card-name">Chapitres</div>
        </div>
        <div class="icon-bx">
            <i class="fas fa-bookmark"></i>
        </div>
    </div>
    <div class="rio-card">
        <div>
            <div class="numbers"><?= $genr_count?></div>
            <div class="card-name">Genres de webtoon</div>
        </div>
        <div class="icon-bx">
            <i class="fas fa-layer-group"></i>
        </div>
    </div>
</div>
<?php if (isset($_GET['add_webtoon'])): ?>
    <?= messageFlash('success', 'Vous avez ajouté un webtoon') ?>
<?php endif ?>
<?php if (isset($_GET['edit_webt'])): ?>
    <?= messageFlash('success', 'Modification du webtoon réussi') ?>
<?php endif ?>
<?php if (isset($_GET['del'])): ?>
    <?= messageFlash('success', 'Suppression du webtoon réussi') ?>
<?php endif ?>
<table class="table-responsive">
    <thead>
        <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Auteur</th>
            <th>Statut</th>
            <th>Likes</th>
            <th>Dislikes</th>
            <th>Sortie</th>
            <th colspan="3">Actions</th>
        </tr>
    </thead>
    <tbody class="text-dark">
        <?php foreach ($webtoons as $webtoon): ?>
            <tr>
                <td data-label="ID" translate="no">#<?= $webtoon->getId()?></td>
                <td data-label="Titre" translate="no"><?= unClean($webtoon->getTitle())?></td>
                <td data-label="Auteur"><?= excerpt($webtoon->getAuthor())?></td>
                <td data-label="Statut"><?= $webtoon->getStatus() ?></td>
                <td data-label="Likes"><?= $webtoon->getLikes() ?></td>
                <td data-label="Likes"><?= $webtoon->getDislikes() ?></td>
                <td data-label="Sortie"><?= $webtoon->getReleaseYear() ?></td>
                <td data-label="Action1"><a href="<?= $router->url('add-chap', ['id' => $webtoon->getId()]) ?>"><i
                            id="add" class="fas fa-sharp fa-solid fa-plus"></i></a></td>
                <td data-label="Action2"><a href="<?= $router->url('edit-webt', ['id' => $webtoon->getId()]) ?>"><i
                            id="edit" class="fas fa-solid fa-pencil"></i></a></td>
                <td data-label="Action3">
                    <form action="<?= $router->url('del-webt', ['id' => $webtoon->getId()]) ?>" method="post"
                        onsubmit="return confirm('Voulez-vous vraiment éffectuer cette action ?')"><button type="submit"
                            style="border: none; background: transparent;"><i id="remove" class="fa-solid fa-trash-can"></i></button>
                    </form>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>
<?= tableStyle()?>