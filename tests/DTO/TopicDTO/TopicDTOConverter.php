<?php

declare(strict_types=1);

namespace Tests\DTO\TopicDTO;

use Project\DTOConverter\PropertyTypeException;
use Project\DTOConverter\PropertyTypeListException;
use Project\DTOConverter\Utils;
use Project\ValueObject\PositiveInt;
use Project\ValueObject\PositiveIntNullable;
use Tests\DTO\CommentDTO\CommentDTOConverter;

final class TopicDTOConverter
{
    public function __construct(
        private Utils $utils,
        private CommentDTOConverter $commentDTOConverter,
    ) {}

    public function convert(mixed $inputData): TopicDTO
    {
        $this->utils->checkInputData(TopicDTOAssoc::getKeys(), $inputData);

        $e = new PropertyTypeListException(__CLASS__);
        try {
            $id = new PositiveInt($inputData[TopicDTOAssoc::ID]);
        } catch (\Throwable $th) {
            $e->add(new PropertyTypeException(TopicDTOAssoc::ID, $th));
        }

        try {
            $parentId = new PositiveIntNullable($inputData[TopicDTOAssoc::PARENT_ID]);
        } catch (\Throwable $th) {
            $e->add(new PropertyTypeException(TopicDTOAssoc::PARENT_ID, $th));
        }

        $comments = [];
        $commentsRaw = $inputData[TopicDTOAssoc::COMMENTS];
        $commentsExc = new PropertyTypeListException(CommentDTOConverter::class);
        foreach ($commentsRaw as $index => $item) {
            try {
                $comments[] = $this->commentDTOConverter->convert($item);
            } catch (\Throwable $th) {
                $commentsExc->add(new PropertyTypeException(TopicDTOAssoc::COMMENTS . '.' . $index, $th));
            }
        }
        if ($commentsExc->hasSomeExceptions()) {
            $e->add(new PropertyTypeException(TopicDTOAssoc::COMMENTS, $th));
        }

        if ($e->hasSomeExceptions()) {
            throw $e;
        }

        return (new TopicDTO())
            ->setId($id)
            ->setParentId($parentId);
    }
}
