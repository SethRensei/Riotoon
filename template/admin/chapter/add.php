<?php

use Riotoon\Entity\Webtoon;
use Riotoon\Repository\ChapterRepository;
use Riotoon\Repository\WebtoonRepository;
use Riotoon\Service\BuildErrors;

$id = (int) $params['id'];
$is_admin = true;

$repository = new ChapterRepository();
$r_webt = new WebtoonRepository();

/** @var Webtoon|false */
$webtoon = $r_webt->fetchOne("id", $id);

if ($webtoon === false)
    throw new Exception("Aucun webtoon n'a été trouvé");

$pg_title = html_entity_decode($webtoon->getTitle()) . " | RioToon - Administration";
$errors = [];
if (isset($_POST['validate'], $_FILES['file-zip']['name'])) {
    if (!empty($_POST['ch-num']) and !empty($_FILES['file-zip']['name'])) {
        $name = strtolower($_POST['ch-num']);
        $directory = "../public/images/chapitres/" . $webtoon->getId() .replace(unClean($webtoon->getTitle()), '') . "/";
        $path = uploadFile($_FILES['file-zip'], $name, '20971520', $directory, ['zip', 'cbz']);
        $repository->setWebtoon($webtoon->getId())
            ->setChNum($_POST['ch-num'])
            ->setChPath(str_replace('../public/', '', $path));
        $errors = BuildErrors::getErrors();
        
        if (empty($errors) and move_uploaded_file($_FILES['file-zip']['tmp_name'], $path)) {
            $repository->add();
            $_POST = [];
            $success = true;
        } else
            BuildErrors::setErrors('fail', "Ajout échoué");
    } else
        BuildErrors::setErrors('empty', 'Veuillez remplir tous les champs');
}
$errors = BuildErrors::getErrors();
?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh">
    <form class="border shadow p-3 rounded" method="post" enctype="multipart/form-data" style="width: 450px;">
        <h1 class="text-center p-3">Ajouter un chapitre</h1>
        <?php if (isset($success) and $success === true): ?>
            <?= messageFlash('success', 'Vous avez ajouté un webtoon') ?>
        <?php endif ?>
        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $err): ?>
                <?= messageFlash('warning', $err) ?>
            <?php endforeach ?>
        <?php endif ?>
        <div class="mb-3">
            <label class="form-label">N° chapitre</label>
            <input type="text" class="form-control" name="ch-num" value="<?= clean($_POST['ch-num'] ?? '')?>" placeholder="Ch-1">
        </div>
        <div class="mb-3">
            <label class="form-label">Images (Zip ou Cbz)</label>
            <input type="file" name="file-zip" class="form-control">
        </div>

        <div class="col-10 mb-2">
            <button type="submit" name="validate" class="btn btn-outline-primary btn-lg">Ajouter</button>
        </div>
    </form>
</div>