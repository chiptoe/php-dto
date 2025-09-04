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

    /**
     * @param string $camelCase
     * @return string
     */
    public function toScreamingSnakeCase(string $camelCase): string
    {
        $temp = trim($camelCase);

        $temp = preg_replace('/([a-z])([A-Z])/', '$1_$2', $temp);

        $temp = preg_replace('/([a-z])([0-9])/', '$1_$2', $temp);

        $temp = preg_replace('/([A-Z])([A-Z])/', '$1_$2', $temp);

        $temp = preg_replace('/([0-9])([A-Z])/', '$1_$2', $temp);

        return strtoupper($temp);
    }
}
