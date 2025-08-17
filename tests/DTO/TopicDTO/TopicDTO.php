<?php

declare(strict_types=1);

namespace Tests\DTO\TopicDTO;

use Project\Exceptions\AccessToUninitialisedPropertyException;
use Project\ValueObject\PositiveInt;
use Project\ValueObject\PositiveIntNullable;
use Tests\DTO\CommentDTO\CommentDTO;

final class TopicDTO
{
    private PositiveInt $id;

    private PositiveIntNullable $parentId;

    /**
     * @var list<CommentDTO>
     */
    private array $commentDTOs;

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

    /**
     * @return list<CommentDTO>
     */
    public function getCommentDTOs(): array
    {
        return $this->commentDTOs;
    }

    /**
     * @param list<CommentDTO>
     */
    public function setCommentDTOs(array $commentDTOs): self
    {
        $this->commentDTOs = $commentDTOs;

        return $this;
    }
}
