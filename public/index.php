<?php

session_start();

use Riotoon\Controller\Router;
use Symfony\Component\Dotenv\Dotenv;

require_once '../vendor/autoload.php';

// Chargement des variables d'environnement depuis le fichier .env
$dotenv = new Dotenv();
$dotenv->load(dirname(__DIR__) . '/.env');

define('BASE_URL', dirname($_SERVER['SCRIPT_NAME']));

$router = new Router(dirname(__DIR__) . '/template');

$router->fallOver('/', 'index', 'home')
    ->fallOver('/webtoon/[i:id]-[*:title]', 'post/show', 'show-webt')
    ->fallOver('/webtoon/read/[i:id]-[*:title]/[*:chapt]', 'post/read', 'read')
    ->fallOver('/webtoon/genre/[i:id]-[*:label]', 'post/genre', 'genre')
    ->fallOver('/profile/[*:pseudo]', 'profile', 'profile')
    ->post('/webtoon/votes', 'post/vote', 'vote')
    ->fallOver('/signup-riotoon', 'signup', 'signup')
    ->fallOver('/verify/[*:pseudo]', 'verifyAccount', 'verif')
    ->post('/logout-riotoon', 'logout', 'logout')
    ->get('/error404', 'error404', 'error')
    // ADMIN
    ->get('/admin', 'admin/index', 'home-admin')
    ->fallOver('/admin/add-chapter/[i:id]', 'admin/chapter/add', 'add-chap')
    ->fallOver('/admin/add-genre', 'admin/genre/add', 'add-genre')
    ->post('/admin/delete-genre/[i:id]', 'admin/genre/delete', 'del-genre')
    ->fallOver('/admin/update-genre/[i:id]', 'admin/genre/edit', 'edit-genre')
    ->get('/admin/genres', 'admin/genre/index', 'see-genres')
    ->get('/admin/users', 'admin/user/index', 'see-users')
    ->fallOver('/admin/user/[*:pseudo]', 'admin/user/editRoles', 'edit-user-admin')
    ->post('/admin/delete-user/[*:pseudo]', 'admin/user/delete', 'del-user')
    ->fallOver('/admin/add-webtoon', 'admin/webtoon/add', 'add-webt')
    ->fallOver('/admin/update-webtoon/[i:id]', 'admin/webtoon/edit', 'edit-webt')
    ->post('/admin/delete-webtoon/[i:id]', 'admin/webtoon/delete', 'del-webt')
    ->run();