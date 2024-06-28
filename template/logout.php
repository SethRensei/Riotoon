<?php

$_SESSION = [];
session_destroy();
header('Location: ' . $router->url('home'));
exit();