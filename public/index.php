<?php

use Riotoon\Controller\Router;

require_once '../vendor/autoload.php';

define('BASE_URL', dirname($_SERVER['SCRIPT_NAME']));

$router = new Router(dirname(__DIR__) . '/template');

$router->get('/', 'index', 'home')
    ->get('/webtoon/[i:id]-[*:title]', 'showWebtoon', 'show-webt')
    ->get('/admin', 'admin/index', 'home-admin')
    ->fallOver('/admin/add-webtoon', 'admin/addWebtoon')
    ->run();