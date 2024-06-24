<?php

use Riotoon\Entity\User;
use Riotoon\Repository\UserRepository;

$is_admin = true;
$active = 'users';

$pg_title = "Les utilisateurs - Info | RioToon - Administration";

$repository = new UserRepository();

/** @var User */
$users = $repository->findAll();

?>
<form class="container mb-4" style="margin-top: 95px;">
    <div class="form-group mb-2">
        <input type="text" name="q" class="form-control" placeholder="Rechercher par titre"
            value="<?= clean($_GET['q'] ?? '') ?>">
    </div>
    <button class="btn btn-primary">Rechercher</button>
</form>
<?php if (isset($_GET['success']) and $_GET['success'] == true): ?>
    <?= messageFlash('success', 'Modification du rôle réussi') ?>
<?php endif ?>
<?php if (isset($_GET['del']) and $_GET['del'] == true): ?>
    <?= messageFlash('success', "Suppression de l'utilisateur réussi") ?>
<?php endif ?>
<table class="table-responsive">
    <thead>
        <tr>
            <th>ID</th>
            <th>Pseudo</th>
            <th>Nom complet</th>
            <th>Rôles</th>
            <th>Email</th>
            <th>Vérifié</th>
            <th colspan="2">Actions</th>
        </tr>
    </thead>
    <tbody class="text-dark">
        <?php foreach ($users as $user): ?>
            <tr>
                <td data-label="ID" translate="no">#<?= $user->getId() ?></td>
                <td data-label="Pseudo" translate="no"><?= unClean($user->getPseudo()) ?></td>
                <td data-label="Nom"><?= excerpt($user->getFullname()) ?></td>
                <td data-label="Rôles"><?= implode(', ', $user->getCollectionsRoles() )?></td>
                <td data-label="Email"><?= $user->getEmail() ?></td>
                <td data-label="Vérifé"><?= $user->getIsVerified() == 1 ? 'Oui' : 'Non' ?></td>
                <td data-label="Modifier"><a href="<?= $router->url('edit-user-admin', ['pseudo' => $user->getPseudo()])?>"><i
                            id="edit" class="fas fa-solid fa-pencil"></i></a></td>
                <td data-label="Supprimer">
                    <form action="<?= $router->url('del-user', ['pseudo' => $user->getPseudo()]) ?>" method="post"
                        onsubmit="return confirm('Voulez-vous vraiment éffectuer cette action ?')"><button type="submit"
                            style="border: none; background: transparent;"><i id="remove"
                                class="fa-solid fa-trash-can"></i></button>
                    </form>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>
<?= tableStyle() ?>