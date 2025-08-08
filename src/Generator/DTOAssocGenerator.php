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

        return $temp;
    }
}
