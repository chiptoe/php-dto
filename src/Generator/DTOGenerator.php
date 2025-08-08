<?php

declare(strict_types=1);

namespace Project\Generator;

use Project\DTOConverter\Utils;

final class DTOGenerator
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
        $namespace = 'Tests\DTO\TopicDTO';

        $accessExceptionClass = 'Project\Exceptions\AccessToUninitialisedPropertyException';
        $useClasses = [
            $accessExceptionClass,
            ...array_unique(array_map(fn($it) => $it['type'], $inputData['properties'])),
        ];

        $className = $inputData['dtoName'] . 'DTO';

        $temp = '';

        $temp .= $this->utils->getClassHeader(
            $namespace,
            $useClasses,
            $className,
        );

        foreach ($inputData['properties'] as $property) {
            $temp .= '    ' . 'private ' . $this->utils->getClassName($property['type']) . ' ' . '$' . $property['name'] . ';' . PHP_EOL . PHP_EOL;
        }

        foreach ($inputData['properties'] as $idx => $property) {
            if ($idx > 0) {
                $temp .= PHP_EOL;
            }
            $temp .= $this->getGetter($property['name'], $property['type'], $this->utils->getClassName($accessExceptionClass));
            $temp .= PHP_EOL;
            $temp .= $this->getSetter($property['name'], $property['type']);
        }

        $temp .= $this->utils->getClassFooter();

        return $temp;
    }

    public function getGetter(
        string $propertyName,
        string $propertyType,
        string $accessExceptionClassName,
    ): string
    {
        $temp = '';

        $temp .= '    ' . 'public function get' . ucfirst($propertyName) . '(): ' . $this->utils->getClassName($propertyType) . PHP_EOL;
        $temp .= '    ' . '{' . PHP_EOL;
        $temp .= '    ' . '    ' . 'if (!isset($this->' . $propertyName . ')) {' . PHP_EOL;
        $temp .= '    ' . '    ' . '    ' . 'throw new ' . $accessExceptionClassName . '();' . PHP_EOL;
        $temp .= '    ' . '    ' . '}' . PHP_EOL;
        $temp .= PHP_EOL;
        $temp .= '    ' . '    ' . 'return $this->' . $propertyName . ';' . PHP_EOL;
        $temp .= '    ' . '}' . PHP_EOL;

        return $temp;
    }

    public function getSetter(
        string $propertyName,
        string $propertyType,
    ): string
    {
        $temp = '';

        $temp .= '    ' . 'public function set' . ucfirst($propertyName) . '(' . $this->utils->getClassName($propertyType) . ' $' . $propertyName . '): ' . 'self' . PHP_EOL;
        $temp .= '    ' . '{' . PHP_EOL;
        $temp .= '    ' . '    ' . '$this->' . $propertyName . ' = $' . $propertyName . ';' . PHP_EOL;
        $temp .= PHP_EOL;
        $temp .= '    ' . '    ' . 'return $this;' . PHP_EOL;
        $temp .= '    ' . '}' . PHP_EOL;

        return $temp;
    }
}
