<?php
declare(strict_types=1);

namespace Project\Generator;

use Project\DTOConverter\Utils;

class DTOAssocGenerator
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

        $useClasses = [];

        $dtoName = 'Topic';
        $className = $dtoName . 'DTO' . 'Assoc';
        
        $temp = '';

        $temp .= $this->utils->getClassHeader(
            $namespace,
            $useClasses,
            $className,
        );

        $temp .= $this->getConstants($inputData['fromKeys']);
        $temp .= $this->getKeysFunc($inputData['fromKeys']);

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
