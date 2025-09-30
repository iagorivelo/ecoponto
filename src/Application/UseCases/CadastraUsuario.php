<?php
namespace Src\Application\UseCases;

use Src\Domain\Entities\User;
use Src\Domain\Repositories\UserRepositoryInterface;
use Src\Domain\ValueObjects\Result;

class CadastraUsuario
{
    private UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(
        string $nomeCompleto, string $telefone, string $cpf, string $email, string $password
    ): Result
    {
        $objectUser = User::fromArray([
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'name' => $nomeCompleto,
            'cpf' => $cpf,
            'telefone' => $telefone
        ]);

        $user = $this->repository->save($objectUser);

        return Result::success($user->message, $user->data);
    }
}
