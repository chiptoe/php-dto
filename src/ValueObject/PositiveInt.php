<?php
declare(strict_types=1);

namespace Project\ValueObject;

class PositiveInt
{
    public readonly int $value;

    public function __construct(mixed $value)
    {
        self::check($value, self::class);

        $this->value = $value;
    }

    public static function check(mixed $value, string $className): void
    {
        $message = 'The (value) must be valid (' . $className . ').';
        if (!is_int($value)) {
            throw new \InvalidArgumentException($message);
        }
        if ($value <= 0) {
            throw new \InvalidArgumentException($message);
        }
    }
}
