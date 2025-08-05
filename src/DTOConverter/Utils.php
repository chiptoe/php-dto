<?php
declare(strict_types=1);

namespace Project\DTOConverter;

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

        $temp = preg_replace('/([a-z])([A-Z])/g', '$1_$2', $temp);

        $temp = preg_replace('/([a-z])([0-9])/g', '$1_$2', $temp);

        $temp = preg_replace('/([A-Z])([A-Z])/g', '$1_$2', $temp);

        $temp = preg_replace('/([0-9])([A-Z])/g', '$1_$2', $temp);

        return strtoupper($temp);
    }
}
