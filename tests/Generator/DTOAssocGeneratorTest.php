<?php

declare(strict_types=1);

namespace Tests\Generator;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Project\DTOConverter\Utils;
use Project\Generator\DTOAssocGenerator;

final class DTOAssocGeneratorTest extends TestCase
{
    public function test_happy(): void
    {
        $inputData = [
            'dtoName' => 'Topic',
            'fromKeys' => [
                'id',
                'parentId',
            ],
        ];

        $service = new DTOAssocGenerator(new Utils());
        $generated = $service->generate($inputData);

        self::assertSame(
            file_get_contents(__DIR__ . '/TopicDTOAssoc.expected.txt'),
            $generated
        );
    }

    public static function provider_it_must_throw_if_dtoname_is_not_valid()
    {
        return [
            [
                'inputData' => [],
                'expectedMessage' => 'the (dtoName) must exist as array key',
            ],
            [
                'inputData' => [
                    'dtoName' => 3
                ],
                'expectedMessage' => 'the (dtoName) must be string and filled',
            ],
            [
                'inputData' => [
                    'dtoName' => ''
                ],
                'expectedMessage' => 'the (dtoName) must be string and filled',
            ],
            [
                'inputData' => [
                    'dtoName' => '    '
                ],
                'expectedMessage' => 'the (dtoName) must be string and filled',
            ],
        ];
    }

    #[DataProvider('provider_it_must_throw_if_dtoname_is_not_valid')]
    public function test_it_must_throw_if_dtoname_is_not_valid(
        mixed $inputData,
        mixed $expectedMessage,
    ) {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedMessage);

        $service = new DTOAssocGenerator(new Utils());
        $service->generate($inputData);
    }
}
