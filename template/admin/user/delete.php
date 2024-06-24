<?php

use Riotoon\Entity\User;
use Riotoon\Repository\UserRepository;

$id = (int) $params['id'];
$is_admin = true;

$repository = new UserRepository();

/** @var User|false */
$user = $repository->find($id);

$repository->delete($user->getId());

header('Location:' . $router->url('see-users') . '?del=true');
exit();