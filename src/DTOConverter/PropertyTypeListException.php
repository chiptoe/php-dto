<?php
declare(strict_types=1);

namespace Project\DTOConverter;

class PropertyTypeListException extends BaseException
{
    /**
     * @param list<PropertyTypeException> $list
     */
    public function __construct(
        public readonly array $exceptions = [],
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
}
