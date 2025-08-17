<?php

declare(strict_types=1);

namespace Tests\DTO\TopicDTO;

use Project\DTOConverter\AggregateException;
use Project\DTOConverter\InvalidNestedItemException;
use Project\DTOConverter\PropertyTypeException;
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

        try {
            $parentId = $this->convertList($inputData, TopicDTOAssoc::COMMENTS, $this->commentDTOConverter);
        } catch (\Throwable $th) {
            $e->add(new PropertyTypeException(TopicDTOAssoc::PARENT_ID, $th));
        }

        if ($e->hasSomeExceptions()) {
            throw $e;
        }

        return (new TopicDTO())
            ->setId($id)
            ->setParentId($parentId);
    }

    /**
     * @return list<mixed>
     * @throws AggregateException
     */
    private function convertList(
        mixed $inputData,
        string $assocKey,
        IConverter $converter,
    ): array
    {
        $temp = [];
        $e = new AggregateException($converter::class);

        $items = $inputData[$assocKey];
        $index = 0;
        foreach ($items as $item) {
            try {
                $temp[] = $converter->convert($item);
            } catch (\Throwable $th) {
                $e->add(new InvalidNestedItemException($assocKey, $index, $th));
            }
            $index++;
        }

        if ($e->hasSomeExceptions()) {
            throw $e;
        }

        return $temp;
    }
}
