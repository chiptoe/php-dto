<?php

declare(strict_types=1);

namespace Tests\DTO\CommentDTO;

use Project\DTOConverter\AggregateException;
use Project\DTOConverter\PropertyTypeException;
use Project\DTOConverter\Utils;
use Project\ValueObject\PositiveInt;
use Tests\DTO\IConverter;

/**
 * @implements IConverter<CommentDTO>
 */
final class CommentDTOConverter implements IConverter
{
    public function __construct(
        private Utils $utils,
    ) {}

    /**
     * @inheritdoc
     */
    public function convert(mixed $inputData): CommentDTO
    {
        $this->utils->checkInputData(CommentDTOAssoc::getKeys(), $inputData);

        $e = new AggregateException(__CLASS__);
        try {
            $id = new PositiveInt($inputData[CommentDTOAssoc::ID]);
        } catch (\Throwable $th) {
            $e->add(new PropertyTypeException(CommentDTOAssoc::ID, $th));
        }

        $parentId = null;
        if ($inputData[CommentDTOAssoc::PARENT_ID] !== null) {
            try {
                $parentId = new PositiveInt($inputData[CommentDTOAssoc::PARENT_ID]);
            } catch (\Throwable $th) {
                $e->add(new PropertyTypeException(CommentDTOAssoc::PARENT_ID, $th));
            }
        }

        if ($e->hasSomeExceptions()) {
            throw $e;
        }

        return (new CommentDTO())
            ->setId($id)
            ->setParentId($parentId);
    }
}
