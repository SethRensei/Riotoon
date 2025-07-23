<?php

use Riotoon\Entity\{Chapter, Webtoon};
use Riotoon\Repository\{ChapterRepository, WebtoonRepository};
use Riotoon\Service\BuilderError;

$id = (int) $params['id'];
$is_admin = true;

$chapter = new Chapter();

$repository = new ChapterRepository();

/** @var Webtoon|false */
$webtoon = (new WebtoonRepository())->fetchByID(value: $id);

if ($webtoon === false)
    throw new Exception("Aucun webtoon n'a été trouvé");

$pg_title = html_entity_decode($webtoon->getTitle()) . " | RioToon - Administration";
$errors = [];
$success = false;

if (isset($_POST['validate'], $_FILES['file-zip']['name'])) {
    if ($_POST['ch-num'] >= 0 and !empty($_FILES['file-zip']['name'])) {
        $directory = "../public/images/chapitres/" . $webtoon->getId() . '_' . replace(unClean($webtoon->getTitle()), '') . "/";
        // SIZE 24Mo
        $path = uploadFile($_FILES['file-zip'], "Chap_" . $_POST['ch-num'], '201326592', $directory, ['zip', 'cbz']);
        $chapter->setWebtoon($webtoon->getId())
            ->setNumber($_POST['ch-num'])
            ->setPath(str_replace('../public/', '', $path));
        $errors = BuilderError::getErrors();
        if(!$repository->isChapter($chapter)) {
            if (!empty($errors))
                http_response_code(422);
            else if (empty($errors) and move_uploaded_file($_FILES['file-zip']['tmp_name'], $path)) {
                $repository->new($chapter);
                $_POST = [];
                $success = true;
                if ($success == true) {
                    header('Content-Type: text/vnd.turbo-stream.html; charset=utf-8');
                    echo '<turbo-stream action="append" target="messages"><template>';
                    echo messageFlash('success', 'Vous avez ajouté le chapitre ' . $chapter->getNumber() .' pour ' . unClean($webtoon->getTitle()));
                    echo '</template></turbo-stream>';
                    exit;
                }
            } else
                BuilderError::setErrors('file', "Ajout du chapitre échoué");
        } else
            BuilderError::setErrors('exist', 'Ce chapitre existe déjà');
    } else
        BuilderError::setErrors('empty', 'N° Chap inférieur à 0 ou tous les champs vides');
}
$errors = BuilderError::getErrors();
if (!empty($errors))
    http_response_code(422);
?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh">
    <form class="border shadow p-3 rounded" method="post" enctype="multipart/form-data" style="width: 450px;">
        <h1 class="text-center p-3">Ajouter un chapitre</h1>
        <div id="messages">
        </div>
        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $err): ?>
                <?= messageFlash('warning', $err) ?>
            <?php endforeach ?>
        <?php endif ?>
        <div class="mb-3">
            <label class="form-label">N° chapitre</label>
            <input type="number" min="0" class="form-control" name="ch-num" value="<?= clean($_POST['ch-num'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Fichier (Zip ou Cbz)</label>
            <input type="file" name="file-zip" class="form-control">
        </div>

        <div class="col-10 mb-2">
            <button type="submit" name="validate" class="btn btn-outline-primary btn-lg">Ajouter</button>
        </div>
    </form>
</div>