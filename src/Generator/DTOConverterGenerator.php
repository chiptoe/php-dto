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

        $concreteConverterClassNames = [];

        $properties = $inputData['properties'];
        $useClasses = [
            'Project\DTOConverter\AggregateException',
            'Project\DTOConverter\PropertyTypeException',
            'Project\DTOConverter\Utils',
            $implementsClassFqcn,
            ...array_map(function($property) use (&$concreteConverterClassNames) {
                $converterConvert = $property['converterConvert'] ?? null;
                if ($converterConvert) {
                    $concreteConverterClassFqcn = $property['type'] . 'Converter';
                    $concreteConverterClassNames[$property['type']] = $this->utils->getClassName($concreteConverterClassFqcn);

                    return $concreteConverterClassFqcn;
                }

                return $property['type'];
            }, $properties),
        ];

        $useClasses = array_unique($useClasses);
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
            'Utils',
            ...array_values($concreteConverterClassNames),
        ]);
        $temp .= PHP_EOL;
        $temp .= $this->getConvert(
            $classNameDTO,
            $classNameDTOAssoc,
            $properties,
            $concreteConverterClassNames,
        );
        $temp .= $this->utils->getClassFooter();

        return $temp;
    }

    private function getConstructor(array $deps): string
    {
        sort($deps);

        $temp = '';

        $temp .= '    ' . 'public function __construct(' . PHP_EOL;
        foreach ($deps as $dep) {
            $temp .= '    ' . '    ' . 'private ' . $dep . ' $' . lcfirst($dep) . ',' . PHP_EOL;
        }
        $temp .= '    ' . ') {}' . PHP_EOL;

        return $temp;
    }

    private function getConvert(
        string $classNameDTO,
        string $classNameDTOAssoc,
        array $properties,
        array $concreteConverterClassNames,
    ): string {
        $inputVarName = '$inputData';

        $temp = '';

        $temp .= '    ' . '/**' . PHP_EOL;
        $temp .= '    ' . ' * ' . '@inheritdoc' . PHP_EOL;
        $temp .= '    ' . ' */' . PHP_EOL;
        $temp .= '    ' . 'public function convert(mixed ' . $inputVarName . '): ' . $classNameDTO . PHP_EOL;
        $temp .= '    ' . '{' . PHP_EOL;
        $temp .= '    ' . '    ' . '$this->utils->checkInputData(' . $classNameDTOAssoc . '::getKeys()' . ', ' . $inputVarName . ');' . PHP_EOL;
        $temp .= PHP_EOL;
        $temp .= '    ' . '    ' . '$e = new AggregateException(' . '__CLASS__' . ');' . PHP_EOL;

        foreach ($properties as $property) {
            $converterConvert = $property['converterConvert'] ?? null;
            $isList = $property['list'] ?? null;
            $isNullable = $property['nullable'] ?? null;

            $tryCatchLines = [];
            $tryCatchLines[] = '    ' . '    ' . 'try {' . PHP_EOL;
            if ($converterConvert === true && $isList === true) {
                $tryCatchLines[] = '    ' . '    ' . '    ' . '$' . $property['name'] . ' = ' . '$this->utils->convertList(' . $inputVarName . ', ' . $classNameDTOAssoc . '::' . $this->utils->toScreamingSnakeCase($property['name']) . ', ' . '$this->' . lcfirst($concreteConverterClassNames[$property['type']]) . ');' . PHP_EOL;
            }
            else if ($converterConvert === true) {
                $tryCatchLines[] = '    ' . '    ' . '    ' . '$' . $property['name'] . ' = ' . '$this->' . lcfirst($concreteConverterClassNames[$property['type']]) . '->' . 'convert' . '(' . $inputVarName . '[' . $classNameDTOAssoc . '::' . $this->utils->toScreamingSnakeCase($property['name']) . ']' . ');' . PHP_EOL;
            }
            else {
                $tryCatchLines[] = '    ' . '    ' . '    ' . '$' . $property['name'] . ' = new ' . $this->utils->getClassName($property['type']) . '(' . $inputVarName . '[' . $classNameDTOAssoc . '::' . $this->utils->toScreamingSnakeCase($property['name']) . ']' . ');' . PHP_EOL;
            }
            $tryCatchLines[] = '    ' . '    ' . '} catch (\Throwable $th) {' . PHP_EOL;
            $tryCatchLines[] = '    ' . '    ' . '    ' . '$e->add(new PropertyTypeException(' . $classNameDTOAssoc . '::' . $this->utils->toScreamingSnakeCase($property['name']) . ', $th));' . PHP_EOL;
            $tryCatchLines[] = '    ' . '    ' . '}' . PHP_EOL;
            $tryCatchLines[] = PHP_EOL;

            foreach ($tryCatchLines as $tryCatchLine) {
                $temp .= $tryCatchLine;
            }
        }

        $temp .= '    ' . '    ' . 'if ($e->hasSomeExceptions()) {' . PHP_EOL;
        $temp .= '    ' . '    ' . '    ' . 'throw $e;' . PHP_EOL;
        $temp .= '    ' . '    ' . '}' . PHP_EOL;
        $temp .= PHP_EOL;
        $temp .= '    ' . '    ' . 'return (new ' . $classNameDTO . '())' . PHP_EOL;

        foreach ($properties as $idx => $property) {
            $temp .= '    ' . '    ' . '    ' . '->set' . ucfirst($property['name']) . '(' . '$' . $property['name'] . ')';
            if ($idx < count($properties) - 1) {
                $temp .= PHP_EOL;
            } else {
                $temp .= ';' . PHP_EOL;
            }
        }

        $temp .= '    ' . '}' . PHP_EOL;

        return $temp;
    }
}
