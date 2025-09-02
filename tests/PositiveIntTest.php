<?php declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Project\ValueObject\PositiveInt;
use Project\ValueObject\PositiveIntException;

final class PositiveIntTest extends TestCase
{
    /**
     * @return mixed[]
     */
    public static function provider_happy(): array
    {
        return [
            [
                'value' => 1,
                'expectedValue' => 1,
            ],

            [
                'value' => 2,
                'expectedValue' => 2,
            ],

            [
                'value' => 2147483647,
                'expectedValue' => 2147483647,
            ],
        ];
    }

    #[DataProvider('provider_happy')]
    public function test_happy(
        mixed $value,
        mixed $expectedValue,
    ): void
    {
        $positiveInt = new PositiveInt($value, PositiveInt::MAX_MYSQL_INT);
        self::assertSame($expectedValue, $positiveInt->value);
    }

    /**
     * @return mixed[]
     */
    public static function provider_it_must_throw_if_value_is_not_valid(): array
    {
        return [
            [
                'value' => '2',
                'expectedMessage' => 'The (value) must be positive int.'
            ],

            [
                'value' => 0,
                'expectedMessage' => 'The (value) must be positive int.'
            ],

            [
                'value' => -1,
                'expectedMessage' => 'The (value) must be positive int.'
            ],
        ];
    }

    #[DataProvider('provider_it_must_throw_if_value_is_not_valid')]
    public function test_it_must_throw_if_value_is_not_valid(
        mixed $value,
        mixed $expectedMessage,
    ): void
    {
        $this->expectException(PositiveIntException::class);
        $this->expectExceptionMessage($expectedMessage);

        try {
            new PositiveInt($value, PositiveInt::MAX_MYSQL_INT);
            self::fail('it must throw');
        } catch (\Throwable $th) {
            if ($th instanceof PositiveIntException) {
                self::assertSame(2147483647, $th->getMax());
            } else {
                self::fail('instanceof');
            }

            throw $th;
        }
    }

    public function test_it_must_throw_if_value_is_too_big(
    {
        [
            'value' => 2147483647 + 1,
            'expectedMessage' => 'The (value) is too big, max allowed is 2147483647.'
        ],
    }
}
