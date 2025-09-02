<?php
declare(strict_types=1);

namespace Project\DTOConverter;

class MissingKeysException extends BaseException
{
    public function __construct(
        private array $missingKeys,
        \Throwable|null $previous = null,
        string $message = '',
    ) {
        if ($message === '') {
            $message = 'The array-key' . (count($missingKeys) > 1 ? 's' : '') . ' (' . implode(', ', $missingKeys) . ') must exist.';
        }

        parent::__construct($message, 0, $previous);
    }

    public function getMissingKeys(): array
    {
        return $this->missingKeys;
    }
}
