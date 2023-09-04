<?php

namespace src\Ecoponto\Controller;

use src\Ecoponto\Model\EcopontoModel;

class EcopontoController
{
    public static function index()
    {
        session_start();

        if(!isset($_SESSION['email']) || empty($_SESSION['email'])) {
            header('Location: login?noLogin=true');
        } else {
            include_once __DIR__ . '/../View/index.phtml';
        }
    }
}
