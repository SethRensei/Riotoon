<?php
use Riotoon\Service\{Pagination, URL};
use Riotoon\Entity\Webtoon;

$id = clean($params['id']);
$label = $params['label'];

$paginator = new Pagination("SELECT webtoon.*, GROUP_CONCAT(label) AS genres
    FROM webtoon
    JOIN webtoon_genres ON webtoon.id = webtoon_genres.webtoon
    JOIN genre ON genre.id = webtoon_genres.genre
    WHERE genre.id = {$id}
    GROUP BY webtoon.id");

$page = URL::getPositiveInt('page', 1);

/** @var Webtoon*/
$webtoons = $paginator->getData(Webtoon::class, 10, $page);
?>

<div class="page-content">
    <?php if (!empty($webtoons)): ?>
        <div class="webt-list">
            <?php foreach ($webtoons as $webtoon): ?>
                <a
                    href="<?= $router->url('show-webt', ['id' => $webtoon->getId(), 'title' => goodURL($webtoon->getTitle())]) ?>">
                    <div class="list">
                        <div class="text">
                            <h4><?= excerpt($webtoon->getTitle()) ?></h4>
                        </div>
                        <div class="item">
                            <img src="<?= BASE_URL . $webtoon->getCover() ?>">
                            <div class="text2">
                                <h4><?= excerpt($webtoon->getTitle()) ?></h4>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach ?>
        </div>
        <div class="rio-paginated">
            <?= $paginator->createLinks(3, 'rounded-blocks') ?>
        </div>
    <?php else: ?>
        <div class="webt-list">
            <h1>Aucun webtoons du type <?= $label?></h1>
        </div>
    <?php endif ?>
</div>