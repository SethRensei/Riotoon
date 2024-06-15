<?php

use Riotoon\Entity\Webtoon;
use Riotoon\Service\BuildErrors;
use Riotoon\Repository\{GenreRepository, WebtoonRepository};

$id = (int) $params['id'];

$is_admin = true;

$errors = [];
$gen_repo = new GenreRepository();

$genres = $gen_repo->findAll();

$repository = new WebtoonRepository();

/** @var Webtoon|false */
$webtoon = $repository->fetchOne("id", $id);

if ($webtoon === false) {
    throw new Exception("Aucun webtoon n'a été trouvé");
}
if ($webtoon->getId() !== $id) {
    http_response_code(301);
    header('Location: ' . $router->url('home-admin'));
    exit();
}
$pg_title = 'Modifier ' . $webtoon->getTitle() . ' | RioToon - Administration';

$current_genres = explode(',', $webtoon->getGenres());

//mettre les tableaux des erreurs à un e valeurs vides
$errors = [];

//voir si le bouton existe et si l'image existe aussi
if (isset($_POST['validate'])) {
    //voir si les champs ne sont pas vides
    if (!empty($_POST['title']) and !empty($_POST['author']) and !empty($_POST['release-year']) and !empty($_POST['status']) and !empty($_POST['synopsis']) and !empty($_POST['genres'])) {
        $result = true; // Si tout ce passe bien et prevoir le saut sur l'upload
        $repository->setTitle($_POST['title'])
            ->setAuthor($_POST['author'])
            ->setReleaseYear($_POST['release-year'])
            ->setStatus($_POST['status'])
            ->setCover($webtoon->getCover())
            ->setSynopsis($_POST['synopsis']);
        if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {
            $name = str_replace(chars(), '_', clean($_POST['title'])) . '-cover_' . rand(1000, 99999);
            $path = uploadFile($_FILES['image'], $name);
            $repository->setCover(str_replace('../public/', '', $path));
            $result = move_uploaded_file($_FILES['image']['tmp_name'], $path);
            if ($result)
                unlink('../public/' . $webtoon->getCover());
        }
        $errors = BuildErrors::getErrors();
        //voir si l'image a bien été uploadé et qu'il n'y a plus d'erreurs
        if ($result and empty($errors)) {
            $repository->edit($webtoon->getId());
            if ($webtoon->getIdGenres() != $_POST['genres']) {
                $gen_repo->deleteWebtoonGenre($webtoon->getId());
                foreach ($_POST['genres'] as $v)
                    $gen_repo->addWebtoonGenre($webtoon->getId(), $v);
            }
            $_POST = [];
            header('Location:' . $router->url('home-admin') . '?edit_webt=true');
        } else
            BuildErrors::setErrors('fail', "Upload échoué ou mauvais format des données");
    } else
        BuildErrors::setErrors('empty', 'Veuillez remplir tous les champs');
}

$errors = BuildErrors::getErrors();

?>

<div class="fs-4 mt-5">
    <h1><?= $webtoon->getTitle() ?></h1>
    <div class="card mb-3 col-md-2">
        <img src="<?= BASE_URL . $webtoon->getCover() ?>">
        <div class="card-img-overlay">
            <h5 class="card-title text-danger">Photo actuelle</h5>
        </div>
    </div>
    <?php if (!empty($errors)): ?>
        <?php foreach ($errors as $err): ?>
            <?= messageFlash('warning', $err) ?>
        <?php endforeach ?>
    <?php endif ?>
    <form method="post" class="row g-3 justify-content-center" enctype="multipart/form-data">
        <div class="col-md-5 form-group">
            <label class="form-label">Titre<i class="text-danger">*</i></label>
            <input type="text" name="title" value="<?= $webtoon->getTitle() ?>" class="form-control" placeholder="ex: the beginning after the end">
        </div>
        <div class="col-md-5 mb-3">
            <label class="form-label">Auteur<i class="text-danger">*</i></label>
            <input type="text" name="author" value="<?= $webtoon->getAuthor() ?>" class="form-control" placeholder="ex: TurtleMe">
        </div>
        <div class="col-md-3 form-group">
            <label class="form-label">Sortie en<i class="text-danger">*</i></label>
            <input type="tel" maxlength="4" pattern="[0-9-() ]*" minlength="1" name="release-year" value="<?= $webtoon->getReleaseYear() ?>" class="form-control">
        </div>
        <div class="col-md-4">
            <label class="form-label">Photo<i class="text-danger">*</i></label>
            <input type="file" name="image" class="form-control">
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Statut<i class="text-danger">*</i></label>
            <select class="form-select" name="status">
                <option value="En cours" <?= $webtoon->getStatus() === 'En cours' ? 'selected' : '' ?>>En cours</option>
                <option value="Terminé" <?= $webtoon->getStatus() === 'Terminé' ? 'selected' : '' ?>>Terminé</option>
            </select>
        </div>
        <div class="col-md-10">
            <label class="form-label">Synopsis<i class="text-danger">*</i></label>
            <textarea name="synopsis" rows="6" class="form-control"><?= html_entity_decode($webtoon->getSynopsis()) ?></textarea>
        </div>
        <div class="col-md-10 mt-3">
            <label class="form-label">Genres<i class="text-danger">*</i></label>
            <div id="grid">
                <?php foreach ($genres as $genre): ?>
                    <div>
                        <input type="checkbox" name="genres[]" value="<?= $genre->getId() ?>" <?= in_array($genre->getLabel(), $current_genres) ? 'checked' : ''?>><?= $genre->getLabel() ?>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
        <div class="col-10 mb-5">
            <button type="submit" name="validate" class="btn btn-outline-primary btn-lg">Modifier</button>
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