<?php declare(strict_types=1);

namespace Tests\Generator;

use PHPUnit\Framework\TestCase;
use Project\DTOConverter\Utils;
use Project\Generator\DTOAssocGenerator;

final class DTOAssocGeneratorTest extends TestCase
{
    public function test_happy(): void
    {
        $inputData = [
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
}
