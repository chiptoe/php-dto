<?php

declare(strict_types=1);

namespace Project\Generator;

use Project\DTOConverter\Utils;

final class DTOAssocGenerator
{
    public function __construct(
        private Utils $utils,
    )
    {
    }

    /**
     * @param array<string,mixed> $inputData
     */
    public function generate(array $inputData): string
    {
        // validate - dtoName
        if (!array_key_exists('dtoName', $inputData)) {
            throw new \InvalidArgumentException('the (dtoName) must exist as array key');
        }
        $dtoName = $inputData['dtoName'];
        if ($this->utils->isNotStringOrBlank($dtoName)) {
            throw new \InvalidArgumentException('the (dtoName) must be string and filled');
        }

        // validate - fromKeys
        if (!array_key_exists('fromKeys', $inputData)) {
            throw new \InvalidArgumentException('the (fromKeys) must exist as array key');
        }
        $fromKeys = $inputData['fromKeys'];
        if (!is_array($fromKeys)) {
            throw new \InvalidArgumentException('the (fromKeys) must be array');
        }
        foreach ($fromKeys as $index => $fromKey) {
            if ($this->utils->isNotStringOrBlank($fromKey)) {
                throw new \InvalidArgumentException('the (fromKey) must be string and filled, at index ' . $index);
            }
        }

        // prepare data
        $namespace = 'Tests\DTO\TopicDTO';
        $useClasses = [];
        $className = $dtoName . 'DTO' . 'Assoc';

        // render
        $temp = '';

        $temp .= $this->utils->getClassHeader(
            $namespace,
            $useClasses,
            $className,
        );

        $temp .= $this->getConstants($fromKeys);
        $temp .= $this->getKeysFunc($fromKeys);

        $temp .= $this->utils->getClassFooter();

        return $temp;
    }

    public function getConstants(array $fromKeys): string
    {
        $temp = '';

        foreach ($fromKeys as $fromKey) {
            $temp .= '    ' . 'public const ' . $this->utils->toScreamingSnakeCase($fromKey) . ' = ' . '\'' . $fromKey . '\';' . PHP_EOL . PHP_EOL;
        }

        return $temp;
    }

    public function getKeysFunc(array $fromKeys): string
    {
        $temp = '';

        $temp .= '    ' . '/**' . PHP_EOL;
        $temp .= '    ' . ' * @return list<string>' . PHP_EOL;
        $temp .= '    ' . ' */' . PHP_EOL;

        $temp .= '    ' . 'public static function getKeys(): array' . PHP_EOL;
        $temp .= '    ' . '{' . PHP_EOL;
        $temp .= '    ' . '    ' . 'return [' . PHP_EOL;
        foreach ($fromKeys as $fromKey) {
            $temp .= '    ' . '    ' . '    ' . 'self::' . $this->utils->toScreamingSnakeCase($fromKey) . ',' . PHP_EOL;
        }
        $temp .= '    ' . '    ' . '];' . PHP_EOL;
        $temp .= '    ' . '}' . PHP_EOL;


        return $temp;
    }
}
