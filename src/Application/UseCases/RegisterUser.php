<?php
namespace Src\Application\UseCases;

use Src\Domain\Repositories\UserRepositoryInterface;
use Src\Domain\Entities\User;

class RegisterUser
{
    private UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(array $data): User
    {
        // Basic validation (move to validator in real app)
        if (empty($data['email']) || empty($data['password'])) {
            throw new \InvalidArgumentException('Email e senha são obrigatórios.');
        }

        // check if already exists
        $existing = $this->repository->findByEmail($data['email']);
        if ($existing) {
            throw new \DomainException('Usuário já existe.');
        }

        // Hash password
        $hashed = password_hash($data['password'], PASSWORD_DEFAULT);

        $user = new User($data['email'], $hashed, $data['name'] ?? null);
        return $this->repository->save($user);
    }
}
