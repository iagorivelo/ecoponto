<?php

require_once "vendor/autoload.php";

use Dotenv\Dotenv;
use App\Auth\Controller\AuthController;
use App\config\Database;
use App\Ecoponto\Controller\EcopontoController;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$pdo = Database::connect();
$authController = new AuthController($pdo);

$parse_url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch($parse_url)
{
    case '/':
        $authController->index();
    
    	break;
    	
    case '/login':
        $authController->login($_POST);
    
	    break;
    
    case '/logout':
        $authController->logout();
    
	    break;

    case '/cadastro':
        $authController->cadastro();
    
        break;

    case '/dashboard':
        EcopontoController::index();
    
        break;

    default:
        echo "Erro 404";
        
	    break;
}
