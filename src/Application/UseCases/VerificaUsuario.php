<?php
namespace Src\Application\UseCases;

use Src\Domain\Repositories\UserRepositoryInterface;
use Src\Domain\ValueObjects\Email;
use Src\Domain\ValueObjects\Result;

class VerificaUsuario
{
    private UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(Email $email): Result
    {
        $user = $this->repository->findByEmail($email);
        if (!$user) {
            return Result::error('Usuário não Cadastrado!');
        }

        return Result::success('', $user);
    }
}
