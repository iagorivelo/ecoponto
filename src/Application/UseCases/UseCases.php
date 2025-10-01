<?php
namespace Src\Application\UseCases;

use Src\Domain\Repositories\UserRepositoryInterface;

class UseCases
{
    private UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function verificaUsuarioUseCase(): VerificaUsuario
    {
        return new VerificaUsuario($this->repository);
    }

    public function verificaSenhaUseCase(): VerificaSenha
    {
        return new VerificaSenha();
    }

    public function cadastraUsuarioUseCase(): CadastraUsuario
    {
        return new CadastraUsuario($this->repository);
    }

    public function cadastraEnderecoUseCase(): CadastraEndereco
    {
        return new CadastraEndereco($this->repository);
    }
}
