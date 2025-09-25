<?php

namespace Src\Domain\ValueObjects;

/**
 * @template T
 */
class Result
{
    public const SUCCESS = 'success';
    public const ERROR   = 'error';

    private string $status;
    private ?string $message;
    
    /**
     * @var T|null
     */
    private mixed $data;

    /**
     * @param T|null $data
     */
    public function __construct(
        string $status,
        ?string $message = null,
        mixed $data = null,
    ) {
        if (!in_array($status, [self::SUCCESS, self::ERROR], true)) {
            throw new \InvalidArgumentException('Status inválido');
        }

        $this->status  = $status;
        $this->message = $message;
        $this->data    = $data;
    }

    /**
     * @template U
     * @param U|null $data
     * @return Result<U>
     */
    public static function success(?string $message = null, mixed $data = null): self
    {
        return new self(self::SUCCESS, $message, $data);
    }

    /**
     * @template U
     * @param U|null $data
     * @return Result<U>
     */
    public static function error(?string $message = null, mixed $data = null): self
    {
        return new self(self::ERROR, $message, $data);
    }

    public function isSuccess(): bool
    {
        return $this->status === self::SUCCESS;
    }

    public function isError(): bool
    {
        return $this->status === self::ERROR;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @return T|null
     */
    public function getData(): mixed
    {
        return $this->data;
    }
}
