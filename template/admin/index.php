<?php

use Riotoon\Entity\Webtoon;
use Riotoon\Repository\WebtoonRepository;

$is_admin = true;

$pg_title = "Page d'accueil | RioToon - Administration";

$repository = new WebtoonRepository();

/** @var Webtoon */
$webtoons = $repository->findAll();

?>
<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 10px;
        text-align: left;
        border: 2px solid black;
    }

    th {
        background-color: #342628;
        color: white;
    }

    tr {
        background-color: #c0c0c0;
    }

    tr td a {
        text-decoration: none;
    }

    tr form {
        display: inline;
    }

    tr:nth-child(odd) {
        background-color: #A3B4C8;
    }

    .table-responsive td #add {
        color: #61a476;
    }

    .table-responsive td #edit {
        color: #FFA101;
    }

    .table-responsive td #remove {
        color: #F21137;
    }

    @media only screen and (max-width: 878px) {

        .table-responsive table,
        .table-responsive thead,
        .table-responsive tbody,
        .table-responsive tr,
        .table-responsive th,
        .table-responsive td {
            display: block;
        }

        .table-responsive thead {
            display: none;
        }

        .table-responsive td {
            padding-left: 150px;
            position: relative;
            margin-top: -1;
            /*ainsi pour pouvoir fisionner les bordures*/
            background-color: #fff;
            font-size: .8rem !important;
            font-weight: 800;
        }

        .table-responsive td:nth-child(odd) {
            background-color: #cdcAd6;
        }

        .table-responsive td::before {
            content: attr(data-label);
            /*il récupére la valeur de l'attribut data-label*/
            position: absolute;
            top: 0;
            left: 0;
            width: 130px;
            bottom: 0;
            color: white;
            background-color: #342628;
            display: flex;
            justify-content: center;
            padding: 10px;
            font-weight: bold;
        }

        .table-responsive tr {
            margin-bottom: 1.2rem;
            /*une marge en bas de une fois la taille de la police*/
        }
    }
</style>
<form class="container mb-4" style="margin-top: 95px;">
    <div class="form-group mb-2">
        <input type="text" name="q" class="form-control" placeholder="Rechercher par titre"
            value="<?= clean($_GET['q'] ?? '') ?>">
    </div>
    <button class="btn btn-primary">Rechercher</button>
</form>
<?php if (isset($_GET['add_webtoon'])): ?>
    <?= messageFlash('success', 'Vous avez ajouté un webtoon') ?>
<?php endif ?>
<?php if (isset($_GET['edit_webt'])): ?>
    <?= messageFlash('success', 'Modification du webtoon réussi') ?>
<?php endif ?>
<table class="table-responsive">
    <thead>
        <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Auteur</th>
            <th>Statut</th>
            <th>Genres</th>
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
                <td data-label="Genres"><?= excerpt($webtoon->getGenres(), 18)?></td>
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