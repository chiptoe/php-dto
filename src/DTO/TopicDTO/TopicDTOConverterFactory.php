<?php
declare(strict_types=1);

namespace Project\DTO\TopicDTO;

use Project\DTOConverter\Utils;

class TopicDTOConverterFactory
{
    public function create(): TopicDTOConverter
    {
        return (
            new TopicDTOConverter(
                new Utils(),
            )
        );
    }
}
