<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Project\DTO\TopicDTO\TopicDTOConverter;

$cases = [
    'The array-key (parentId) must exist.' => [
        'id' => 3,
    ],

    'The (id) must be (int).' => [
        'id' => '3',
        'parentId' => '5',
    ],

    'The (parentId) must be (int|null).' => [
        'id' => 3,
        'parentId' => '5',
    ],

    'The array-key (id) must exist.' => [

    ],
];

foreach ($cases as $expectedMsg => $case) {
    $topicDTOConverter = new TopicDTOConverter();

    try {
        $topicDTO = $topicDTOConverter->convert($case);
        throw new \Exception('Test (Failed)');
    }
    catch(\Throwable $e) {
        if ($e->getMessage() === $expectedMsg) {
            echo('Test (Passed) - ' . $e->getMessage() . PHP_EOL);
        }
        else {
            throw new \Exception('Test (Failed) - expected(' . $expectedMsg . '); actual(' . $e->getMessage() . ')');
        }
    }
}
