<?php
declare(strict_types=1);

namespace Project\Generator\DTOGenerator;

use Project\DTOConverter\Utils;

class DTOGenerator
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
        $className = 'TopicDTO';

        $temp = '';

        $temp .= $this->getClassHeader(
            $namespace,
            $useClasses,
            $className,
        );

        foreach ($inputData['properties'] as $property) {
            // TODO: what if $property['name'] is not camelCase (?)
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

        $temp .= $this->getClassFooter();

        return $temp;
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
        $temp .= 'declare(strict_types=1);' . PHP_EOL;
        $temp .= PHP_EOL;
        $temp .= 'namespace ' . $namespace . ';' . PHP_EOL;
        $temp .= PHP_EOL;
        $temp .= implode(PHP_EOL, array_map(fn($item) => ('use ' . $item . ';'), $useClasses)) . PHP_EOL;
        $temp .= PHP_EOL;
        $temp .= 'final class ' . $className . PHP_EOL;
        $temp .= '{' . PHP_EOL;

        return $temp;
    }

    public function getClassFooter(): string
    {
        return '}' . PHP_EOL;
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
