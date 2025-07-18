<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Project\DTO\TopicDTO\TopicDTOConverter;

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

foreach ($cases as $case) {
    $topicDTOConverter = new TopicDTOConverter();
    $topicDTO = $topicDTOConverter->convert($case);

    var_dump($topicDTO);
}
