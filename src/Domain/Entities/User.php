<?php

namespace Src\Domain\Entities;

use Src\Domain\ValueObjects\CPF;
use Src\Domain\ValueObjects\Email;

class User
{
    public function __construct(
        private readonly Email $email,
        private string $password,
        private string $name,
        private CPF $cpf,
        private string $telefone,
        private ?int $id = null
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCpf(): CPF
    {
        return $this->cpf;
    }

    public function getTelefone(): string
    {
        return $this->telefone;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'password' => $this->password,
            'name' => $this->name,
        ];
    }
}
