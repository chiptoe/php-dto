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

    /**
     * @param list<string> $useClasses
     */
    public function getClassHeader(
        string $namespace,
        array $useClasses,
        string $className,
    ): string
    {
        $temp = '';

        $temp .= '<?php' . PHP_EOL;
        $temp .= PHP_EOL;
        $temp .= 'declare(strict_types=1);' . PHP_EOL;
        $temp .= PHP_EOL;
        $temp .= 'namespace ' . $namespace . ';' . PHP_EOL;
        $temp .= PHP_EOL;
        if (count($useClasses) > 0) {
            $temp .= implode(PHP_EOL, array_map(fn($item) => ('use ' . $item . ';'), $useClasses)) . PHP_EOL;
            $temp .= PHP_EOL;
        }
        $temp .= 'final class ' . $className . PHP_EOL;
        $temp .= '{' . PHP_EOL;

        return $temp;
    }

    public function getClassFooter(): string
    {
        return '}' . PHP_EOL;
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
    ): array
    {
        $temp = [];
        $e = new AggregateException($converter::class);

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
