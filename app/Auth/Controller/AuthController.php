<?php

namespace App\Auth\Controller;

use \PDO;
use App\Auth\Model\AuthModel;
use Src\Application\UseCases\VerificaUsuario;
use Src\Domain\Repositories\UserRepositoryInterface;
use Src\Infrastructure\Repository\PDOUserRepository;

class AuthController
{
    private UserRepositoryInterface $repository;
    private VerificaUsuario $verificaUsuarioUseCase;

    public function __construct(PDO $pdo)
    {
        $this->repository = new PDOUserRepository($pdo);
        $this->verificaUsuarioUseCase = new VerificaUsuario($this->repository);
    }

    public function index()
    {
    	include_once __DIR__ . '/../View/index.phtml';
    }

    public function login(array $post)
    {
        if(isset($post) && !empty($post)) {
            $result = $this->verificaUsuarioUseCase->execute($post['email'], $post['senha']);

            if ($result->isError()) {
                throw new \Exception($result->getMessage());
            }

            session_start();
            $_SESSION['user'] = $result->getData();

    	    header("Location: /dashboard");
    	} else {
    	    include_once __DIR__ . '/../View/login.phtml';
    	}
    }

    public function logout()
    {
        session_start();

        unset($_SESSION);

        session_destroy();

        header('Location: /');
    }

    public function cadastro()
    {
        if(isset($_POST) && !empty($_POST)) {
            $authModel = new AuthModel();
    	    $authModel->cadastrar($_POST);
        } else {
            include_once __DIR__ . '/../View/cadastro.phtml';
        }
    }
}
