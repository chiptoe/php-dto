<?php
declare(strict_types=1);

namespace Project\DTOConverter;

class NestedItemException extends BaseException
{
    public function __construct(
        public readonly string $invalidPropertyName,
        public readonly string $nestedKey,
        \Throwable|null $previous = null,
        string $message = '',
    ) {
        if ($message === '') {
            $message = 'Invalid type of property (' . $invalidPropertyName . ') at nested key (' . $nestedKey . ').';
        }

        parent::__construct($message, 0, $previous);
    }
}
