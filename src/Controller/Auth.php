<?php

namespace Riotoon\Controller;

class Auth {

    public static function check()
    {
        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if(!isset($_SESSION['User'])) {
            throw new Security('Connexion requise');
        }
    }
}