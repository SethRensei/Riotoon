<?php
use Riotoon\Entity\{Chapter, Webtoon};
use Riotoon\Repository\{ChapterRepository, VoteRepository, WebtoonRepository};

//Récupération des paramaètres dans l'url, id et le titre du scans
$title = $params['title'];
$id = (int) $params['id'];


/** @var Webtoon|false */
$webtoon = (new WebtoonRepository())->fetchByID($id);

//Vérification sur le paramètre id de l'url pour un résultat faux
if ($webtoon === false)
    throw new Exception("Aucun webtoon n'a été trouvé");

//Mettre le titre récupérer dans la base de données semblable au params['title']
$is_title = goodURL($webtoon->getTitle());
//Vérification sur le paramètre title de l'url sur la correspondance avec la base de données
if ($is_title !== $title) {
    $url = $router->url('webt_show', ['title' => $is_title, 'id' => $webtoon->getId()]);
    http_response_code(301);
    header('Location: ' . $url);
}

$pg_title = unClean($webtoon->getTitle()) . ' | RioToon';
/** @var Chapter|null */
$chapters = ChapterRepository::findWebtoon($webtoon->getId());

// Voting system
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
                <div class="d-flex justify-content-between">
                    <p class="col-md-7">Auteur : <?= unClean($webtoon->getAuthor()) ?></p>
                    <div id="vote" class="col-md-5 d-flex justify-content-end votes <?= $vote ?>" data-id="<?= $webtoon->getId() ?>"
                        data-ref="<?= $router->url('vote') ?>">
                        <button class="like me-4"><span class="rio-m-9">
                                (<span id="like-count"><?= $webtoon->getLikes() ?></span>)
                            </span><i class="fas fa-thumbs-up"></i>
                        </button>
                        <button class="dislike"><span class="rio-m-9">
                                (<span id="dislike-count"><?= $webtoon->getDislikes() ?></span>)
                            </span><i class="fas fa-thumbs-down"></i>
                        </button>
                    </div>
                </div>
                <hr>
                <p>Statut : <?= $webtoon->statusValid()[$webtoon->getStatus()] ?></p>
                <hr>
                <p>Genres : <?= str_replace(',', ', ', $webtoon->getCategories()) ?></p>
                <hr>
                <div id="synop-wrapper" class="show-wrapper">
                    <div class="synop-text">
                        <h2 style="font-weight: 600; font-size:larger;">Synopsis</h2>
                        <?= $webtoon->getSynopsis() ?>
                    </div>
                    </div>
                </div>
                <span id="toggle-show" class="see-more">Voir plus ▼</span>
            </div>
        </div>
    </section>
    <section class="last-chapters user-select-none">
        <div class="mb-3">
            <h5 style="font-size: 28px;">Liste des chapitres</h5>
            <hr style="width: 90%;">
        </div>
        <div id="table-wrapper" class="table-resp--web table-responsive">
            <table class="table table-web">
                <tbody>
                    <?php foreach ($chapters as $chapter): ?>
                        <tr>
                            <td class="ps-4">
                                <a
                                    href="<?= $router->url('read', ['id' => $webtoon->getId(), 'title' => $is_title, 'chapt' => $chapter->getNumber()]) ?>">Chapitre <?= $chapter->getNumber() ?></a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
        <span id="toggle-show2" class="see-more">Voir plus ▼</span>
    </section>
</div>