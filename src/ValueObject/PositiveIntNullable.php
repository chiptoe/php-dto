<?php
declare(strict_types=1);

namespace Project\ValueObject;

class PositiveIntNullable
{
    public readonly ?int $value;

    public function __construct(mixed $value) {
        if ($value === null) {
            $this->value = $value;
            return;
        }

        PositiveInt::check($value, self::class);

        $this->value = $value;
    }
}
