<?php

declare(strict_types=1);

namespace Tests\DTO;

interface IConverter
{
    public function convert(mixed $inputData): mixed;
}
