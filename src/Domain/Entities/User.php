<?php

namespace Src\Domain\Entities;

class User
{
    public function __construct(
        private readonly string $email,
        private string $password,
        private string $name,
        private string $cpf,
        private string $telefone,
        private ?int $id = null
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
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

    public function getCpf(): string
    {
        return $this->cpf;
    }

    public function getTelefone(): string
    {
        return $this->telefone;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['email'] ?? '',
            $data['password'] ?? '',
            $data['name'] ?? '',
            $data['cpf'] ?? '',
            $data['telefone'] ?? '',
            isset($data['id']) ? (int)$data['id'] : null
        );
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
