<?php

declare(strict_types=1);

namespace Project\Generator;

use Project\DTOConverter\Utils;

final class DTOConverterGenerator
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

        $implementsClassFqcn = 'Tests\DTO\IConverter';
        $implementsClassName = $this->utils->getClassName($implementsClassFqcn);
        $properties = $inputData['properties'];
        $useClasses = [
            'Project\DTOConverter\AggregateException',
            'Project\DTOConverter\PropertyTypeException',
            'Project\DTOConverter\Utils',
            $implementsClassFqcn,
            ...array_unique(array_map(fn($it) => $it['type'], $properties)),
        ];
        // sort lines asc
        sort($useClasses);

        $classNameDTO = $inputData['dtoName'] . 'DTO';
        $classNameDTOAssoc = $inputData['dtoName'] . 'DTO' . 'Assoc';
        $className = $classNameDTO . 'Converter';

        $temp = '';

        $temp .= $this->utils->getClassHeader(
            $namespace,
            $useClasses,
            $className,
            $implementsClassName,
            [$classNameDTO],
        );

        $temp .= $this->getConstructor([
            'Utils'
        ]);
        $temp .= PHP_EOL;
        $temp .= $this->getConvert(
            $classNameDTO,
            $classNameDTOAssoc,
            $properties,
        );
        $temp .= $this->utils->getClassFooter();

        return $temp;
    }

    private function getConstructor(array $deps): string
    {
        $temp = '';

        $temp .= '    ' . 'public function __construct(' . PHP_EOL;
        foreach ($deps as $dep) {
            $temp .= '    ' . '    ' . 'private ' . $dep . ' $' . lcfirst($dep) . PHP_EOL;
        }
        $temp .= '    ' . ') {}' . PHP_EOL;

        return $temp;
    }

    private function getConvert(
        string $classNameDTO,
        string $classNameDTOAssoc,
        array $props,
    ): string {
        $inputVarName = '$inputData';

        $temp = '';

        $temp .= '    ' . 'public function convert(mixed ' . $inputVarName . '): ' . $classNameDTO . PHP_EOL;
        $temp .= '    ' . '{' . PHP_EOL;
        $temp .= '    ' . '    ' . '$this->utils->checkInputData(' . $classNameDTOAssoc . '::getKeys()' . ', ' . $inputVarName . ');' . PHP_EOL;
        $temp .= PHP_EOL;
        $temp .= '    ' . '    ' . '$e = new AggregateException();' . PHP_EOL;

        foreach ($props as $prop) {
            $temp .= '    ' . '    ' . 'try {' . PHP_EOL;
            $temp .= '    ' . '    ' . '    ' . '$' . $prop['name'] . ' = new ' . $this->utils->getClassName($prop['type']) . '(' . $inputVarName . '[' . $classNameDTOAssoc . '::' . $this->utils->toScreamingSnakeCase($prop['name']) . ']' . ');' . PHP_EOL;
            $temp .= '    ' . '    ' . '} catch (\Throwable $th) {' . PHP_EOL;
            $temp .= '    ' . '    ' . '    ' . '$e->add(new PropertyTypeException(' . $classNameDTOAssoc . '::' . $this->utils->toScreamingSnakeCase($prop['name']) . ', $th));' . PHP_EOL;
            $temp .= '    ' . '    ' . '}' . PHP_EOL;
            $temp .= PHP_EOL;
        }

        $temp .= '    ' . '    ' . 'if ($e->hasSomeExceptions()) {' . PHP_EOL;
        $temp .= '    ' . '    ' . '    ' . 'throw $e;' . PHP_EOL;
        $temp .= '    ' . '    ' . '}' . PHP_EOL;
        $temp .= PHP_EOL;
        $temp .= '    ' . '    ' . 'return (new ' . $classNameDTO . '())' . PHP_EOL;

        foreach ($props as $idx => $prop) {
            $temp .= '    ' . '    ' . '    ' . '->set' . ucfirst($prop['name']) . '(' . '$' . $prop['name'] . ')';
            if ($idx < count($props) - 1) {
                $temp .= PHP_EOL;
            } else {
                $temp .= ';' . PHP_EOL;
            }
        }

        $temp .= '    ' . '}' . PHP_EOL;

        return $temp;
    }
}
