<?php

namespace Src\Infrastructure\Repository;

use Src\Domain\Repositories\UserRepositoryInterface;
use Src\Domain\Entities\User;
use \PDO;
use Src\Domain\ValueObjects\Result;

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

    /**
     * @return Result<User>
     */
    public function save(User $user): Result
    {
        $stmt = $this->pdo->prepare('INSERT INTO users (name, cpf, email, telefone, password, created_at, updated_at) 
                                    VALUES (:name, :cpf, :email, :telefone, :password, :created, :updated)');
        $stmt->execute([
            'name' => $user->getName(),
            'cpf' => $user->getCpf(),
            'email' => $user->getEmail(),
            'telefone' => $user->getTelefone(),
            'password' => $user->getPassword(),
            'created' => Date('Y-m-d H:i:s'),
            'updated' => Date('Y-m-d H:i:s')
        ]);
        $id = (int) $this->pdo->lastInsertId();

        return Result::success('Usuário Cadastrado com Sucesso!', new User(
            $user->getEmail(),
            $user->getPassword(),
            $user->getName(),
            $user->getCpf(),
            $user->getTelefone(),
            $id
        ));
    }
}
