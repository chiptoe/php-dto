<?php declare(strict_types=1);

namespace Tests\Generator;

use PHPUnit\Framework\TestCase;
use Project\DTOConverter\Utils;
use Project\Generator\DTOGenerator;

final class DTOGeneratorTest extends TestCase
{
    public function test_happy(): void
    {
        $inputData = [
            'properties' => [
                [
                    'name' => 'id'
                ],
                [
                    'name' => 'parentId'
                ],
            ],
        ];

        $service = new DTOGenerator(new Utils());
        $generated = $service->generate($inputData);

        self::assertSame(
            file_get_contents(__DIR__ . '/TopicDTO.expected.txt'),
            $generated
        );
    }
}
