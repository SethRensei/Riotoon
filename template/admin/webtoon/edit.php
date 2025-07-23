<?php

use Riotoon\Entity\{Category, Webtoon};
use Riotoon\Repository\{CategoryRepository, WebtoonRepository};
use Riotoon\Service\BuilderError;

$id = (int) $params['id'];
$is_admin = true;
$errors = [];

$repository = new WebtoonRepository();

/** @var Category */
$categories = CategoryRepository::findAll();

/** @var Webtoon|false */
$webtoon = $repository->fetchByID(value: $id);

if ($webtoon === false)
    throw new Exception("Aucun webtoon n'a été trouvé");

$w_title = unClean($webtoon->getTitle());
$pg_title = 'RioToon - Administration | Modifier ' . $w_title;

$current_categories = explode(',', $webtoon->getCategories());
$result = false;

if (isset($_POST['validate'])) {
    if (!empty($_POST['title']) and !empty($_POST['author']) and !empty($_POST['status']) and !empty($_POST['synopsis']) and !empty($_POST['categories'])) {
        $result = true;
        
        $webtoon->setTitle($_POST['title'])
        ->setAuthor($_POST['author'])
        ->setStatus($_POST['status'])
        ->setSynopsis($_POST['synopsis']);
        if (!empty($_FILES['image']['name'])) {
            $name = replace(clean($_POST['title'])) . '-cover_' . rand(1000, 99999);
            $path = uploadFile($_FILES['image'], $name);
            $webtoon->setCover(str_replace('../public/', '', $path));
            if (move_uploaded_file($_FILES['image']['tmp_name'], $path))
                unlink('../public/' . $webtoon->getCover());
        }
        $errors = BuilderError::getErrors();
        if (!empty($errors))
            http_response_code(422);
        else if ($result and empty($errors)) {
            $repository->update($webtoon);
            if ($webtoon->getIDCategories() != $_POST['categories']) {
                CategoryRepository::deleteCategorieForWebtoon(webtoon: $webtoon->getId());
                foreach ($_POST['categories'] as $v)
                    CategoryRepository::addCategoriesForWebtoon(webtoon: $webtoon->getId(), category: $v);
            }
            $_POST = [];
            header('Location:' . $router->url('admin_index'));
            $_SESSION['success'] = true;
            $_SESSION['content'] = 'Vous avez modifié ce webtoon : ' . $w_title;
        } else
            BuilderError::setErrors('file', "Ajout des données échoué");
    } else
        BuilderError::setErrors('empty', 'Veuillez remplir tous les champs');
}

$errors = BuilderError::getErrors();
if (!empty($errors))
    http_response_code(422);
?>

<div class="fs-4">
    <h1><?= $w_title ?></h1>
    <div class="card mb-3 col-md-2">
        <img src="<?= BASE_URL . $webtoon->getCover() ?>">
        <div class="card-img-overlay">
            <h5 class="card-title text-danger">Photo actuelle</h5>
        </div>
    </div>
    <form method="post" class="row g-3 mt-2 justify-content-center" enctype="multipart/form-data">
        <div class="col-md-10">
            <?php if (isset($errors['empty'])): ?>
                <?= messageFlash('danger', $errors['empty']) ?>
            <?php endif ?>
        </div>
        <div class="col-md-5 form-group">
            <label for="title" class="form-label">Titre<i class="text-danger">*</i></label>
            <input type="text" id="title" name="title" value="<?= unClean($webtoon->getTitle() )?>" class="form-control"
                placeholder="ex: the beginning after the end">
        </div>
        <div class="col-md-5 mb-3">
            <label for="author" class="form-label">Auteur<i class="text-danger">*</i></label>
            <input type="text" id="author" name="author" value="<?= unClean($webtoon->getAuthor() )?>" class="form-control"
                placeholder="ex: TurtleMe">
        </div>
        <div class="col-md-5">
            <label for="cover" class="form-label">Photo</label>
            <input type="file" id="cover" name="image" class="form-control" value="<?= $_FILES['image']['name'] ?? '' ?>">
            <?php if(isset($errors['file'])):?>
                <?= messageFlash('danger',$errors['file']) ?>
            <?php endif;?>
        </div>
        <div class="col-md-5 mb-3">
            <label for="status" class="form-label">Statut<i class="text-danger">*</i></label>
            <select class="form-select" name="status" id="status">
                <option>Choisi une option</option>
                <?php foreach ($webtoon->statusValid() as $key => $value):?>
                <option value="<?=$key?>" <?= $key == $webtoon->getStatus() ? 'selected' : ''?>><?= $value?></option>
                <?php endforeach; ?>
            </select>
            <?php if(isset($errors['status'])):?>
                <?= messageFlash('danger',$errors['status']) ?>
            <?php endif;?>
        </div>
        <div class="col-md-10">
            <label for="synopsis" class="form-label">Synopsis<i class="text-danger">*</i></label>
            <textarea id="synopsis" name="synopsis" rows="6" class="form-control"><?= unClean($webtoon->getSynopsis()) ?></textarea>
            <?php if(isset($errors['synopsis'])):?>
                <?= messageFlash('danger',$errors['synopsis']) ?>
            <?php endif;?>
        </div>
        <div class="col-md-10 mt-3">
            <label class="form-label">Genres<i class="text-danger">*</i></label>
            <div id="grid">
                <?php foreach ($categories as $cat) :?>
                    <div>
                        <label for="cat_<?= $cat->getId() ?>"><?= $cat->getLabel() ?></label><br>
                        <input type="checkbox" id="cat_<?= $cat->getId() ?>" value="<?= $cat->getId() ?>" name="categories[]" <?= in_array($cat->getLabel(), $current_categories) ? 'checked' : '' ?>>
                    </div>
                <?php endforeach?>
            </div>
        </div>
        <div class="col-10 text-center">
            <button type="submit" name="validate" class="btn btn-primary btn-lg">Modifier</button>
        </div>
    </form>
</div>
    </div>


<style>
    #grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, 150px);
        grid-column-gap: 2rem;
        grid-row-gap: 8px;
        width: 100%;
        border: 1px solid black;
        font-size: 16px !important;
        padding: 12px 0 12px 8px;
    }
</style>