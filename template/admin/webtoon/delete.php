<?php

use Riotoon\Entity\Webtoon;
use Riotoon\Repository\WebtoonRepository;

$id = (int) $params['id'];
$is_admin = true;

$repository = new WebtoonRepository();

/** @var Webtoon|false */
$webtoon = $repository->fetchByID(value: $id);

$directory = '../public/' . $webtoon->getCover();

$repository->delete($webtoon->getId());
unlink($directory);

$_SESSION['success'] = true;
$_SESSION['content'] = 'Le webtoon suivant a été supprimé : ' . $webtoon->getTitle();
header('Location:' . $router->url('admin_index'));
exit();