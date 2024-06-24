<?php

use Riotoon\Entity\User;
use Riotoon\Repository\UserRepository;

$pseudo = $params['pseudo'];
$is_admin = true;

$repository = new UserRepository();

/** @var User|false */
$user = $repository->find($pseudo);

$repository->delete($user->getId());

header('Location:' . $router->url('see-users') . '?del=true');
exit();