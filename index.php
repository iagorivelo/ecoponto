<?php

require_once "vendor/autoload.php";

use src\Auth\Controller\AuthController;
use src\Ecoponto\Controller\EcopontoController;

$parse_url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch($parse_url)
{
    case '/':
        AuthController::index();
    
    	break;
    	
    case '/login':
        AuthController::login();
    
	    break;
    
    case '/logout':
        AuthController::logout();
    
	    break;

    case '/cadastro':
        AuthController::cadastro();
    
        break;

    case '/dashboard':
        EcopontoController::index();
    
        break;

    default:
        echo "Erro 404";
        
	    break;
}
#REALIZANDO TESTE DE COMMIT