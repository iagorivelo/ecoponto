<?php

namespace Src\Domain\Repositories;

use Src\Domain\Entities\User;

interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?User;
    public function save(User $user): User;
}
