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
