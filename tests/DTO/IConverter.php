<?php

declare(strict_types=1);

namespace Tests\DTO;

use Project\DTOConverter\AggregateException;
use Project\DTOConverter\MissingKeysException;

/**
 * @template T
 */
interface IConverter
{
    /**
     * @return T
     *
     * @throws MissingKeysException
     * @throws AggregateException
     */
    public function convert(mixed $inputData): mixed;
}
