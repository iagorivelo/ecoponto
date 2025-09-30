<?php
namespace Src\Application\UseCases;

use Src\Domain\Entities\User;
use Src\Domain\Repositories\UserRepositoryInterface;
use Src\Domain\ValueObjects\Email;
use Src\Domain\ValueObjects\Result;

class CadastraUsuario
{
    private UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(
        User $user
    ): Result
    {
        $user = $this->repository->save($user);

        return Result::success($user->message, $user->data);
    }
}
