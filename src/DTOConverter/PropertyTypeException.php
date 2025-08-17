<?php
declare(strict_types=1);

namespace Project\DTOConverter;

class PropertyTypeException extends BaseException
{
    public function __construct(
        public readonly string $invalidPropertyName,
        public readonly string $subKey,
        \Throwable|null $previous = null,
        string $message = '',
    ) {
        if ($message === '') {
            $message = 'Invalid type of property (' . $invalidPropertyName . ').';
        }

        parent::__construct($message, 0, $previous);
    }
}
