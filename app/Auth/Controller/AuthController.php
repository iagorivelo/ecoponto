<?php

namespace App\Auth\Controller;

use \PDO;
use Src\Application\UseCases\CadastraEndereco;
use Src\Application\UseCases\CadastraUsuario;
use Src\Application\UseCases\VerificaUsuario;
use Src\Domain\Entities\User;
use Src\Domain\Repositories\UserRepositoryInterface;
use Src\Domain\ValueObjects\CPF;
use Src\Domain\ValueObjects\Email;
use Src\Infrastructure\Repository\PDOUserRepository;

class AuthController
{
    private UserRepositoryInterface $repository;
    private VerificaUsuario $verificaUsuarioUseCase;
    private CadastraUsuario $cadastraUsuarioUseCase;
    private CadastraEndereco $cadastraEnderecoUseCase;

    public function __construct(PDO $pdo)
    {
        $this->repository = new PDOUserRepository($pdo);
        $this->verificaUsuarioUseCase = new VerificaUsuario($this->repository);
        $this->cadastraUsuarioUseCase = new CadastraUsuario($this->repository);
        $this->cadastraEnderecoUseCase = new CadastraEndereco($this->repository);
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

        if ($email->isError()) {
            throw new \Exception($email->message);
        }

        $result = $this->verificaUsuarioUseCase->execute($email->data, $post['senha']);

        if ($result->isError()) {
            throw new \Exception($result->message);
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

        if ($email->isError()) {
            throw new \Exception($email->message);
        }

        if ($cpf->isError()) {
            throw new \Exception($cpf->message);
        }

        $result = $this->verificaUsuarioUseCase->execute($email->data, $post['senha']);

        if ($result->isSuccess()) {
            return include_once __DIR__ . '/../View/cadastro.phtml';
        }

        $user = new User(
            $email->data,
            password_hash($post['senha'], PASSWORD_DEFAULT),
            $post['nome_completo'],
            $cpf->data,
            $post['telefone']
        );

        $resultCadastroUsuario = $this->cadastraUsuarioUseCase->execute($user);

        session_start();
        $_SESSION['name'] = $resultCadastroUsuario->data->getName();

        header("Location: /dashboard");
    }
}
