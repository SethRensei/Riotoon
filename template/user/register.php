<?php

use Riotoon\Repository\UserRepository;
use Riotoon\Service\{BuilderError, Mailer};

$repository = new UserRepository();
$errors = [];
$mail = new Mailer();

if (isset($_POST["validate"])) {
    if (!empty($_POST['pseudo']) and !empty($_POST['email']) and !empty($_POST['password'])) {
        $repository->setPseudo($_POST['pseudo'])
            ->setEmail($_POST['email'])
            ->setPassword($_POST['password'])
            ->generateToken()->generateTokenExpire()
            ->setRoles(['ROLE_USER']);
        $errors = BuilderError::getErrors();
        $content = "Nous sommes ravis que vous vous lanciez ! Vous devez d'abord confirmer votre compte. Il vous suffit de copier le code ci-dessous valade pendant 1h.";
        $code = $repository->getToken();
        $message = verifyAccount('Bienvenue !',$content, $code, $router->url('home'));
        if(!empty($errors))
            http_response_code(422);
        else {
            $mail->send(clean($_POST['email']), $_POST['pseudo'], 'Vérification du compte', $message);
            $repository->new($repository);
            $errors = BuilderError::getErrors();
            if (!empty($errors))
                http_response_code(422);
            else {
                $_POST = [];
                $_SESSION['user_register'] = "Is Okay";
                $_SESSION['user_content'] = "Veuillez consulter votre email pour obtenir le code de validation";
                header('Location:' . $router->url('verify', ['pseudo' => goodURL($repository->getPseudo())]), true, 301);
            }
        }
    } else
        BuilderError::setErrors('emtpy', "Veuillez renseigner tous les champs");
}

$errors = BuilderError::getErrors();
if (!empty($errors))
    http_response_code(422);
?>

<div class="form-content">
    <div class="col-md-10">
        <?php if (!empty($errors)): ?>
        <?php foreach ($errors as $err): ?>
            <?= messageFlash('warning', $err) ?>
        <?php endforeach ?>
    <?php endif ?>
    </div>
    <div class="wrapper">
        <form autocomplete="off" method="post">
            <h1>S'inscrire</h1>
            <div class="input-box">
                <input type="text" name="pseudo" value="<?= $_POST['pseudo'] ?? '' ?>" placeholder="Votre pseudo" autocomplete="new-username" required>
                <i class="fas fa-user"></i>
            </div>
            <div class="input-box">
                <input type="email" name="email" value="<?= $_POST['email'] ?? '' ?>" placeholder="Votre email" autocomplete="offf" required>
                <i class="fas fa-envelope"></i>
            </div>
            <div class="input-box">
                <input id="password" type="password" name="password" value="<?= $_POST['password'] ?? '' ?>" placeholder="Votre password" autocomplete="new-password" required>
                <i class="fas fa-lock"></i>
                <div class="view">
                    <i class="fas fa-eye"></i>
                    <i class="fas fa-eye-slash"></i>
                </div>
                <p class="r-error" style="color: red;">Error</p>
            </div>
            <button type="submit" name="validate" class="btn">S'inscrire</button>
            <div class="register-link">
                <p>Déjà un compte ? <a href="<?= $router->url('login')?>">Se connection</a></p>
            </div>
        </form>
    </div>
</div>