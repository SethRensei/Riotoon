<?php
use Riotoon\Entity\{Category, Webtoon};
use Riotoon\Repository\{CategoryRepository, WebtoonRepository};
use Riotoon\Service\BuilderError;

$is_admin = true;
$active = 'webt_new';

$repository = new WebtoonRepository();
$webtoon = new Webtoon();

/** @var Category */
$categories = CategoryRepository::findAll();

if (isset($_POST['validate'], $_FILES['image']['name'])) {
    if (!empty($_POST['title']) and !empty($_POST['author']) and !empty($_FILES['image']['size']) and !empty($_POST['status']) and !empty($_POST['synopsis']) and !empty($_POST['categories'])) {
        $name = replace(clean($_POST['title'])) . '-cover_' . rand(1000, 99999);
        $path = uploadFile($_FILES['image'], $name);
        $webtoon->setTitle($_POST['title'])
            ->setAuthor($_POST['author'])
            ->setStatus($_POST['status'])
            ->setCover(str_replace('../public/', '', $path))
            ->setSynopsis($_POST['synopsis']);
        $errors = BuilderError::getErrors();
        if (empty($errors) and move_uploaded_file($_FILES['image']['tmp_name'], $path)) {
            $id = $repository->new($webtoon);
            foreach ($_POST['categories'] as $v)
                CategoryRepository::addCategoriesForWebtoon($id, $v);
            $_POST = [];
            $_SESSION['success'] = true;
            $_SESSION['content'] = 'Le webtoon suivant a été ajouté : ' . unClean($webtoon->getTitle());
            // header('Location:' . $router->url('home-admin'));
        } else
            BuilderError::setErrors('fail', "Upload du fichier échoué");
    } else
        BuilderError::setErrors('empty', 'Veuillez remplir tous les champs');
}
$errors = BuilderError::getErrors();
?>
<div class="fs-4">
    <h1 class="mt-1">Ajouter un Webtoon</h1>
    <?php if (!empty($errors)): ?>
        <?php foreach ($errors as $err): ?>
            <?= $err ?>
        <?php endforeach ?>
    <?php endif ?>
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
        <div class="col-md-5">
            <label class="form-label">Photo<i class="text-danger">*</i></label>
            <input type="file" name="image" class="form-control" value="<?= $_FILES['image']['name'] ?? '' ?>">
        </div>
        <div class="col-md-5 mb-3">
            <label class="form-label">Statut<i class="text-danger">*</i></label>
            <select class="form-select" name="status">
                <option value="ONGOING" select>En cours</option>
                <option value="FINISHED">Terminé</option>
                <option value="SUSPENDED">Suspendu</option>
            </select>
        </div>
        <div class="col-md-10">
            <label class="form-label">Synopsis<i class="text-danger">*</i></label>
            <textarea name="synopsis" rows="6" class="form-control"><?= unClean($_POST['synopsis'] ?? '') ?></textarea>
        </div>
        <div class="col-md-10 mt-3">
            <label class="form-label">Genres<i class="text-danger">*</i></label>
            <div id="grid">
                <?php foreach ($categories as $cat) :?>
                    <div>
                        <label for="cat_<?= $cat->getId() ?>"><?= $cat->getLabel() ?></label><br>
                        <input type="checkbox" id="cat_<?= $cat->getId() ?>" value="<?= $cat->getId() ?>" name="categories[]">
                    </div>
                <?php endforeach?>
            </div>
        </div>
        <div class="col-10 text-center">
            <button type="submit" name="validate" class="btn btn-primary btn-lg">Ajouter</button>
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