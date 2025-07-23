<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name="description" content="<?= $pg_desc ?? "RioToon | Read and Download Webtoons" ?>">
    <meta name="keywords" content="Webtoons, Webtoon VF, Free Webtoon, Download Webtoon, Read Webtoon">
    <title><?= $pg_title ?? 'RioToon | Read and Download Webtoons' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= BASE_URL ?>styles/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>styles/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>styles/css/navbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?= BASE_URL ?>styles/fontawesome/css/all.min.css">
    <script type="module" src="<?= BASE_URL ?>styles/js/hotwired/turbo.js"></script>
    <script type="module" src="<?= BASE_URL ?>styles/js/hotwired/stimulus.js"></script>
    <script src="<?= BASE_URL ?>styles/js/app.js" type="module"></script>
</head>
<body>
    <?php
        if (isset($is_admin) && $is_admin == true)
            require_once '_partial/navbar-admin.php';
        else
            require_once '_partial/navbar.php';
    ?>
    
    <div class="container-lg main">
        <?= $pg_content?>        
    </div>
    <script src="<?= BASE_URL ?>styles/js/jquery.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <script src="<?= BASE_URL ?>styles/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>