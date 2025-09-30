<?php

declare(strict_types=1);

namespace Src\Domain\ValueObjects;

final class Email
{
    public function __construct(
        public string $email
    ) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return Result::error('Email não é válido!');
        }

        return Result::sucess('Email válido!', $email);
    }
}
