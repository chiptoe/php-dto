<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Project\DTO\TopicDTO\TopicDTOConverterFactory;

$cases = [
    [
        'id' => 3,
        'parentId' => 5,
    ],

    [
        'id' => 3,
        'parentId' => null,
    ],
];

foreach ($cases as $caseIdx => $case) {
    $topicDTOConverter = (new TopicDTOConverterFactory)->create();
    $topicDTO = $topicDTOConverter->convert($case);


    if ($topicDTO->getId()->value === $case['id']) {
        echo('#' . $caseIdx . ' Test (Passed) - ' . 'id' . PHP_EOL);
    } else {
        echo('#' . $caseIdx . ' Test (Failed) - ' . 'id' . PHP_EOL);
    }


    if ($topicDTO->getParentId()->value === $case['parentId']) {
        echo('#' . $caseIdx . ' Test (Passed) - ' . 'parentId' . PHP_EOL);
    } else {
        echo('#' . $caseIdx . ' Test (Failed) - ' . 'parentId' . PHP_EOL);
    }
}
