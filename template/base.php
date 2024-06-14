<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="<?= $pg_desc ?? "RioToon | Lecture et Téléchargement de Webtoon" ?>">
    <meta name="keywords" content="Webtoons, Webtoon VF, Webtoon Gratuit, Télécharger Webtoon, Lire Webtoon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pg_title ?? 'RioToon | Lecture et Téléchargement de Webtoon' ?></title>
    
    <link rel="stylesheet" href="<?= BASE_URL ?>styles/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>styles/css/style-color.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>styles/css/styles.css">
</head>
<body>
    <?php
    if (isset($is_admin) && $is_admin == true)
        require_once '_partials/navbarAdmin.php';
    else
        require_once '_partials/navbar.php';
    ?>

    <div class="container-lg">
        <?= $pg_content?>
    </div>
    <script src="<?=BASE_URL?>styles/js/jquery.js"></script>
    <script src="<?=BASE_URL?>styles/js/main.js"></script>
</body>
</html>