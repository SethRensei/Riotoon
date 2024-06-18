<?php

use Riotoon\Repository\UserRepository;
use Riotoon\Service\BuildErrors;
use Riotoon\Service\Mailer;

$navbar = '';
$pg_title = "Sincrire sur Riotoon";

$mail = new Mailer();
$repository = new UserRepository();

$errors = [];

if (isset($_POST['validate'])) {
    if (!empty($_POST['pseudo']) and !empty($_POST['fullname']) and !empty($_POST['email']) and !empty($_POST['password'])) {
        $fullname = ucwords($_POST['fullname']);
        $repository->setPseudo($_POST['pseudo'])
            ->setFullname($_POST['fullname'])
            ->setEmail($_POST['email'])
            ->setPassword($_POST['password'])
            ->setIsVerified(0);
        $message = "<h1 style='font-size: 33px;'>Bienvenue sur RioToon</h1>
            <h3 style='font-size: 29px;'>" . $fullname . " alias " . $_POST['pseudo'] . "</h3>
            <p style='font-size: 24px;'>Votre code de confirmation est : <strong>" . $repository->getConfirKey() .
            "</strong></p>
            <p style='font-size: 18px;'>---------------<br>Ceci est un mail automatique, Merci de ne pas y répondre.</p>";
        $mail->send($_POST['email'], $fullname, 'Vérification du compte', $message);
        $errors = BuildErrors::getErrors();
        if(empty($errors)) {
            $repository->add();
            $_POST = [];
            $_SESSION['user_register'] = "Veuillez consulter votre email pour obtenir le code de validation";
            header('Location:' . $router->url('verif', ['pseudo' => $repository->getPseudo()]), true, 301);
        }
    } else
        BuildErrors::setErrors('empty', 'Remplissez tous les champs');
}
$errors = BuildErrors::getErrors();
?>

<div class="content-form">
    <div class="form">
        <h1>Inscription</h1>
        <?php if (isset($errors['empty'])): ?>
            <p><?= $errors['empty'] ?></p>
        <?php endif ?>
        <form method="post">
            <div class="form-input">
                <i class="fas fa-user"></i>
                <?php if (isset($errors['pseudo'])): ?>
                    <p><?= $errors['pseudo'] ?></p>
                <?php endif ?>
                <input type="text" name="pseudo" value="<?= clean($_POST['pseudo'] ?? '') ?>" placeholder="Pseudo*">
                <span class="bar" <?= isset($errors['pseudo']) ? 'style="background-color: var(--col-red);"' : '' ?>></span>
            </div>
            <div class="form-input">
                <i class="fas fa-user"></i>
                <?php if (isset($errors['fullname'])): ?>
                    <p><?= $errors['fullname'] ?></p>
                <?php endif ?>
                <input type="text" name="fullname" value="<?= clean($_POST['fullname'] ?? '') ?>"
                    placeholder="Nom complet*">
                <span class="bar" <?= isset($errors['fullname']) ? 'style="background-color: var(--col-red);"' : '' ?>></span>
            </div>
            <div class="form-input">
                <i class="fas fa-envelope"></i>
                <?php if (isset($errors['email'])): ?>
                    <p><?= $errors['email'] ?></p>
                <?php endif ?>
                <input type="email" name="email" value="<?= clean($_POST['email'] ?? '') ?>" placeholder="Email*">
                <span class="bar" <?= isset($errors['email']) ? 'style="background-color: var(--col-red);"' : '' ?>></span>
            </div>
            <div class="form-input">
                <i class="fas fa-lock"></i>
                <?php if (isset($errors['Password'])): ?>
                    <p class="ri-error"><?= $errors['password'] ?></p>
                <?php endif ?>
                <p class="r-error"></p>
                <input id="password" type="password" name="password" class="password-v"
                    value="<?= clean($_POST['password'] ?? '') ?>" placeholder="Mot de passe*">
                <div class="view">
                    <i class="fas fa-eye"></i>
                    <i class="fas fa-eye-slash"></i>
                </div>
            </div>
            <button type="submit" name="validate" class="submit-btn">S'inscrire</button>
        </form>
    </div>
</div>