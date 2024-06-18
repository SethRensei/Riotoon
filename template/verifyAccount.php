<?php

use Riotoon\Entity\User;
use Riotoon\Repository\UserRepository;
use Riotoon\Service\BuildErrors;
use Riotoon\Service\Mailer;

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
if (isset($_POST['validate'])) {
    if (!empty($_POST['verif'])) {
        if (!preg_match('/^\d+$/', clean($_POST['verif'])))
            BuildErrors::setErrors('verif', 'Entrez uniquement des chiffres');
        if ($user->getConfirKey() === (int) clean($_POST['verif'])) {
            $repository->verify($user->getPseudo());
            $_SESSION['User'] = $user->getId();
            $_SESSION['pseudo'] = $user->getPseudo();
            $_SESSION['fullname'] = $user->getFullname();
            $_SESSION['roles'] = $user->getCollectionsRoles();
            $_SESSION['user_register'] = null;
            echo "<script>window.history.go(-2);</script>";
        } else
            BuildErrors::setErrors('fail', 'Le code est incorrect');
    } else
        BuildErrors::setErrors('empty', 'Veuillez renseigner le champ');
}

if (isset($_POST['resend'])) {
    $mail = new Mailer();
    $message = "<h1 style='font-size: 33px;'>Bienvenue sur RioToon</h1>
    <h3 style='font-size: 29px;'>" . $user->getFullname() . " alias " . $user->getPseudo() . "</h3>
    <p style='font-size: 24px;'>Votre nouveau code de confirmation est : <strong>" . $repository->getConfirKey() .
        "</strong></p>
    <p style='font-size: 18px;'>---------------<br>Ceci est un mail automatique, Merci de ne pas y répondre.</p>";
    $mail->send($user->getEmail(), $user->getFullname(), 'Nouveau code de vérification', $message);
    $errors = BuildErrors::getErrors();
    if (empty($errors)) {
        $repository->editConfirKey($user->getPseudo());
        $_SESSION['user_register'] = 'Le nouveau code a été envoyé';
    }
}

$errors = BuildErrors::getErrors();
?>


<div class="form-verif">
    <div class="error-verif">
        <?php if (isset($_SESSION['user_register'])) {
            echo messageFlash('primary', $_SESSION['user_register']);
        }
        ?>
        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $err): ?>
                <?= messageFlash('warning', $err) ?>
            <?php endforeach ?>
        <?php endif ?>
    </div>
    <form class="verify-form" method="post">
        <h3>Confirmez votre identité</h3>
        <input type="tel" name="verif" id="email" placeholder="Votre code de vérification">
        <button type="submit" name="resend" style="color:blue;">Renvoyez le code</button>
        <button type="submit" name="validate" class="btn btn-outline-dark">Valider</button>
    </form>
</div>