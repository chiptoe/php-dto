<?php
declare(strict_types=1);

namespace Project\DTOConverter;

use Project\Exceptions\AccessToUninitialisedPropertyException;

class PropertyTypeListException extends BaseException
{
    /**
     * @param list<PropertyTypeException> $exceptions
     */
    public function __construct(
        private string $className,
        private array $exceptions = [],
        \Throwable|null $previous = null,
        string $message = '',
    ) {
        parent::__construct($message, 0, $previous);
    }

    public function add(PropertyTypeException $e): self
    {
        $this->exceptions[] = $e;

        return $this;
    }

    public function hasSomeExceptions(): bool
    {
        return count($this->exceptions) > 0;
    }

    /**
     * @return list<PropertyTypeException> $exceptions
     */
    public function getExceptions(): array
    {
        return $this->exceptions;
    }

    public function getClassName(): string
    {
        if (!isset($this->className)) {
            throw new AccessToUninitialisedPropertyException();
        }

        return $this->className;
    }
}
