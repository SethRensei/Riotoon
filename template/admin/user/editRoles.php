<?php

use Riotoon\Entity\User;
use Riotoon\Service\BuildErrors;
use Riotoon\Repository\UserRepository;

$pseudo = $params['pseudo'];
$is_admin = true;
$active = 'users';

/** @var User|false */
$user = (new UserRepository())->find($pseudo);

if ($user === false)
    throw new Exception("Aucun utilisateur n'a été trouvé sous le pseudo de : {$pseudo}");

$pg_title = 'Modifie Rôles ' . html_entity_decode($user->getPseudo()) . " | RioToon - Administration";
$errors = [];
if (isset($_POST['validate'])) {
    if (!empty($_POST['roles'])) {
        if (in_array('ROLE_USER', $_POST['roles'])) {
            $repository->setRoles($_POST['roles'])
                ->setFullname(unClean($user->getFullname()));
            $errors = BuildErrors::getErrors();
            if (empty($errors)) {
                $repository->edit($user->getId());
                $_POST = [];
                header('Location:' . $router->url('see-users') . '?success=true');
            } else
                BuildErrors::setErrors('fail', "Modification échoué");
        } else
            BuildErrors::setErrors('roles', 'Le rôle user est obligatoire');
    } else
        BuildErrors::setErrors('empty', 'Veuillez selection au moins un champ');
}
$errors = BuildErrors::getErrors();
?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh">
    <form class="border shadow p-3 rounded" method="post" style="width: 450px;">
        <h1 class="text-center p-3">Modifier le rôle</h1>
        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $err): ?>
                <?= messageFlash('warning', $err) ?>
            <?php endforeach ?>
        <?php endif ?>
        <div class="mb-3">
            <label class="form-label">Pseudo</label>
            <input type="text" class="form-control" value="<?= $user->getPseudo() ?>" disabled>
        </div>
        <div class="mb-4">
            <label class="form-label">Roles</label>
            <div class="form-check form-switch">
                <input type="checkbox" name="roles[]" value="ROLE_USER" role="switch" class="form-check-input" <?= in_array('ROLE_USER', $user->getCollectionsRoles()) ? 'checked' : '' ?>>
                <label class="form-check-label">Utilisateur</label>
            </div>
            <div class="form-check form-switch">
                <input type="checkbox" name="roles[]" value="ROLE_ADMIN" role="switch" class="form-check-input" <?= in_array('ROLE_ADMIN', $user->getCollectionsRoles()) ? 'checked' : '' ?>>
                <label class="form-check-label">Administrateur</label>
            </div>
        </div>
        <div class="col-10 mb-2">
            <button type="submit" name="validate" class="btn btn-outline-primary btn-lg">Modifier</button>
        </div>
    </form>
</div>