<?php
declare(strict_types=1);

namespace Project\ValueObject;

class PositiveInt
{
    /**
     * On 64-bit PHP: PHP int max 9223372036854775807 is much larger, so MySQL INT (2147483647) is less.
     * PHP is typically 64-bit on most modern servers and desktops.
     */
    public const MAX_MYSQL_INT = 2147483647;

    public readonly int $value;

    public function __construct(
        mixed $value,
        private int $max,
    )
    {
        $this->check($value, self::class);

        $this->value = $value;
    }

    public function check(mixed $value, string $className): void
    {
        $message = 'The (value) must be valid (' . $className . ').';
        if (!is_int($value)) {
            throw new \InvalidArgumentException($message);
        }
        if ($value <= 0) {
            throw new \InvalidArgumentException($message);
        }
        if ($value > $this->max) {
            throw new \InvalidArgumentException($message);
        }
    }
}
