<?php

use Riotoon\Entity\Webtoon;
use Riotoon\Repository\WebtoonRepository;

$id = (int) $params['id'];
$is_admin = true;

$repository = new WebtoonRepository();

/** @var Webtoon|false */
$webtoon = $repository->fetchOne("id", $id);

$directory = '../public/' . $webtoon->getCover();

$repository->delete($webtoon->getId());
unlink($directory);

header('Location:' . $router->url('home-admin') . '?del=true');
exit();