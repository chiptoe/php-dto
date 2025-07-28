<?php
declare(strict_types=1);

namespace Project\DTO\TopicDTO;

use Project\DTOConverter;
use Project\DTOConverter\PropertyTypeListException;
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

        $e = new PropertyTypeListException();
        try {
            $id = new PositiveInt($inputData[TopicDTO::ID]);
        } catch (\Throwable $th) {
            $e->add(new DTOConverter\PropertyTypeException(TopicDTO::ID, $th));
        }

        try {
            $parentId = new PositiveIntNullable($inputData[TopicDTO::PARENT_ID]);
        } catch (\Throwable $th) {
            $e->add(throw new DTOConverter\PropertyTypeException(TopicDTO::PARENT_ID, $th));
        }

        if ($e->hasSomeExceptions()) {
            throw $e; 
        }

        return (new TopicDTO())
            ->setId($id)
            ->setParentId($parentId);
    }
}
