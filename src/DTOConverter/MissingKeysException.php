<?php
declare(strict_types=1);

namespace Project\DTOConverter;

class MissingKeysException extends BaseException
{
    public function __construct(
        public readonly array $missingKeys,
        \Throwable|null $previous = null,
        string $message = '',
    ) {
        if ($message === '') {
            $message = 'The array-keys (' . implode(', ', $missingKeys) . ') must exist.';
        }

        parent::__construct($message, 0, $previous);
    }
}
