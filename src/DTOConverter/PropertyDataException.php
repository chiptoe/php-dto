<?php
declare(strict_types=1);

namespace Project\DTOConverter;

class PropertyDataException extends BaseException
{
    public function __construct(
        public readonly string $invalidPropertyName,
        \Throwable|null $previous = null,
        string $message = '',
    ) {
        if ($message === '') {
            $message = 'Invalid data for property (' . $invalidPropertyName . ').';
        }

        parent::__construct($message, 0, $previous);
    }
}
