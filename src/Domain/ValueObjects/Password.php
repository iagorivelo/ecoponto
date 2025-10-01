<?php

declare(strict_types=1);

namespace Src\Domain\ValueObjects;

final class Password
{
    private function __construct(
        public string $password
    ) {}

    /**
     * @return Result<Password>
     */
    public static function criar(string $password): Result
    {
        $result = self::validar($password);
        if ($result->isError()) {
            return Result::error($result->message);
        }

        return Result::success($result->message, new self($password));
    }

    private static function validar(string $password): Result
    {
        if (strlen($password) < 7) {
            return Result::error('Senha tem que ser maior que 6 caracteres, total: ' . strlen($password));
        }

        return Result::success('Senha válida');
    }

    public function encriptar(): string
    {
        return password_hash($this->password, PASSWORD_DEFAULT);
    }

    public static function verificar(string $password, string $passwordHash): bool
    {
        return password_verify($password, $passwordHash);
    }
}
