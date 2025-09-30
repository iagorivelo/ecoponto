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
        public mixed $data = null
    ) {}

    public static function success(?string $message = null, mixed $data = null): self
    {
        return new self(ResultStatus::SUCCESS, $message, $data);
    }

    public static function error(?string $message = null, mixed $data = null): self
    {
        return new self(ResultStatus::ERROR, $message, $data);
    }

    public function isSuccess(): bool
    {
        return $this->status === ResultStatus::SUCCESS;
    }

    public function isError(): bool
    {
        return $this->status === ResultStatus::ERROR;
    }
}
