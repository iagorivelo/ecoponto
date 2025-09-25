<?php

declare(strict_types=1);

namespace Mapion\Domain\ValueObjects;

use Mapion\Domain\ValueObjects\Result;

class Email
{
    private function __construct(
        private readonly string $email
    ) {}

    public static function criar(string $email): Result
    {
        if (empty($email)) {
            return Result::error('Email não pode ser vazio.');
        }

        $email = trim($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return Result::error('Email (' . $email . ') inválido: formato incorreto.');
        }

        return Result::success('Email válido', new self($email));
    }

    public function getDominio(): string
    {
        return substr($this->email, strpos($this->email, '@') + 1);
    }

    public function getNome(): string
    {
        return substr($this->email, 0, strpos($this->email, '@'));
    }

    public function getValor(): string
    {
        return $this->email;
    }
}
