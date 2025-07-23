<?php

use Riotoon\Entity\Webtoon;
use Riotoon\Service\{Pagination, URL};

$paginator = new Pagination("SELECT * FROM webtoon");
$page = URL::getPositiveInt('page', 1);

/** @var Webtoon*/
$webtoons = $paginator->getData(Webtoon::class, 10, $page);
?>

<div class="page-content">
    <div class="webt-list">
        <?php foreach ($webtoons as $webtoon): ?>
                <a href="<?= $router->url('webt_show', ['id' => $webtoon->getId(), 'title' => goodURL($webtoon->getTitle())]) ?>">
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
</div>