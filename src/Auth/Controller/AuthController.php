<?php

namespace src\Auth\Controller;

use src\Auth\Model\AuthModel;

class AuthController
{
    public static function index()
    {
    	include_once __DIR__ . '/../View/index.phtml';
    }

    public static function login()
    {
    	if(isset($_POST) && !empty($_POST)) {
    	    $authModel = new AuthModel();
    	    $authModel->login($_POST);
    	} else {
    	    include_once __DIR__ . '/../View/login.phtml';
    	}
    }

    public static function logout()
    {
        session_start();

        unset($_SESSION);

        session_destroy();

        header('Location: /');
    }

    public static function cadastro()
    {
        if(isset($_POST) && !empty($_POST)) {
            $authModel = new AuthModel();
    	    $authModel->cadastrar($_POST);
        } else {
            include_once __DIR__ . '/../View/cadastro.phtml';
        }
    }
}
