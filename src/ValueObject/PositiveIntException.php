<?php
declare(strict_types=1);

namespace Project\Exceptions;

use Exception;

class PositiveIntException extends \Exception
{
    public function __construct(
        string $message = '',
        \Throwable|null $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }
}
