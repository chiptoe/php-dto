<?php
declare(strict_types=1);

namespace Project\Utils;

final class StringCoreUtils
{
    public function isNotStringOrBlank(mixed $value): bool
    {
        if (!is_string($value)) {
            return true;
        }

        if (trim($value) === '') {
            return true;
        }

        return false;
    }
}
