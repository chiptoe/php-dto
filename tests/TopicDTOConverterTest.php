<?php declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Project\DTOConverter\BaseException;
use Project\DTOConverter\MissingKeysException;
use Project\DTO\TopicDTO\TopicDTOConverter;
use Project\DTOConverter\Utils;
use PHPUnit\Framework\Attributes\DataProvider;
use Project\DTOConverter\PropertyTypeListException;

final class TopicDTOConverterTest extends TestCase
{
    /**
     * @return mixed[]
     */
    public static function provider_happy(): array
    {
        return [
            [
                'inputData' => [
                    'id' => 3,
                    'parentId' => 5,
                ],
            ],

            [
                'inputData' => [
                    'id' => 3,
                    'parentId' => null,
                ],
            ],
        ];
    }

    #[DataProvider('provider_happy')]
    public function test_happy(mixed $inputData): void
    {
        $service = new TopicDTOConverter(new Utils());

        $topicDTO = $service->convert($inputData);

        self::assertSame($inputData['parentId'], $topicDTO->getParentId()->value);
        self::assertSame($inputData['id'], $topicDTO->getId()->value);
    }

    public function test_it_must_throw_if_input_data_is_not_array(): void
    {
        $service = new TopicDTOConverter(new Utils());

        $this->expectException(BaseException::class);
        $this->expectExceptionMessage('The (inputData) must be array.');

        $service->convert(123);
    }

    /**
     * @return mixed[]
     */
    public static function provider_it_must_throw_if_some_keys_are_missing(): array
    {
        return [
            [
                'inputData' => [
                    // 'id' => 3,
                    // 'parentId' => 5,
                ],
                'expectedMissingKeys' => ['id', 'parentId'],
                'expectedMessage' => 'The array-keys (id, parentId) must exist.'
            ],
            [
                'inputData' => [
                    'id' => 3,
                    // 'parentId' => 5,
                ],
                'expectedMissingKeys' => ['parentId'],
                'expectedMessage' => 'The array-key (parentId) must exist.'
            ],
        ];
    }

    /**
     * @param list<string> $expectedMissingKeys
     */
    #[DataProvider('provider_it_must_throw_if_some_keys_are_missing')]
    public function test_it_must_throw_if_some_keys_are_missing(
        mixed $inputData,
        array $expectedMissingKeys,
        string $expectedMessage
    ): void
    {
        $service = new TopicDTOConverter(new Utils());

        $this->expectException(MissingKeysException::class);
        $this->expectExceptionMessage($expectedMessage);

        try {
            $service->convert($inputData);
            self::fail('it must throw');
        } catch (MissingKeysException $e) {
            self::assertSame($expectedMissingKeys, $e->missingKeys);
            throw $e;
        }
    }


    /**
     * @return mixed[]
     */
    public static function provider_it_must_throw_if_property_has_wrong_type(): array
    {
        return [
            [
                'inputData' => [
                    'id' => -3,
                    'parentId' => -5,
                ],
                'expectedNestedExceptionsCount' => 2,
                'expectedInvalidProperties' =>  [
                    'id',
                    'parentId',
                ],
            ],
            [
                'inputData' => [
                    'id' => null,
                    'parentId' => 0,
                ],
                'expectedNestedExceptionsCount' => 2,
                'expectedInvalidProperties' =>  [
                    'id',
                    'parentId',
                ],
            ],
            [
                'inputData' => [
                    'id' => 'aaa',
                    'parentId' => 'bbb',
                ],
                'expectedNestedExceptionsCount' => 2,
                'expectedInvalidProperties' =>  [
                    'id',
                    'parentId',
                ],
            ],
            [
                'inputData' => [
                    'id' => 3,
                    'parentId' => 'bbb',
                ],
                'expectedNestedExceptionsCount' => 1,
                'expectedInvalidProperties' =>  [
                    // 'id',
                    'parentId',
                ],
            ],
        ];
    }

    /**
     * @param list<string> $expectedInvalidProperties
     */
    #[DataProvider('provider_it_must_throw_if_property_has_wrong_type')]
    public function test_it_must_throw_if_property_has_wrong_type(
        mixed $inputData,
        int $expectedNestedExceptionsCount,
        array $expectedInvalidProperties,
    ): void
    {
        $this->expectException(PropertyTypeListException::class);

        try {
            $service = new TopicDTOConverter(new Utils());
            $service->convert($inputData);
            self::fail('it must throw');
        } catch (PropertyTypeListException $e) {
            foreach ($e->getExceptions() as $index => $exception) {
                self::assertNotNull($exception->getPrevious());
                self::assertSame($expectedInvalidProperties[$index], $exception->invalidPropertyName);
            }

            self::assertCount($expectedNestedExceptionsCount, $e->getExceptions());
            throw $e;
        }
    }
}
