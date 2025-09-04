<?php
declare(strict_types=1);

namespace Project;

final class PhpGeneratingUtils
{
    /**
     * @param list<string> $useClasses
     * @param list<string> $implementsClassArgs
     */
    public function getClassHeader(
        string $namespace,
        array $useClasses,
        string $className,
        ?string $implementsClassName,
        array $implementsClassArgs,
    ): string {
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
        if ($implementsClassName) {
            $temp .= '/**' . PHP_EOL;
            $temp .= ' * @implements ' . $implementsClassName . '<' . implode(',', $implementsClassArgs) . '>' . PHP_EOL;
            $temp .= ' */' . PHP_EOL;
            $temp .= 'final class ' . $className . ' implements' . ' ' . $implementsClassName . PHP_EOL;
        } else {
            $temp .= 'final class ' . $className . PHP_EOL;
        }
        $temp .= '{' . PHP_EOL;

        return $temp;
    }

    public function getClassFooter(): string
    {
        return '}' . PHP_EOL;
    }
}
