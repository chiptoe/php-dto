<?php
declare(strict_types=1);

namespace Project\DTO\TopicDTO;

use Project\DTOConverter;
use Project\ValueObject\PositiveInt;
use Project\ValueObject\PositiveIntNullable;

class TopicDTOConverter
{
    public function __construct(
        private DTOConverter\Utils $utils
    ) {
    }

    public function convert(mixed $inputData): TopicDTO
    {
        $this->utils->checkInputData(TopicDTO::getKeys(), $inputData);

        try {
            $id = new PositiveInt($inputData[TopicDTO::KEY_ID]);
        } catch (\Throwable $th) {
            throw new DTOConverter\PropertyTypeException(TopicDTO::KEY_ID, $th);
        }

        try {
            $parentId = new PositiveIntNullable($inputData[TopicDTO::KEY_PARENT_ID]);
        } catch (\Throwable $th) {
            throw new DTOConverter\PropertyTypeException(TopicDTO::KEY_PARENT_ID, $th);
        }

        return (new TopicDTO())
            ->setId($id)
            ->setParentId($parentId);
    }
}
