<?php

declare(strict_types=1);

namespace Tests\DTO\CommentDTO;

use Project\DTOConverter\PropertyTypeException;
use Project\DTOConverter\PropertyTypeListException;
use Project\DTOConverter\Utils;
use Project\ValueObject\PositiveInt;
use Project\ValueObject\PositiveIntNullable;

final class CommentDTOConverter
{
    public function __construct(
        private Utils $utils
    ) {}

    public function convert(mixed $inputData): CommentDTO
    {
        $this->utils->checkInputData(CommentDTOAssoc::getKeys(), $inputData);

        $e = new PropertyTypeListException(__CLASS__);
        try {
            $id = new PositiveInt($inputData[CommentDTOAssoc::ID]);
        } catch (\Throwable $th) {
            $e->add(new PropertyTypeException(CommentDTOAssoc::ID, $th));
        }

        try {
            $parentId = new PositiveIntNullable($inputData[CommentDTOAssoc::PARENT_ID]);
        } catch (\Throwable $th) {
            $e->add(new PropertyTypeException(CommentDTOAssoc::PARENT_ID, $th));
        }

        if ($e->hasSomeExceptions()) {
            throw $e;
        }

        return (new CommentDTO())
            ->setId($id)
            ->setParentId($parentId);
    }
}
