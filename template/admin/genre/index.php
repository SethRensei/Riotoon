<?php

use Riotoon\Entity\Genre;
use Riotoon\Repository\{GenreRepository, WebtoonRepository};

$is_admin = true;
$active = 'genre';

$repository = new GenreRepository();
$webt = new WebtoonRepository();

/** @var Genre|null*/
$genres = $repository->getAllGenresWithWebtoonCount();
$webt_count = count($webt->findAll());

$pg_title = "Tous les genres de Webtoon disponible | RioToon - Administration";

?>

<div style="margin-top: 95px; overflow-x:hidden;">
    <h1 style="font-size: var(--font-h-x);">Listes des genres de Webtoon <a href="<?= $router->url('add-genre')?>"><i class="fas fa-plus-circle"></i></a></h1>
    <table class="table table-striped table-borderless mt-3">
        <thead>
            <tr class="table-dark">
                <th>ID</th>
                <th>Nom</th>
                <th>Nombre de Webtoons</th>
                <th colspan="2">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($genres as $genre): ?>
                <tr>
                    <td translate="no">#<?= $genre->getId() ?></td>
                    <td translate="no"><?= unClean($genre->getLabel()) ?></td>
                    <td translate="no"><?= $genre->getWebtoonCount() . ' / ' . $webt_count ?></td>
                    <td><a href="<?= $router->url('edit-genre', ['id' => $genre->getId()]) ?>" class="text-warning ms-2">
                        <i class="fas fa-solid fa-pencil"></i></a>
                    </td>
                    <td>
                        <form action="<?= $router->url('del-genre', ['id' => $genre->getId()]) ?>" method="post"
                            onsubmit="return confirm('Voulez-vous vraiment éffectuer cette action ?')">
                            <button type="submit" class="text-danger ms-2" style="background: transparent;">
                                <i class="fa-solid fa-trash-can"></i></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>