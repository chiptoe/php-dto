<?php

declare(strict_types=1);

namespace Tests\DTO;

/**
 * @template T
 */
interface IConverter
{
    /**
     * @return T
     */
    public function convert(mixed $inputData): mixed;
}
