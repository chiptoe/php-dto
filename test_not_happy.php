<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Project\DTO\TopicDTO\TopicDTOConverterFactory;

$cases = [
    'The array-keys (parentId) must exist.' => [
        'input' => [
            'id' => 3,
        ],
        'expectedPreviousMessage' => null,
    ],

    'Invalid type of property (id).' => [
        'input' => [
            'id' => '3',
            'parentId' => '5',
        ],
        'expectedPreviousMessage' => 'The (value) must be valid (Project\ValueObject\PositiveInt).',
    ],

    'Invalid type of property (parentId).' => [
        'input' => [
            'id' => 3,
            'parentId' => '5',
        ],
        'expectedPreviousMessage' => 'The (value) must be valid (Project\ValueObject\PositiveIntNullable).',
    ],

    'The array-keys (id, parentId) must exist.' => [
        'input' => [],
        'expectedPreviousMessage' => null,
    ],
];

foreach ($cases as $expectedMessage => $case) {
    $topicDTOConverter = (new TopicDTOConverterFactory())->create();

    try {
        $topicDTO = $topicDTOConverter->convert($case['input']);
        throw new \Exception('Test (Failed)');
    }
    catch(\Throwable $e) {
        $actualPreviousMessage = $e->getPrevious()?->getMessage();
        $expectedPreviousMessage = $case['expectedPreviousMessage'];
        if ($actualPreviousMessage !== $case['expectedPreviousMessage']) {
            throw new \Exception('Test (Failed) - expected(' . $expectedPreviousMessage . '); actual(' . $actualPreviousMessage . ')');
        }

        if ($e->getMessage() === $expectedMessage) {
            echo('Test (Passed) - ' . $e->getMessage() . PHP_EOL);
        }
        else {
            throw new \Exception('Test (Failed) - expected(' . $expectedMessage . '); actual(' . $e->getMessage() . ')');
        }
    }
}
