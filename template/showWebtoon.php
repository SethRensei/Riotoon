<?php

use Riotoon\Entity\Webtoon;
use Riotoon\Repository\WebtoonRepository;

//Récupération des paramaètres dans l'url, id et le titre du scans
$title = $params['title'];
$id = (int) $params['id'];

$repository = new WebtoonRepository();

/** @var Webtoon|false */
$webtoon = $repository->fetchOne("id", $id);

//Vérification sur le paramètre id de l'url pour un résultat faux
if ($webtoon === false)
    throw new Exception("Aucun webtoon n'a été trouvé");

//Mettre le titre récupérer dans la base de données semblable au params['title']
$is_title = goodURL(html_entity_decode($webtoon->getTitle()));
//Vérification sur lre paramètre title de l'url sur la correspondance avec la base de données
if ($is_title !== $title) {
    $url = $router->url('show-webt', ['title' => $is_title, 'id' => $webtoon->getId()]);
    http_response_code(301);
    header('Location: ' . $url);
}

?>

<div class="page-content">
    <section class="one-webt">
        <div class="webt-img user-select-none">
            <img src="<?= BASE_URL . $webtoon->getCover() ?>">
        </div>
        <div class="webt-info">
            <h4><?= html_entity_decode($webtoon->getTitle()) ?></h4>
            <div class="info">
                <div class="rio-v">
                    <p>Auteur : <?= html_entity_decode($webtoon->getAuthor()) ?></p>
                </div>
                <hr>
                <p>Sortie en : <?= $webtoon->getReleaseYear() ?></p>
                <hr>
                <p>Statut : <?= $webtoon->getStatus() ?></p>
                <hr>
                <p>Genres : <?= $webtoon->getGenres() ?></p>
                <hr>
                <p class="synopsis">Synopsis : <?= html_entity_decode($webtoon->getSynopsis()) ?></p>
            </div>
        </div>
    </section>
    <section class="last-chapters user-select-none">
        <div class="mb-3">
            <h5 style="font-size: 28px;">Liste des chapitres</h5>
            <hr style="width: 90%;">
        </div>
    </section>
</div>