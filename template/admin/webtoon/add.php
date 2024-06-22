<?php

use Riotoon\Repository\{GenreRepository, WebtoonRepository};
use Riotoon\Service\BuildErrors;

$is_admin = true;
$active = 'add-webt';
$pg_title = 'Ajouter un webtoon | RioToon - Administration';

$errors = [];
$gen_repo = new GenreRepository();

$genres = $gen_repo->findAll();

$repository = new WebtoonRepository();

if (isset($_POST['validate'], $_FILES['image']['name'])) {
    if (!empty($_POST['title']) and !empty($_POST['author']) and !empty($_FILES['image']['size']) and !empty($_POST['release-year']) and !empty($_POST['status']) and !empty($_POST['synopsis']) and !empty($_POST['genres'])) {
        $name = replace(clean($_POST['title'])) . '-cover_' . rand(1000, 99999);
        $path = uploadFile($_FILES['image'], $name);
        $repository->setTitle($_POST['title'])
        ->setAuthor($_POST['author'])
        ->setReleaseYear($_POST['release-year'])
        ->setStatus($_POST['status'])
        ->setCover(str_replace('../public/', '', $path))
        ->setSynopsis($_POST['synopsis']);
        $errors = BuildErrors::getErrors();
        //voir si l'image a bien été uploadé et qu'il n'y a plus d'erreurs
        if (empty($errors and move_uploaded_file($_FILES['image']['tmp_name'], $path))) {
            $id = $repository->add();
            foreach ($_POST['genres'] as $v)
                $gen_repo->addWebtoonGenre($id, $v);
            $_POST = [];
            header('Location:' . $router->url('home-admin') . '?add_webtoon=true');
        } else
            BuildErrors::setErrors('fail', "Ajout des données échoué");
    } else
        BuildErrors::setErrors('empty', 'Veuillez remplir tous les champs');
}

$errors = BuildErrors::getErrors();

?>

<div class="fs-4" style="margin-top: 95px;">
    <?php if (!empty($errors)): ?>
        <?php foreach ($errors as $err): ?>
            <?= messageFlash('warning', $err) ?>
        <?php endforeach ?>
    <?php endif ?>
    <h1 class="mb-3">Ajouter un Webtoon</h1>
    <form method="post" class="row g-3 mt-2 justify-content-center" enctype="multipart/form-data">
        <div class="col-md-5 form-group">
            <label class="form-label">Titre<i class="text-danger">*</i></label>
            <input type="text" name="title" value="<?= $_POST['title'] ?? '' ?>" class="form-control"
                placeholder="ex: the beginning after the end">
        </div>
        <div class="col-md-5 mb-3">
            <label class="form-label">Auteur<i class="text-danger">*</i></label>
            <input type="text" name="author" value="<?= $_POST['author'] ?? '' ?>" class="form-control"
                placeholder="ex: TurtleMe">
        </div>
        <div class="col-md-3 form-group">
            <label class="form-label">Sortie en<i class="text-danger">*</i></label>
            <input type="tel" maxlength="4" pattern="[0-9-() ]*" minlength="2" name="release-year"
                value="<?= $_POST['release-year'] ?? '' ?>" class="form-control">
        </div>
        <div class="col-md-4">
            <label class="form-label">Photo<i class="text-danger">*</i></label>
            <input type="file" name="image" class="form-control">
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Statut<i class="text-danger">*</i></label>
            <select class="form-select" name="status">
                <option value="En cours" select>En cours</option>
                <option value="Terminé">Terminé</option>
            </select>
        </div>
        <div class="col-md-10">
            <label class="form-label">Synopsis<i class="text-danger">*</i></label>
            <textarea name="synopsis" rows="6"
                class="form-control"><?= unClean($_POST['synopsis'] ?? '') ?></textarea>
        </div>
        <div class="col-md-10 mt-3">
            <label class="form-label">Genres<i class="text-danger">*</i></label>
            <div id="grid">
                <?php foreach ($genres as $genre): ?>
                    <div>
                        <input type="checkbox" value="<?= $genre->getId() ?>" name="genres[]"><?= $genre->getLabel() ?>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
        <div class="col-10 mb-5">
            <button type="submit" name="validate" class="btn btn-outline-primary btn-lg">Ajouter</button>
        </div>
    </form>
</div>

<style>
    #grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, 130px);
        grid-column-gap: 3.2rem;
        grid-row-gap: 8px;
        width: 100%;
        border: 1px solid black;
        font-size: 16px !important;
        padding: 12px 0 12px 8px;
    }

    #grid div input {
        margin-right: 8px;
        text-align: left;
    }
</style>