<?php

namespace App\Auth\Controller;

use \PDO;
use Src\Application\UseCases\UseCases;
use Src\Domain\Entities\User;
use Src\Domain\Repositories\UserRepositoryInterface;
use Src\Domain\ValueObjects\CPF;
use Src\Domain\ValueObjects\Email;
use Src\Domain\ValueObjects\Password;
use Src\Infrastructure\Repository\PDOUserRepository;

class AuthController
{
    private UserRepositoryInterface $repository;
    private UseCases $useCases;

    public function __construct(PDO $pdo)
    {
        $this->repository = new PDOUserRepository($pdo);
        $this->useCases = new UseCases($this->repository);
    }

    public function index()
    {
        session_start();

        if (!isset($_SESSION['name']) || empty($_SESSION['name'])) {
            return include_once __DIR__ . '/../View/index.phtml';
        }

        header("Location: /dashboard");
    }

    public function login(array $post)
    {
        if (!isset($post) || empty($post)) {
            
            return include_once __DIR__ . '/../View/login.phtml';
        }

        $email = Email::criar($post['email']);
        $password = Password::criar($post['senha']);

        if ($email->isError()) {
            throw new \Exception($email->message);
        }

        if ($password->isError()) {
            throw new \Exception($password->message);
        }

        $result = $this->useCases->verificaUsuarioUseCase()->execute($email->data);
        if ($result->isError()) {
            throw new \Exception($result->message);
        }

        $user = $result->data;
        $resultSenha = $this->useCases->verificaSenhaUseCase()->execute($user, $password->data);

        if ($resultSenha->isError()) {
            throw new \Exception($resultSenha->message);
        }

        session_start();
        $_SESSION['name'] = $result->data->getName();

        header("Location: /dashboard");
    }

    public function logout()
    {
        session_start();

        unset($_SESSION);

        session_destroy();

        header('Location: /');
    }

    public function cadastro(array $post)
    {
        if (!isset($post) || empty($post)) {
            return include_once __DIR__ . '/../View/cadastro.phtml';
        }

        $email = Email::criar($post['email']);
        $cpf = CPF::criar($post['cpf']);
        $password = Password::criar($post['senha']);

        if ($email->isError()) {
            throw new \Exception($email->message);
        }

        if ($cpf->isError()) {
            throw new \Exception($cpf->message);
        }

        if ($password->isError()) {
            throw new \Exception($password->message);
        }

        $result = $this->useCases->cadastraUsuarioUseCase()->execute($email->data);

        if ($result->isSuccess()) {
            return include_once __DIR__ . '/../View/cadastro.phtml';
        }

        $user = new User(
            $email->data,
            $password->data,
            $post['nome_completo'],
            $cpf->data,
            $post['telefone']
        );

        $resultCadastroUsuario = $this->useCases->cadastraUsuarioUseCase()->execute($user);

        session_start();
        $_SESSION['name'] = $resultCadastroUsuario->data->getName();

        header("Location: /dashboard");
    }
}
