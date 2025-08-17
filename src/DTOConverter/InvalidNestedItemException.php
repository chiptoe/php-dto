<?php
declare(strict_types=1);

namespace Project\DTOConverter;

class InvalidNestedItemException extends BaseException
{
    public function __construct(
        public readonly string $invalidPropertyName,
        public readonly int $nestedIndex,
        \Throwable|null $previous = null,
        string $message = '',
    ) {
        if ($message === '') {
            $message = 'Invalid nested item (' . $invalidPropertyName . ':' . $nestedIndex . ').';
        }

        parent::__construct($message, 0, $previous);
    }
}
