<?php declare(strict_types=1);

namespace Tests\Generator;

use PHPUnit\Framework\TestCase;
use Project\Generator\DTOGenerator;

final class DTOGeneratorTest extends TestCase
{
    public function test_happy(): void
    {
        $inputData = [];

        $service = new DTOGenerator();
        $generated = $service->generate($inputData);

        self::assertSame(
            file_get_contents(__DIR__ . '/TopicDTO.expected.txt'),
            $generated
        );
    }
}
