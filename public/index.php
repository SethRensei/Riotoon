<?php

use Riotoon\Router\Router;
use Symfony\Component\Dotenv\Dotenv;

session_start();

require_once __DIR__ ."/../vendor/autoload.php";

$dotenv = new Dotenv();
$dotenv->load(dirname(__DIR__) . '/.env.local');

define(constant_name: 'BASE_URL', value: dirname(path: $_SERVER['SCRIPT_NAME']));

$router = new Router(dirname(__DIR__) ."/template");

$router->route(uri: "/",view: "main/index", name: "home")
    ->route(uri:"/admin/webtoon/new",view:"admin/webtoon/add", name:"webt_new", method:"GET|POST")
    ->run();