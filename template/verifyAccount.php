<?php

use Riotoon\Entity\User;
use Riotoon\Repository\UserRepository;
use Riotoon\Service\BuildErrors;

$navbar = '';

$pg_title = 'Vérification de votre compte | RioToon';
$pseudo = $params['pseudo'];

if (!isset($_SESSION['user_register'])) {
    http_response_code(308);
    header('Location:' . $router->url('home'));
}

$repository = new UserRepository();

/** @var User */
$user = $repository->find($pseudo);

if ($user === false)
    throw new Exception("Cet utilisateur n'existe pas");
if ($user->getIsVerified() == 1) {
    http_response_code(308);
    header('Location:' . $router->url('home'));
}

$errors = [];
if (isset($_POST['validate']))
{
    if (!empty($_POST['verif'])) {
        if (!preg_match('/^\d+$/', clean($_POST['verif'])))
            BuildErrors::setErrors('verif', 'Entrez uniquement des chiffres');
        if ($user->getConfirKey() === (int) clean($_POST['verif'])) {
            $repository->verify($user->getPseudo());
            $_SESSION['User'] = $user->getId();
            $_SESSION['pseudo'] = $user->getPseudo();
            $_SESSION['fullname'] = $user->getFullname();
            header('Location:' . $router->url('home'), true, 308);
        } else
            BuildErrors::setErrors('fail', 'Le code est incorrect');
    } else
        BuildErrors::setErrors('empty', 'Veuillez renseigner le champ');
}

$errors = BuildErrors::getErrors();
?>


<div class="form-verif">
    <div class="error-verif">
        <?php if (isset($_SESSION['user_register'])) {
            echo messageFlash('success', $_SESSION['user_register']);
            $_SESSION['user_register'] = null;
        }
        ?>
        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $err): ?>
                <?= messageFlash('warning', $err) ?>
            <?php endforeach ?>
        <?php endif ?>
    </div>
    <form class="verify-form" method="post">
		<h3>Confirmez votre compte</h3>
		<input type="tel" name="verif" id="email" placeholder="Votre code de vérification">
        <a href="#">Renvoyez le code</a>
		<button type="submit" name="validate" class="btn btn-outline-dark">Valider</button>
	</form>
</div>