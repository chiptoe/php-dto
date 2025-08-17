<?php

declare(strict_types=1);

namespace Tests\DTO\TopicDTO;

use Project\Exceptions\AccessToUninitialisedPropertyException;
use Project\ValueObject\PositiveInt;
use Project\ValueObject\PositiveIntNullable;
use Tests\DTO\CommentDTO\CommentDTOList;

final class TopicDTO
{
    private PositiveInt $id;

    private PositiveIntNullable $parentId;

    private CommentDTOList $commentDTOList;

    public function getId(): PositiveInt
    {
        if (!isset($this->id)) {
            throw new AccessToUninitialisedPropertyException();
        }

        return $this->id;
    }

    public function setId(PositiveInt $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getParentId(): PositiveIntNullable
    {
        if (!isset($this->parentId)) {
            throw new AccessToUninitialisedPropertyException();
        }

        return $this->parentId;
    }

    public function setParentId(PositiveIntNullable $parentId): self
    {
        $this->parentId = $parentId;

        return $this;
    }

    public function getCommentDTOList(): CommentDTOList
    {
        if (!isset($this->commentDTOList)) {
            throw new AccessToUninitialisedPropertyException();
        }

        return $this->commentDTOList;
    }

    public function setCommentDTOList(CommentDTOList $commentDTOList): self
    {
        $this->commentDTOList = $commentDTOList;

        return $this;
    }
}
