<?php

declare(strict_types=1);

namespace Tests\DTO\TopicDTO;

use Project\DTOConverter\PropertyTypeException;
use Project\DTOConverter\AggregateException;
use Project\DTOConverter\Utils;
use Project\ValueObject\PositiveInt;
use Project\ValueObject\PositiveIntNullable;
use Tests\DTO\CommentDTO\CommentDTOConverter;
use Tests\DTO\IConverter;

final class TopicDTOConverter implements IConverter
{
    public function __construct(
        private Utils $utils,
        private CommentDTOConverter $commentDTOConverter,
    ) {}

    public function convert(mixed $inputData): TopicDTO
    {
        $this->utils->checkInputData(TopicDTOAssoc::getKeys(), $inputData);

        $e = new AggregateException(__CLASS__);
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
        $commentsValue = $inputData[TopicDTOAssoc::COMMENTS];
        $commentsException = new AggregateException(CommentDTOConverter::class);
        foreach ($commentsValue as $commentsValueIndex => $commentsValueItem) {
            try {
                $comments[] = $this->commentDTOConverter->convert($commentsValueItem);
            } catch (\Throwable $th) {
                $commentsException->add(new PropertyTypeException(TopicDTOAssoc::COMMENTS . '.' . $commentsValueIndex, $th));
            }
        }
        if ($commentsException->hasSomeExceptions()) {
            $e->add(new PropertyTypeException(TopicDTOAssoc::COMMENTS, $th));
        }

        if ($e->hasSomeExceptions()) {
            throw $e;
        }

        return (new TopicDTO())
            ->setId($id)
            ->setParentId($parentId);
    }

    private function convert_list(mixed $inputData, string $assocKey, IConverter $converter): array
    {
        $temp = [];
        $e = new AggregateException(CommentDTOConverter::class);

        $items = $inputData[$assocKey];
        foreach ($items as $index => $item) {
            try {
                $temp[] = $converter->convert($item);
            } catch (\Throwable $th) {
                $e->add(new PropertyTypeException($assocKey . '.' . $index, $th));
            }
        }

        if ($e->hasSomeExceptions()) {
            throw $e;
        }

        return $temp;
    }
}
