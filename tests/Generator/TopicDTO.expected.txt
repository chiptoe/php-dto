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
    private array $comments;

    private CommentDTO $commentRoot;

    /**
     * @throws AccessToUninitialisedPropertyException
     */
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

    /**
     * @throws AccessToUninitialisedPropertyException
     */
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
     *
     * @throws AccessToUninitialisedPropertyException
     */
    public function getComments(): array
    {
        if (!isset($this->comments)) {
            throw new AccessToUninitialisedPropertyException();
        }

        return $this->comments;
    }

    /**
     * @param list<CommentDTO> $comments
     */
    public function setComments(array $comments): self
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * @throws AccessToUninitialisedPropertyException 
     */
    public function getCommentRoot(): CommentDTO
    {
        if (!isset($this->commentRoot)) {
            throw new AccessToUninitialisedPropertyException();
        }

        return $this->commentRoot;
    }

    public function setCommentRoot(CommentDTO $commentRoot): self
    {
        $this->commentRoot = $commentRoot;

        return $this;
    }
}
