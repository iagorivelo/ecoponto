<?php

namespace App\Ecoponto\Controller;

use App\Ecoponto\Model\EcopontoModel;

class EcopontoController
{
    public static function index()
    {
        session_start();

        // if(!isset($_SESSION['email']) || empty($_SESSION['email'])) {
        //     header('Location: login?noLogin=true');
        // } else {
            include_once __DIR__ . '/../View/index.phtml';
        // }
    }
}
