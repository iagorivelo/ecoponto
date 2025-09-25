<?php

namespace Src\Infrastructure\Repository;

use Src\Domain\Repositories\UserRepositoryInterface;
use Src\Domain\Entities\User;
use \PDO;

class PDOUserRepository implements UserRepositoryInterface
{
    private PDO $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare('SELECT id, email, password, name FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);

        $row = $stmt->fetch();

        if (!$row) return null;

        return User::fromArray($row);
    }

    public function save(User $user): User
    {
        // Simple upsert: if id exists, update; else insert
        if ($user->getId()) {
            $stmt = $this->pdo->prepare('UPDATE users SET email = :email, password = :password, name = :name WHERE id = :id');
            $stmt->execute([
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'name' => $user->getName(),
                'id' => $user->getId()
            ]);
            return $user;
        } else {
            $stmt = $this->pdo->prepare('INSERT INTO users (email, password, name) VALUES (:email, :password, :name)');
            $stmt->execute([
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'name' => $user->getName()
            ]);
            $id = (int)$this->pdo->lastInsertId();
            return new User($user->getEmail(), $user->getPassword(), $user->getName(), $id);
        }
    }
}
