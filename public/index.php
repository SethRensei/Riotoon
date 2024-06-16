<?php

use Riotoon\Controller\Router;

require_once '../vendor/autoload.php';

define('BASE_URL', dirname($_SERVER['SCRIPT_NAME']));

$router = new Router(dirname(__DIR__) . '/template');

$router->get('/', 'index', 'home')
    ->get('/webtoon/[i:id]-[*:title]', 'post/show', 'show-webt')
    ->get('/webtoon/read/[i:id]-[*:title]/[*:chapt]', 'post/read', 'read')
    ->get('/webtoon/genre/[i:id]-[*:label]', 'post/genres', 'genre')
    // ADMIN
    ->get('/admin', 'admin/index', 'home-admin')
    ->fallOver('/admin/add-webtoon', 'admin/webtoon/add', 'add-webt')
    ->fallOver('/admin/update-webtoon/[i:id]', 'admin/webtoon/edit', 'edit-webt')
    ->post('/admin/delete-webtoon/[i:id]', 'admin/webtoon/delete', 'del-webt')
    ->fallOver('/admin/add-chapter/[i:id]', 'admin/chapter/add', 'add-chap')
    ->run();