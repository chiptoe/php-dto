<?php

declare(strict_types=1);

namespace Tests\Generator;

use PHPUnit\Framework\TestCase;
use Project\DTOConverter\Utils;
use Project\Generator\DTOConverterGenerator;
use Project\ValueObject\PositiveInt;
use Tests\DTO\CommentDTO\CommentDTO;

final class DTOConverterGeneratorTest extends TestCase
{
    const MAX_MYSQL_INT = 'PositiveInt::MAX_MYSQL_INT';

    public function test_happy(): void
    {
        $inputData = [
            'dtoName' => 'Topic',
            'properties' => [
                [
                    'name' => 'id',
                    'type' => PositiveInt::class,
                    'restParams' => [self::MAX_MYSQL_INT],
                ],
                [
                    'name' => 'parentId',
                    'type' => PositiveInt::class,
                    'nullable' => true,
                    'restParams' => [self::MAX_MYSQL_INT],
                ],
                [
                    'name' => 'comments',
                    'type' => CommentDTO::class,
                    'converterConvert' => true,
                    'list' => true,
                ],
                [
                    'name' => 'commentRoot',
                    'type' => CommentDTO::class,
                    'converterConvert' => true,
                ],
            ],
        ];

        $service = new DTOConverterGenerator(new Utils());
        $generated = $service->generate($inputData);

        self::assertSame(
            file_get_contents(__DIR__ . '/TopicDTOConverter.expected.txt'),
            $generated
        );
    }
}
