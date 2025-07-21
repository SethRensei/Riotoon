<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name="description" content="<?= $pg_desc ?? "RioToon | Read and Download Webtoons" ?>">
    <meta name="keywords" content="Webtoons, Webtoon VF, Free Webtoon, Download Webtoon, Read Webtoon">
    <title><?= $pg_title ?? 'RioToon | Read and Download Webtoons' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= BASE_URL ?>styles/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>styles/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>styles/css/navbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?= BASE_URL ?>styles/fontawesome/css/all.min.css">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js" integrity="sha512-ykZ1QQr0Jy/4ZkvKuqWn4iF3lqPZyij9iRv6sGqLRdTPkY69YX6+7wvVGmsdBbiIfN/8OdsI7HABjvEok6ZopQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="<?= BASE_URL ?>styles/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>