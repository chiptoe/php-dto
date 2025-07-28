<?php
declare(strict_types=1);

namespace Project\DTO\TopicDTO;

use Project\Exceptions\AccessToUninitialisedPropertyException;
use Project\ValueObject\PositiveInt;
use Project\ValueObject\PositiveIntNullable;

class TopicDTO
{
    public const ID = 'id';

    public const PARENT_ID = 'parentId';

    private PositiveInt $id;

    private PositiveIntNullable $parentId;

    /**
     * @return list<string>
     */
    public static function getKeys(): array
    {
        return [
            self::ID,
            self::PARENT_ID,
        ];
    }

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
}
