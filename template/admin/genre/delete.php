<?php

use Riotoon\Entity\Genre;
use Riotoon\Repository\GenreRepository;

$id = (int) $params['id'];
$is_admin = true;

$repository = new GenreRepository();

/** @var Genre|false */
$genre = $repository->find($id);

$repository->delete($genre->getId());

header('Location:' . $router->url('see-genres') . '?del=true');
exit();