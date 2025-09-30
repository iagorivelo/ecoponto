<?php

declare(strict_types=1);

namespace Src\Domain\ValueObjects;

final class CPF
{
    private function __construct(
        public string $cpf
    ) {}

    /**
     * @return Result<CPF>
     */
    public static function criar(string $cpf)
    {
        if (empty($cpf)) {
            return Result::error('CPF não pode estar vazio!');
        }

        $cpf = preg_replace('/\D/', '', $cpf);

        if (strlen($cpf) !== 11) {
            return Result::error('CPF deve conter 11 dígitos!');
        }

        $validaCPF = self::validarCPF($cpf);
        if ($validaCPF->isError()) {
            return Result::error($validaCPF->message);
        }

        return Result::success($validaCPF->message, new self($cpf));
    }

    private static function validarCPF(string $cpf): Result
    {
        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            return Result::error('CPF inválido, dígitos repetidos!');
        }

        if (self::calculoCPF($cpf)) {
            return Result::success('CPF válido!');
        }

        return Result::error('CPF inválido!');
    }

    private static function calculoCPF(string $cpf): bool
    {
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }

            $d = ((10 * $d) % 11) % 10;
            
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }
}
