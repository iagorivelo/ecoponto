<?php
namespace Src\Application\UseCases;

use Src\Domain\Entities\User;
use Src\Domain\ValueObjects\Password;
use Src\Domain\ValueObjects\Result;

class VerificaSenha
{
    public function __construct() {}

    public function execute(User $user, Password $password): Result
    {
        if (!$password->verificar($password->password, $user->getPassword()->password)) {
            return Result::error('Senha incorreta!');
        }

        return Result::success('', $user);
    }
}
