<?php
declare(strict_types=1);

namespace Project\DTOConverter;

use Project\Exceptions\AccessToUninitialisedPropertyException;

class AggregateException extends BaseException
{
    /**
     * @param list<BaseException> $exceptions
     */
    public function __construct(
        private string $atClass,
        private array $exceptions = [],
        \Throwable|null $previous = null,
        string $message = '',
    ) {
        parent::__construct($message, 0, $previous);
    }

    public function add(BaseException $e): self
    {
        $this->exceptions[] = $e;

        return $this;
    }

    public function hasSomeExceptions(): bool
    {
        return count($this->exceptions) > 0;
    }

    /**
     * @return list<BaseException> $exceptions
     */
    public function getExceptions(): array
    {
        return $this->exceptions;
    }

    public function getAtClass(): string
    {
        if (!isset($this->atClass)) {
            throw new AccessToUninitialisedPropertyException();
        }

        return $this->atClass;
    }
}
