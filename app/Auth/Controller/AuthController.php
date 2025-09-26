<?php

namespace App\Auth\Controller;

use \PDO;
use App\Auth\Model\AuthModel;
use Src\Application\UseCases\CadastraEndereco;
use Src\Application\UseCases\CadastraUsuario;
use Src\Application\UseCases\VerificaUsuario;
use Src\Domain\Repositories\UserRepositoryInterface;
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

        $result = $this->verificaUsuarioUseCase->execute($post['email'], $post['senha']);

        if ($result->isError()) {
            throw new \Exception($result->getMessage());
        }

        session_start();
        $_SESSION['name'] = $result->getData()->getName();

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

        $result = $this->verificaUsuarioUseCase->execute($post['email'], $post['senha']);

        if ($result->isSuccess()) {
            return include_once __DIR__ . '/../View/cadastro.phtml';
        }

        $resultCadastroUsuario = $this->cadastraUsuarioUseCase->execute(
            $post['nome_completo'],
            $post['telefone'],
            $post['cpf'],
            $post['email'],
            $post['senha']
        );

        session_start();
        $_SESSION['name'] = $resultCadastroUsuario->getData()->getName();

        header("Location: /dashboard");
    }
}
