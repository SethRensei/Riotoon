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

$router->get('/', 'index', 'home')
    ->get('/webtoon/[i:id]-[*:title]', 'post/show', 'show-webt')
    ->get('/webtoon/read/[i:id]-[*:title]/[*:chapt]', 'post/read', 'read')
    ->get('/webtoon/genre/[i:id]-[*:label]', 'post/genres', 'genre')
    ->post('/webtoon/votes', 'post/vote', 'vote')
    ->fallOver('/signup-riotoon', 'signup', 'signup')
    ->fallOver('/verify/[*:pseudo]', 'verifyAccount', 'verif')
    ->post('/logout-riotoon', 'logout', 'logout')
    // ADMIN
    ->get('/admin', 'admin/index', 'home-admin')
    ->fallOver('/admin/add-webtoon', 'admin/webtoon/add', 'add-webt')
    ->fallOver('/admin/update-webtoon/[i:id]', 'admin/webtoon/edit', 'edit-webt')
    ->post('/admin/delete-webtoon/[i:id]', 'admin/webtoon/delete', 'del-webt')
    ->fallOver('/admin/add-chapter/[i:id]', 'admin/chapter/add', 'add-chap')
    ->run();