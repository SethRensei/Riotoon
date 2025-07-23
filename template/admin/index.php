<?php

use Riotoon\Entity\Webtoon;
use Riotoon\Repository\{CategoryRepository, WebtoonRepository};

$is_admin = true;
$active = 'home';

$pg_title = "RioToon - Administration";

$repository = new WebtoonRepository();

/** @var Webtoon */
$webtoons = $repository->findAll();

$webt_count = count($webtoons);
$category_count = count((new CategoryRepository())->findAll());
?>

<div class="col-md-10">
    <?php if (isset($_SESSION['success']) && $_SESSION['success'] == true) {
            echo messageFlash('success', $_SESSION['content']);
            unset($_SESSION['content'], $_SESSION['success']);
        }
    ?>
</div>

<table class="table-responsive">
    <thead>
        <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Auteur</th>
            <th>Statut</th>
            <th>Likes</th>
            <th>Dislikes</th>
            <th colspan="3">Actions</th>
        </tr>
    </thead>
    <tbody class="text-dark">
        <?php foreach ($webtoons as $webtoon): ?>
            <tr>
                <td data-label="ID" translate="no">#<?= $webtoon->getId() ?></td>
                <td data-label="Titre" translate="no"><?= unClean($webtoon->getTitle()) ?></td>
                <td data-label="Auteur"><?= excerpt($webtoon->getAuthor()) ?></td>
                <td data-label="Statut"><?= $webtoon->getStatus() ?></td>
                <td data-label="Likes"><?= $webtoon->getLikes() ?></td>
                <td data-label="Likes"><?= $webtoon->getDislikes() ?></td>
                <td data-label="Action1"><a href="#"><i id="add" class="fas fa-sharp fa-solid fa-plus"></i></a></td>
                <td data-label="Action2"><a href="<?= $router->url('webt_edit', ['id' => $webtoon->getId()]) ?>"><i
                            id="edit" class="fas fa-solid fa-pencil"></i></a></td>
                <td data-label="Action3">
                    <form action="<?= $router->url('webt_del', ['id' => $webtoon->getId()]) ?>" method="post"
                        onsubmit="return confirm('Voulez-vous vraiment éffectuer cette action ?')"><button type="submit"
                            style="border: none; background: transparent;"><i id="remove"
                                class="fa-solid fa-trash-can"></i></button>
                    </form>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>
<?= tableStyle() ?>