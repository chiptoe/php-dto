<?php
declare(strict_types=1);

namespace Project\ValueObject;

use Exception;

class PositiveIntException extends \Exception
{
    public function __construct(
        private int $max,
        private bool $isTooBig,
        string $message = '',
        \Throwable|null $previous = null,
    ) {
        if ($message === '') {
            $message = 'The (value) must be valid (' . PositiveInt::class . ').';
        }

        parent::__construct($message, 0, $previous);
    }

    public function getMax(): int
    {
        return $this->max;
    }

    public function isIsTooBig(): bool
    {
        return $this->isTooBig;
    }
}
