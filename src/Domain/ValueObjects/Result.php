<?php

declare(strict_types=1);

namespace Src\Domain\ValueObjects;

enum ResultStatus: string
{
    case SUCCESS = 'success';
    case ERROR = 'error';
}

final readonly class Result
{
    private function __construct(
        protected ResultStatus $status,
        public ?string $message = null,
        $data = null
    ) {}

    public static function sucess(?string $message = null, $data = null): self
    {
        return new self(ResultStatus::SUCCESS, $message, $data);
    }

    public static function error(?string $message = null, $data = null): self
    {
        return new self(ResultStatus::ERROR, $message, $data);
    }

    public function isSucess(): bool
    {
        return $this->status === ResultStatus::SUCCESS;
    }

    public function isError(): bool
    {
        return $this->status === ResultStatus::ERROR;
    }
}
