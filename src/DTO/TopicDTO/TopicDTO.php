<?php
declare(strict_types=1);

namespace Project\DTO\TopicDTO;

class TopicDTO
{
    private int $id;

    private ?int $parentId;

    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of parentId
     */
    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    /**
     * Set the value of parentId
     */
    public function setParentId(?int $parentId): self
    {
        $this->parentId = $parentId;

        return $this;
    }
}
