<?php

use Riotoon\Repository\GenreRepository;
use Riotoon\Service\BuildErrors;

$is_admin = true;

$errors = [];
$genres = GenreRepository::findAll();

if (isset($_POST['validate'], $_FILES['image']['name'])) {
    if (!empty($_POST['title']) and !empty($_POST['author']) and !empty($_FILES['image']['size']) and !empty($_POST['release_year']) and !empty($_POST['statut']) and !empty($_POST['genres']) and !empty($_POST['synopsis'])) {

    }
}

$errors = BuildErrors::getErrors();

?>

<div class="fs-4">
    <h1>Ajouter un Webtoon</h1>
    <form method="post" class="row g-3 justify-content-center" enctype="multipart/form-data">
        <div class="col-md-5 form-group">
            <label class="form-label">Titre</label>
            <input type="text" name="title" value="<?= $_POST['title'] ?? '' ?>" class="form-control"
                placeholder="ex: the beginning after the end">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Auteur</label>
            <input type="text" name="author" value="<?= $_POST['author'] ?? '' ?>" class="form-control"
                placeholder="ex: TurtleMe">
        </div>
        <div class="col-md"></div>
        <div class="col-md-3 form-group">
            <label class="form-label">Année de sortie</label>
            <input type="tel" maxlength="4" pattern="[0-9-() ]*" minlength="1" name="release_year"
                value="<?= $_POST['release_year'] ?? '' ?>"
                class="form-control <?= isset($errors['year']) ? 'is-invalid' : '' ?>">
            <?php if (isset($errors['year'])): ?>
                <div class="invalid-feedback"></div>
            <?php endif ?>
        </div>
        <div class="col-md-3">
            <label class="form-label">Photo de couverture</label>
            <input type="file" name="image" class="form-control <?= isset($errors['cover']) ? 'is-invalid' : '' ?>">
            <?php if (isset($errors['Image'])): ?>
                <div class="invalid-feedback"></div>
            <?php endif ?>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Statut</label>
            <select class="form-select" name="statut">
                <option value="0" select>En cours</option>
                <option value="1">Terminé</option>
            </select>
        </div>
        <div class="col-md-9">
            <label class="form-label">Synopsis</label>
            <textarea name="synopsis" rows="6"
                class="form-control <?= isset($errors['synopsis']) ? 'is-invalid' : '' ?>"><?= html_entity_decode($_POST['synopsis'] ?? '') ?></textarea>
            <?php if (isset($errors['synopsis'])): ?>
                <div class="invalid-feedback"></div>
            <?php endif ?>
        </div>
        <div class="col-md-12 mt-3" style="border: 1px solid black;">
            <label class="form-label">Genres</label>
            <div id="grid">
                <?php foreach ($genres as $genre): ?>
                    <div>
                        <input type="checkbox" value="<?= $genre->getId() ?>"
                            name="genres[]"><?= $genre->getLabel() ?>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
        <div class="col-12 mb-5">
            <button type="submit" name="validate" class="btn btn-primary">Ajouter</button>
        </div>
    </form>
</div>