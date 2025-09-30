<?php

declare(strict_types=1);

namespace Src\Domain\ValueObjects;

final class Email
{
    private function __construct(
        public string $email
    ) {}

    public static function criar(string $email)
    {
        if (empty($email)) {
            return Result::error('Email não pode estar vazio!');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return Result::error('Email não é válido!');
        }

        return Result::success('Email válido!', new self($email));
    }
}
