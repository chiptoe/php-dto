<?php

declare(strict_types=1);

namespace Tests\Generator;

use PHPUnit\Framework\TestCase;
use Project\DTOConverter\Utils;
use Project\Generator\DTOConverterGenerator;

final class DTOConverterGeneratorTest extends TestCase
{
    public function test_happy(): void
    {
        $inputData = [
            // 'dtoName' => 'Topic',
            // 'fromKeys' => [
            //     'id',
            //     'parentId',
            // ],
        ];

        $service = new DTOConverterGenerator(new Utils());
        $generated = $service->generate($inputData);

        self::assertSame(
            file_get_contents(__DIR__ . '/TopicDTOConverter.expected.txt'),
            $generated
        );
    }
}
