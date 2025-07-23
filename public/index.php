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
    ->route(uri:"/webtoon/[i:id]-[*:title]",view:"main/show", name:"webt_show")
    ->route(uri:"/webtoon/read/AEDy6jL8[i:id]-[*:title]/Chapitre-[*:chapt]",view:"main/read", name:"read")

    // ADMIN
    ->route(uri:"/admin",view:"admin/index", name:"admin_index", method:"GET")
    ->route(uri:"/admin/webtoon/new",view:"admin/webtoon/add", name:"webt_new", method:"GET|POST")
    ->route(uri:"/admin/webtoon/edit/[i:id]",view:"admin/webtoon/edit", name:"webt_edit", method:"GET|POST")
    ->route(uri:"/admin/webtoon/delete/[i:id]",view:"admin/webtoon/delete", name:"webt_del", method:"POST")
    ->route(uri:"/admin/chapter/new/[i:id]",view:"admin/chapter/add", name:"chap_new", method:"GET|POST")
    ->run();