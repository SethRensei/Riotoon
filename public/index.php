<?php

use Riotoon\Controller\Router;

require_once '../vendor/autoload.php';

define('BASE_URL', dirname($_SERVER['SCRIPT_NAME']));

$router = new Router(dirname(__DIR__) . '/template');

$router->get('/', 'index', 'home')
    ->fallOver('/admin/add-webtoon', 'admin/addWebtoon')
    ->run();