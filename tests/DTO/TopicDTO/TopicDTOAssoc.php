<?php
declare(strict_types=1);

namespace Tests\DTO\TopicDTO;

final class TopicDTOAssoc
{
    public const ID = 'id';

    public const PARENT_ID = 'parentId';

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
}
