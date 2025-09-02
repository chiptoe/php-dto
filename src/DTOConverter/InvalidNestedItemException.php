<?php
declare(strict_types=1);

namespace Project\DTOConverter;

class InvalidNestedItemException extends BaseException
{
    public function __construct(
        private string $invalidPropertyName,
        private int $nestedIndex,
        \Throwable|null $previous = null,
        string $message = '',
    ) {
        if ($message === '') {
            $message = 'Invalid nested item (' . $invalidPropertyName . ':' . $nestedIndex . ').';
        }

        parent::__construct($message, 0, $previous);
    }

    public function getInvalidPropertyName(): string
    {
        return $this->invalidPropertyName;
    }

    public function getNestedIndex(): int
    {
        return $this->nestedIndex;
    }
}
