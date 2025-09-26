<?php

namespace Src\Domain\Repositories;

use Src\Domain\Entities\User;
use Src\Domain\ValueObjects\Result;

interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?User;
    public function save(User $user): Result;
}
