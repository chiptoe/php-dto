<?php

declare(strict_types=1);

namespace Tests\Generator;

use PHPUnit\Framework\TestCase;
use Project\DTOConverter\Utils;
use Project\Generator\DTOConverterGenerator;
use Project\ValueObject\PositiveInt;
use Project\ValueObject\PositiveIntNullable;
use Tests\DTO\CommentDTO\CommentDTO;

final class DTOConverterGeneratorTest extends TestCase
{
    public function test_happy(): void
    {
        $inputData = [
            'dtoName' => 'Topic',
            'properties' => [
                [
                    'name' => 'id',
                    'type' => PositiveInt::class,
                ],
                [
                    'name' => 'parentId',
                    'type' => PositiveIntNullable::class,
                ],
                [
                    'name' => 'comments',
                    'type' => CommentDTO::class,
                    'list' => true,
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
