<?php
declare(strict_types=1);

namespace Project\Utils;

final class StringUtils
{
    public static function isNotStringOrBlank(mixed $value): bool
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
     * checks for any Unicode whitespace
     * 
     * @return int|false 1 - match, 0 - no match, false - error
     */
    public static function containsWhitespace(string $value): int|false
    {
        return preg_match('/\s/u', $value);
    }

    public static function getClassName(string $fqcn): string
    {
        $temp = explode('\\', $fqcn);

        return end($temp);
    }

    /**
     * @param string $camelCase
     * @return string
     */
    public static function toScreamingSnakeCase(string $camelCase): string
    {
        $temp = trim($camelCase);

        $temp = preg_replace('/([a-z])([A-Z])/', '$1_$2', $temp);

        $temp = preg_replace('/([a-z])([0-9])/', '$1_$2', $temp);

        $temp = preg_replace('/([A-Z])([A-Z])/', '$1_$2', $temp);

        $temp = preg_replace('/([0-9])([A-Z])/', '$1_$2', $temp);

        return strtoupper($temp);
    }
}
