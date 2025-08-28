<?php declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Project\DTOConverter\AggregateException;
use Project\DTOConverter\BaseException;
use Project\DTOConverter\InvalidNestedItemException;
use Project\DTOConverter\MissingKeysException;
use Project\DTOConverter\PropertyTypeException;
use Project\DTOConverter\Utils;
use Tests\DTO\CommentDTO\CommentDTOConverter;
use Tests\DTO\TopicDTO\TopicDTOConverter;

final class TopicDTOConverterTest extends TestCase
{
    private TopicDTOConverter $service;

    private const VALID_COMMENT_ROOT = [
        'commentRoot' => [
            'id' => 5,
            'parentId' => 6,
        ],
    ];

    protected function setUp(): void
    {
        $this->service = new TopicDTOConverter(
            new CommentDTOConverter(
                new Utils(),
            ),
            new Utils(),
        );
    }

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
                    'comments' => [],
                    ...self::VALID_COMMENT_ROOT,
                ],
            ],

            [
                'inputData' => [
                    'id' => 3,
                    'parentId' => null,
                    'comments' => [],
                    ...self::VALID_COMMENT_ROOT,
                ],
            ],

            [
                'inputData' => [
                    'id' => 3,
                    'parentId' => null,
                    'comments' => [
                        [
                            'id' => 6,
                            'parentId' => 7,
                        ],
                    ],
                    ...self::VALID_COMMENT_ROOT,
                ],
            ],
        ];
    }

    #[DataProvider('provider_happy')]
    public function test_happy(mixed $inputData): void
    {
        $service = $this->service;

        $topicDTO = $service->convert($inputData);

        self::assertSame($inputData['id'], $topicDTO->getId()->value);
        self::assertSame($inputData['parentId'], $topicDTO->getParentId()?->value);

        self::assertSame(count($inputData['comments']), count($topicDTO->getComments()));
        foreach ($inputData['comments'] as $index => $inputDataComment) {
            self::assertSame($inputDataComment['id'], $topicDTO->getComments()[$index]->getId()->value);
            self::assertSame($inputDataComment['parentId'], $topicDTO->getComments()[$index]->getParentId()?->value);
        }

        self::assertSame($inputData['commentRoot']['id'], $topicDTO->getCommentRoot()->getId()->value);
        self::assertSame($inputData['commentRoot']['parentId'], $topicDTO->getCommentRoot()->getParentId()?->value);
    }

    public function test_it_must_throw_if_input_data_is_not_array(): void
    {
        $service = $this->service;

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
                'expectedMissingKeys' => [
                    'id',
                    'parentId',
                    'comments',
                    'commentRoot',
                ],
                'expectedMessage' => 'The array-keys (id, parentId, comments, commentRoot) must exist.'
            ],
            [
                'inputData' => [
                    'id' => 3,
                    // 'parentId' => 5,
                    'comments' => [],
                    ...self::VALID_COMMENT_ROOT,
                ],
                'expectedMissingKeys' => [
                    'parentId',
                ],
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
        $service = $this->service;

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
                    'comments' => [],
                    ...self::VALID_COMMENT_ROOT,
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
                    'comments' => [],
                    ...self::VALID_COMMENT_ROOT,
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
                    'comments' => [],
                    ...self::VALID_COMMENT_ROOT,
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
                    'comments' => [],
                    ...self::VALID_COMMENT_ROOT,
                ],
                'expectedNestedExceptionsCount' => 1,
                'expectedInvalidProperties' =>  [
                    // 'id',
                    'parentId',
                ],
            ],
            [
                'inputData' => [
                    'id' => 3,
                    'parentId' => 4,
                    'comments' => [
                        [
                            'id' => 3,
                            'parentId' => -4,
                        ]
                    ],
                    ...self::VALID_COMMENT_ROOT,
                ],
                'expectedNestedExceptionsCount' => 1,
                'expectedInvalidProperties' =>  [
                    // 'id',
                    // 'parentId',
                    'comments',
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
        $this->expectException(AggregateException::class);

        try {
            $service = $this->service;
            $service->convert($inputData);
            self::fail('it must throw');
        } catch (AggregateException $e) {
            self::assertCount($expectedNestedExceptionsCount, $e->getExceptions());

            foreach ($e->getExceptions() as $index => $exception) {
                self::assertNotNull($exception->getPrevious());
                self::assertInstanceOf(PropertyTypeException::class, $exception);
                if ($exception instanceof PropertyTypeException) {
                    self::assertSame($expectedInvalidProperties[$index], $exception->invalidPropertyName);
                } else {
                    self::fail('wrong exception type');
                }
            }

            throw $e;
        }
    }

    public function test_invalid_nested_comments()
    {
        $inputData = [
            'id' => 3,
            'parentId' => 4,
            ...self::VALID_COMMENT_ROOT,
            'comments' => [
                [
                    'id' => 3,
                    'parentId' => -4,
                ]
            ],
        ];

        $expectedException = [
            [
                'exceptions' => [
                    // [done] 'message' => 'Invalid type of property (comments).',
                    'previous' => [
                        'class' => AggregateException::class,
                        'atClass' => TopicDTOConverter::class,
                        'exceptions' => [
                            'class' => InvalidNestedItemException::class,
                            'invalidPropertyName' => 'comments',
                            'nestedIndex' => 0,
                            'message' => 'Invalid nested item (comments:0).',
                            'previous' => [
                                'class' => AggregateException::class,
                                'atClass' => TopicDTOConverter::class,
                                'exceptions' => [
                                    'class' => PropertyTypeException::class,
                                    'invalidPropertyName' => 'parentId',
                                    'message' => 'Invalid type of property (parentId).',
                                    'previous' => [
                                        'class' => \InvalidArgumentException::class,
                                        'message' => 'the (value) must be valid (Project\ValueObject\PositiveInt).'
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        try {
            $service = $this->service;
            $service->convert($inputData);
            self::fail('it must throw');
        } catch (\Throwable $eLevel0) {
            self::assertInstanceOf(AggregateException::class, $eLevel0);
            if ($eLevel0 instanceof AggregateException) {
                self::assertSame(TopicDTOConverter::class, $eLevel0->getClassName());
    
                $eFirst = $eLevel0->getExceptions()[0];
                self::assertInstanceOf(PropertyTypeException::class, $eFirst);
                if ($eFirst instanceof PropertyTypeException) {
                    self::assertSame('comments', $eFirst->invalidPropertyName);
                    self::assertSame('Invalid type of property (comments).', $eFirst->getMessage());
        
                    $eFirstPrev = $eFirst->getPrevious();
                    self::assertInstanceOf(AggregateException::class, $eFirstPrev);
                    if ($eFirstPrev instanceof AggregateException) {

                    }
                }
            }
        }
    }
}
