<?php

use Riotoon\Entity\User;
use Riotoon\Controller\Auth;
use Riotoon\Repository\UserRepository;

$pseudo = $params['pseudo'];
$is_admin = true;

Auth::check();

$repository = new UserRepository();

/** @var User|false */
$user = $repository->find($pseudo);
$directory = '../public/' . $user->getProfilePicture();
$repository->delete($user->getId());
if ($directory != null)
    unlink($directory);
if (!in_array('ROLE_ADMIN', $_SESSION['roles'])) {
    $_SESSION = [];
    session_destroy();
    header('Location:' . $router->url('home'), true, 301);
}
else
    header('Location:' . $router->url('see-users') . '?del=true');
exit();