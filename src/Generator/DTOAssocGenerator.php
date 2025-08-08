<?php
declare(strict_types=1);

namespace Project\Generator;

class DTOAssocGenerator
{
    /**
     * @param array<string,mixed> $inputData
     */
    public function generate(array $inputData): string
    {
        $temp = '';

        $dtoName = 'Topic';
        $className = $dtoName . 'DTO' . 'Assoc';

        $temp .= $this->getClassHeader(
            $namespace,
            $useClasses,
            $className,
        );

        return $temp;
    }
}
