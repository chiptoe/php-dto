<?php

declare(strict_types=1);

namespace Tests\DTO\TopicDTO;

use Project\DTOConverter\AggregateException;
use Project\DTOConverter\PropertyDataException;
use Project\DTOConverter\Utils;
use Project\ValueObject\PositiveInt;
use Tests\DTO\CommentDTO\CommentDTOConverter;
use Tests\DTO\IConverter;

/**
 * @implements IConverter<TopicDTO>
 */
final class TopicDTOConverter implements IConverter
{
    public function __construct(
        private CommentDTOConverter $commentDTOConverter,
        private Utils $utils,
    ) {}

    /**
     * @inheritdoc
     */
    public function convert(mixed $inputData): TopicDTO
    {
        $this->utils->checkInputData(TopicDTOAssoc::getKeys(), $inputData);

        $e = new AggregateException(__CLASS__);
        try {
            $id = new PositiveInt($inputData[TopicDTOAssoc::ID], PositiveInt::MAX_MYSQL_INT);
        } catch (\Throwable $th) {
            $e->add(new PropertyDataException(TopicDTOAssoc::ID, $th));
        }

        $parentId = null;
        if ($inputData[TopicDTOAssoc::PARENT_ID] !== null) {
            try {
                $parentId = new PositiveInt($inputData[TopicDTOAssoc::PARENT_ID], 2147483647);
            } catch (\Throwable $th) {
                $e->add(new PropertyDataException(TopicDTOAssoc::PARENT_ID, $th));
            }
        }

        try {
            $comments = $this->utils->convertList($inputData, TopicDTOAssoc::COMMENTS, $this->commentDTOConverter, __CLASS__);
        } catch (\Throwable $th) {
            $e->add(new PropertyDataException(TopicDTOAssoc::COMMENTS, $th));
        }

        try {
            $commentRoot = $this->commentDTOConverter->convert($inputData[TopicDTOAssoc::COMMENT_ROOT]);
        } catch (\Throwable $th) {
            $e->add(new PropertyDataException(TopicDTOAssoc::COMMENT_ROOT, $th));
        }

        if ($e->hasSomeExceptions()) {
            throw $e;
        }

        return (new TopicDTO())
            ->setId($id)
            ->setParentId($parentId)
            ->setComments($comments)
            ->setCommentRoot($commentRoot);
    }
}
