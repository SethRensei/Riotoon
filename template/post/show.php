<?php

use Riotoon\Entity\{Webtoon, Chapter};
use Riotoon\Repository\{ChapterRepository, VoteRepository, WebtoonRepository};

//Récupération des paramaètres dans l'url, id et le titre du scans
$title = $params['title'];
$id = (int) $params['id'];

$repository = new WebtoonRepository();
$r_chapter = new ChapterRepository();

/** @var Webtoon|false */
$webtoon = $repository->fetchOne("id", $id);

//Vérification sur le paramètre id de l'url pour un résultat faux
if ($webtoon === false)
    throw new Exception("Aucun webtoon n'a été trouvé");

//Mettre le titre récupérer dans la base de données semblable au params['title']
$is_title = goodURL($webtoon->getTitle());
//Vérification sur lre paramètre title de l'url sur la correspondance avec la base de données
if ($is_title !== $title) {
    $url = $router->url('show-webt', ['title' => $is_title, 'id' => $webtoon->getId()]);
    http_response_code(301);
    header('Location: ' . $url);
}

$pg_title = unClean($webtoon->getTitle()) . ' | RioToon';

/** @var Chapter|null */
$chapters = $r_chapter->findWebtoon($webtoon->getId());

$vote = false;
$v = new VoteRepository();
$v->updateCount($webtoon->getId());
if (isset($_SESSION['User']))
    $vote = $v->getClass($webtoon->getId(), $_SESSION['User']);
?>

<div class="page-content">
    <section class="one-webt">
        <div class="webt-img user-select-none">
            <img src="<?= BASE_URL . $webtoon->getCover() ?>">
        </div>
        <div class="webt-info">
            <h4><?= unClean($webtoon->getTitle()) ?></h4>
            <div class="info">
                <div class="rio-v">
                    <p>Auteur : <?= unClean($webtoon->getAuthor()) ?></p>
                    <div id="vote" class="votes <?= $vote ?>" data-id="<?= $webtoon->getId() ?>"
                        data-ref="<?= $router->url('vote') ?>">
                        <button class="like"><span class="rio-m-9">(<span id="like-count"><?= $webtoon->getLikes() ?></span>)</span><i
                                class="fas fa-thumbs-up"></i></button>
                        <button class="dislike"><span class="rio-m-9">(<span id="dislike-count"><?= $webtoon->getDislikes() ?></span>)</span><i
                                class="fas fa-thumbs-down"></i></button>
                    </div>
                </div>
                <hr>
                <p>Sortie en : <?= $webtoon->getReleaseYear() ?></p>
                <hr>
                <p>Statut : <?= $webtoon->getStatus() ?></p>
                <hr>
                <p>Genres : <?= $webtoon->getGenres() ?></p>
                <hr>
                <p class="synopsis">Synopsis : <?= unClean($webtoon->getSynopsis()) ?></p>
            </div>
        </div>
    </section>
    <section class="last-chapters user-select-none">
        <div class="mb-3">
            <h5 style="font-size: 28px;">Liste des chapitres</h5>
            <hr style="width: 90%;">
        </div>
        <div class="table-resp--web table-responsive scrollbar">
            <table class="table table-web">
                <tbody>
                    <?php foreach ($chapters as $chapter): ?>
                        <tr>
                            <td class="ps-4">
                                <a
                                    href="<?= $router->url('read', ['id' => $webtoon->getId(), 'title' => $is_title, 'chapt' => $chapter->getChNum()]) ?>"><?= $chapter->getChNum() ?></a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </section>
</div>