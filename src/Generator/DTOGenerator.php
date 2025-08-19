<?php

declare(strict_types=1);

namespace Project\Generator;

use Project\DTOConverter\Utils;

final class DTOGenerator
{
    public function __construct(
        private Utils $utils,
    ) {}

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
            $isList = $property['list'] ?? false;
            if ($isList === true) {
                $temp .= '    ' . '/**' . PHP_EOL;
                $temp .= '    ' . ' * ' . '@var' . ' ' . 'list<' . $this->utils->getClassName($property['type']) . '>' . PHP_EOL;
                $temp .= '    ' . ' */' . PHP_EOL;
                $temp .= '    ' . 'private ' . 'array' . ' ' . '$' . $property['name'] . ';' . PHP_EOL . PHP_EOL;
            } else {
                $temp .= '    ' . 'private ' . $this->utils->getClassName($property['type']) . ' ' . '$' . $property['name'] . ';' . PHP_EOL . PHP_EOL;
            }
        }

        foreach ($inputData['properties'] as $idx => $property) {
            if ($idx > 0) {
                $temp .= PHP_EOL;
            }

            $isList = $property['list'] ?? false;

            $temp .= $this->getGetter(
                $property['name'],
                $property['type'],
                $isList,
                $this->utils->getClassName($accessExceptionClass),
            );

            $temp .= PHP_EOL;

            $temp .= $this->getSetter(
                $property['name'],
                $property['type'],
                $isList,
            );
        }

        $temp .= $this->utils->getClassFooter();

        return $temp;
    }

    public function getGetter(
        string $propertyName,
        string $propertyType,
        bool $isList,
        string $accessExceptionClassName,
    ): string {
        $temp = '';

        $temp .= '    ' . '/**' . PHP_EOL;
        if ($isList) {
            $temp .= '    ' . ' * ' . '@return' . ' ' . 'list<' . $this->utils->getClassName($propertyType) . '>' . PHP_EOL;
            $temp .= '    ' . ' *' . PHP_EOL;
        }
        $temp .= '    ' . ' * ' . '@throws' . ' ' . $accessExceptionClassName . PHP_EOL;
        $temp .= '    ' . ' */' . PHP_EOL;
        if ($isList) {
            $temp .= '    ' . 'public function get' . ucfirst($propertyName) . '(): ' . 'array' . PHP_EOL;
        } else {
            $temp .= '    ' . 'public function get' . ucfirst($propertyName) . '(): ' . $this->utils->getClassName($propertyType) . PHP_EOL;
        }
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
        bool $isList,
    ): string {
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
