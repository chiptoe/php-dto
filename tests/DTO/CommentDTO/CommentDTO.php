<?php

declare(strict_types=1);

namespace Tests\DTO\CommentDTO;

use Project\Exceptions\AccessToUninitialisedPropertyException;
use Project\ValueObject\PositiveInt;

final class CommentDTO
{
    private PositiveInt $id;

    private PositiveInt|null $parentId;

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
    public function getParentId(): PositiveInt|null
    {
        if ($this->parentId === null) {
            return null;
        }

        if (!isset($this->parentId)) {
            throw new AccessToUninitialisedPropertyException();
        }

        return $this->parentId;
    }

    public function setParentId(PositiveInt|null $parentId): self
    {
        $this->parentId = $parentId;

        return $this;
    }
}
