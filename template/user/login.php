<?php

use Riotoon\Repository\UserRepository;
use Riotoon\Service\BuilderError;

$errors = [];

if (isset($_POST["validate"])) {
    if (!empty($_POST['user']) and !empty($_POST['password'])) {
        $repository = new UserRepository();
        /** @var UserRepository */
        $user = $repository->find(clean($_POST['user']));
        if ($user != false) {
            if (password_verify(clean($_POST['password']), $user->getPassword())) {
                if ($user->getIsVerify() == true) {
                    $_SESSION['User'] = $user->getId();
                    $_SESSION['pseudo'] = $user->getPseudo();
                    $_SESSION['roles'] = $user->collectionRoles();
                    header('Location:' . $router->url('home'));
                } else {
                    $_SESSION['user_register'] = 'Is Okay';
                    $_SESSION['user_content'] = "Connexion impossible ! Veuillez confirmer votre compte";
                    header('Location:' . $router->url('verify', ['pseudo' => goodURL($user->getPseudo())]), true, 301);
                }
            } else
                BuilderError::setErrors('Incorrect', 'Le mot de passe saisi est incorrect');
        } else
            BuilderError::setErrors('Incorret', 'Pseudo ou Email incorrect');
    } else
        BuilderError::setErrors('empty', 'Veuillez remplir tous les champs');
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
        <form method="post" autocomplete="off">
            <h1>Connection</h1>
            <div class="input-box">
                <input type="text" name="user" value="<?= $_POST['user'] ?? '' ?>" placeholder="Votre pseudo ou email" autocomplete="new-username" required>
                <i class="fas fa-user"></i>
            </div>
            <div class="input-box">
                <input type="password" id="password" name="password" value="<?= $_POST['password'] ?? '' ?>" placeholder="Votre password" autocomplete="new-password" required>
                <i class="fas fa-lock"></i>
                <div class="view">
                    <i class="fas fa-eye"></i>
                    <i class="fas fa-eye-slash"></i>
                </div>
            </div>
            <button type="submit" name="validate" class="btn">Se connecter</button>
            <div class="register-link">
                <p>Pas de compte ? <a href="<?= $router->url('register')?>">S'inscrire</a></p>
            </div>
        </form>
    </div>
</div>