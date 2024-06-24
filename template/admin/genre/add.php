<?php

use Riotoon\Repository\GenreRepository;
use Riotoon\Service\BuildErrors;

$is_admin = true;
$active = 'genre';

$repository = new GenreRepository();

$pg_title = "Ajout d'un genre de webtoon | RioToon - Administration";
$errors = [];
if (isset($_POST['validate'])) {
    if (!empty($_POST['label'])) {
        $repository->setLabel($_POST['label']);
            $errors = BuildErrors::getErrors();
            if (empty($errors)) {
                $repository->add();
                $_POST = [];
                header('Location:' . $router->url('see-genres') . '?success=true');
            } else
                BuildErrors::setErrors('fail', "Modification échoué");
    } else
        BuildErrors::setErrors('empty', 'Veuillez selection au moins un champ');
}
$errors = BuildErrors::getErrors();
?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh">
    <form class="border shadow p-3 rounded" method="post" style="width: 450px;">
        <h1 class="text-center p-3">Ajouter un genre</h1>
        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $err): ?>
                <?= messageFlash('warning', $err) ?>
            <?php endforeach ?>
        <?php endif ?>
        <div class="mb-3">
            <label class="form-label">Nom</label>
            <input type="text" class="form-control" name="label">
        </div>
        <div class="col-10 mb-2">
            <button type="submit" name="validate" class="btn btn-outline-primary btn-lg">Ajouter</button>
        </div>
    </form>
</div>