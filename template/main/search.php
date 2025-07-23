<?php
use Riotoon\Service\DbConnection;

/** @var \PDO $pdo */
$pdo = DbConnection::GetConnection();

$q = trim($_GET['q'] ?? '');
if ($q === '') {
    // Rien à afficher
    exit;
}

// Query with ILIKE (PostgreSQL) or LIKE depending on your DBMS
$stmt = $pdo->prepare("
    SELECT id, title, cover
      FROM webtoon
     WHERE title   LIKE :sear
        OR author  LIKE :sear
     LIMIT 6
");
$stmt->bindValue(':sear', "%".clean($q)."%");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($rows)) {
    echo '<li style="color:white">Aucune correspondance</li>';
    exit;
}

foreach ($rows as $row) {
    $url = $router->url('webt_show', [
        'id' => $row['id'],
        'title' => goodURL($row['title'])
    ]);
    $title = unClean($row['title']);
    $cover = BASE_URL . unClean($row['cover']);

    echo <<<HTML
<li>
  <img src="{$cover}" alt="Couverture">
  <a href="{$url}">{$title}</a>
</li>
<hr>
HTML;
}
