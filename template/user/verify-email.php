<?php

use Riotoon\Repository\UserRepository;
use Riotoon\Service\{BuilderError, Mailer};

$repository = new UserRepository();
$errors = [];
$mail = new Mailer();

$pg_title = 'Vérification de votre compte | RioToon';
$pseudo = $params['pseudo'];

if (!isset($_SESSION['user_register'])) {
    http_response_code(308);
    header('Location:' . $router->url('home'));
}

/** @var UserRepository */
$user = $repository->find($pseudo);
if ($user === false)
    throw new Exception("Cet utilisateur n'existe pas");

if (isset($_POST['validate'])) {
    if (!empty($_POST['verif'])) {
        if (!preg_match('/^\d+$/', clean($_POST['verif'])))
            BuilderError::setErrors('verif', 'Entrez uniquement des chiffres');
        if (!empty($errors))
            http_response_code(422);
        if (time() < $user->getTokenExpire()) {
            if ($user->getToken() === (int) clean($_POST['verif'])) {
                    $user->setIsVerify(true);
                    $repository->verifyEmail($user);
                    $_SESSION['User'] = $user->getId();
                    $_SESSION['pseudo'] = $user->getPseudo();
                    $_SESSION['roles'] = $user->collectionRoles();
                    header('Location:' . $router->url('home'));
                    unset($_SESSION['user_register']);
            } else
                BuilderError::setErrors('fail', 'Le code est incorrect');
        } else
            BuilderError::setErrors('token_expire','Ce code de vérification a expiré');
    } else
        BuilderError::setErrors('empty', 'Veuillez renseigner le champ');
}

if (isset($_POST['resend'])) {
    $user->generateToken()->generateTokenExpire();
    $content = "Vous avez demandé un nouveau code de vérification. Veuillez utiliser celui-ci pour confirmer votre compte. Il est valide pendant 1 heure.";
    $code = $user->getToken();
    $message = verifyAccount('Nouveau code de confirmation', $content, $code, $router->url('home'));
    $mail->send($user->getEmail(), strtoupper($user->getPseudo()), 'Nouveau code de confirmation', $message);
    $errors = BuilderError::getErrors();
    if (empty($errors)) {
        $repository->changeToken($user);
        $message = "Veuillez vérifier vos mails pour obtenir le nouveau code de confirmation !";
        header('Content-Type: text/vnd.turbo-stream.html; charset=utf-8');
        echo '<turbo-stream action="append" target="messages"><template>';
        echo messageFlash('success', $message);
        echo '</template></turbo-stream>';
        exit;
    }
}

$errors = BuilderError::getErrors();
if (!empty($errors))
    http_response_code(422);
?>


<div class="page-content">
    <div class="form-verif">
        <div class="error-verif">
            <?php if (isset($_SESSION['user_register'], $_SESSION['user_content'])) {
                echo messageFlash('primary', $_SESSION['user_content']);
                unset($_SESSION['user_content']);
            }
            ?>
            <div id="messages">
            </div>
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
</div>