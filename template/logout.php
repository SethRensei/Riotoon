<?php

$admin_session = $_SESSION['roles'];
$_SESSION = [];
session_destroy();
if (in_array('ROLE_ADMIN', $admin_session))
    header('Location: ' . $router->url('home'));
echo '<script>window.history.back();</script>';
exit();