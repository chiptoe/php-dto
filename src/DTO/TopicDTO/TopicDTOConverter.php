<?php
declare(strict_types=1);

namespace Project\DTO\TopicDTO;

use Project\DTO\DTOConverterException;

class TopicDTOConverter
{
    public function convert(mixed $inputData): TopicDTO
    {
        if (!is_array($inputData)) {
            throw new DTOConverterException('The (inputData) must be array.');
        }

        if (!array_key_exists('id', $inputData)) {
            throw new DTOConverterException('The array-key (id) must exist.');
        }
        if (!array_key_exists('parentId', $inputData)) {
            throw new DTOConverterException('The array-key (parentId) must exist.');
        }

        if (!is_int($inputData['id'])) {
            throw new DTOConverterException('The (id) must be (int).');
        }
        if ($inputData['parentId'] !== null) {
            if (!is_int($inputData['parentId'])) {
                throw new DTOConverterException('The (parentId) must be (int|null).');
            }
        }

        return (new TopicDTO())
            ->setId($inputData['id'])
            ->setParentId($inputData['parentId']);
    }
}
