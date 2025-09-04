<?php
declare(strict_types=1);

namespace Project\DTOConverter;

use Tests\DTO\IConverter;

class Utils
{
    /**
     * @param list<string> $keys
     * @throws MissingKeysException
     */
    public function checkInputData(array $keys, mixed $inputData): void
    {
        if (!is_array($inputData)) {
            throw new BaseException('The (inputData) must be array.');
        }

        $missingKeys = [];
        foreach ($keys as $key) {
            if (!array_key_exists($key, $inputData)) {
                $missingKeys[] = $key;
            }
        }

        if (count($missingKeys) > 0) {
            throw new MissingKeysException($missingKeys);
        }
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

    public function getClassName(string $fqcn): string
    {
        $temp = explode('\\', $fqcn);

        return end($temp);
    }

    public function isNotStringOrBlank($value): bool
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
    public function stringContainsWhitespace(string $value): int|false
    {
        return preg_match('/\s/u', $value);
    }

    /**
     * @template T
     *
     * @param IConverter<T> $converter
     *
     * @return list<T>
     *
     * @throws AggregateException
     */
    public function convertList(
        mixed $inputData,
        string $assocKey,
        IConverter $converter,
        string $classNameForAggregateException,
    ): array
    {
        $temp = [];
        $e = new AggregateException($classNameForAggregateException);

        $index = 0;
        $items = $inputData[$assocKey];

        foreach ($items as $item) {
            try {
                $temp[] = $converter->convert($item);
            } catch (\Throwable $th) {
                $e->add(new InvalidNestedItemException($assocKey, $index, $th));
            }

            $index++;
        }

        if ($e->hasSomeExceptions()) {
            throw $e;
        }

        return $temp;
    }
}
