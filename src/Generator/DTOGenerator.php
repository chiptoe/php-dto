<?php
declare(strict_types=1);

namespace Project\Generator;

use Project\DTOConverter\Utils;

class DTOGenerator
{
    public function __construct(
        private Utils $utils,
    )
    {
    }

    public function generate(array $inputData): string
    {
        $namespace = 'Tests\DTO\TopicDTO';
        $useClasses = [
            'Project\Exceptions\AccessToUninitialisedPropertyException',
            'Project\ValueObject\PositiveInt',
            'Project\ValueObject\PositiveIntNullable',
        ];
        $className = 'TopicDTO';

        $temp = '';

        $temp .= $this->getClassHeader(
            $namespace,
            $useClasses,
            $className,
        );

        foreach ($inputData['properties'] as $property) {
            $temp .= '    ' . 'public const ' . $this->utils->toScreamingSnakeCase($property['name']) . ' = \'' . $property['name'] . '\';' . PHP_EOL . PHP_EOL;
        }

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
}
