<?php

declare(strict_types=1);

namespace Project\Generator;

use Project\DTOConverter\Utils;

final class DTOConverterGenerator
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

        $useClasses = [
            'Project\DTOConverter\PropertyTypeException',
            'Project\DTOConverter\PropertyTypeListException',
            'Project\DTOConverter\Utils',
            ...array_unique(array_map(fn($it) => $it['type'], $inputData['properties'])),
        ];

        $classNameDTO = $inputData['dtoName'] . 'DTO';
        $classNameDTOAssoc = $inputData['dtoName'] . 'DTO' . 'Assoc';
        $className = $classNameDTO . 'Converter';

        $temp = '';

        $temp .= $this->utils->getClassHeader(
            $namespace,
            $useClasses,
            $className,
        );

        $temp .= $this->getConstructor([
            'Utils'
        ]);
        $temp .= PHP_EOL;
        $temp .= $this->getConvert(
            $classNameDTO,
            $classNameDTOAssoc,
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
    ): string
    {
        $temp = '';

        $temp .= '    ' . 'public function convert(mixed $inputData): ' . $classNameDTO . PHP_EOL;
        $temp .= '    ' . '{' . PHP_EOL;
        $temp .= '    ' . '    ' . '$this->utils->checkInputData(' . $classNameDTOAssoc . '::getKeys()' . ', ' . '$inputData' . ');' . PHP_EOL;
        $temp .= PHP_EOL;
        $temp .= '    ' . '}' . PHP_EOL;

        return $temp;
    }
}
