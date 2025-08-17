<?php

declare(strict_types=1);

namespace Tests\DTO\CommentDTO;

use Project\Exceptions\AccessToUninitialisedPropertyException;

final class CommentDTOList
{
    /**
     * @var list<CommentDTO>
     */
    private array $list;

    /**
     * @return list<CommentDTO>
     */
    public function getList(): array
    {
        if (!isset($this->list)) {
            throw new AccessToUninitialisedPropertyException();
        }

        return $this->list;
    }

    /**
     * @param list<CommentDTO> $list
     */
    public function setList(array $list): self
    {
        $this->list = $list;

        return $this;
    }
}
