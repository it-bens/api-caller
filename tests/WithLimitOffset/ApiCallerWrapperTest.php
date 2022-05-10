<?php

declare(strict_types=1);

namespace ITB\ApiCaller\WithLimitOffset\Tests;

use Generator;
use ITB\ApiCaller\Tests\WithLimitOffset\ApiCallerMock;
use ITB\ApiCaller\WithLimitOffset\ApiCallerInterface;
use ITB\ApiCaller\WithLimitOffset\ApiCallerWrapper;
use PHPUnit\Framework\TestCase;

final class ApiCallerWrapperTest extends TestCase
{
    private const NO_ITEM_COUNT = 0;
    private const WITH_ITEM_COUNT = 100;
    private const MAX_RESULTS_PER_REQUEST = 5;

    use ApiCallerWrapper;

    /**
     * @return Generator
     */
    public function provideApiCallerNoItems(): Generator
    {
        yield [new ApiCallerMock(self::NO_ITEM_COUNT, self::MAX_RESULTS_PER_REQUEST)];
    }

    /**
     * @return Generator
     */
    public function provideApiCallerWithItems(): Generator
    {
        yield [new ApiCallerMock(self::WITH_ITEM_COUNT, self::MAX_RESULTS_PER_REQUEST)];
    }

    /**
     * @dataProvider provideApiCallerWithItems
     *
     * @param ApiCallerInterface $apiCaller
     * @return void
     */
    public function testRequestMoreItemsThanLimitWithoutOffset(ApiCallerInterface $apiCaller): void
    {
        $items = $this->request($apiCaller, 10);
        self::assertCount(10, $items);
    }

    /**
     * @dataProvider provideApiCallerWithItems
     *
     * @param ApiCallerInterface $apiCaller
     * @return void
     */
    public function testRequestMoreItemsThanLimitWithOffsetNotOverflowing(ApiCallerInterface $apiCaller): void
    {
        $items = $this->request($apiCaller, 10, 10);
        self::assertCount(10, $items);
    }

    /**
     * @dataProvider provideApiCallerWithItems
     *
     * @param ApiCallerInterface $apiCaller
     * @return void
     */
    public function testRequestMoreItemsThanLimitWithOffsetOverflowing(ApiCallerInterface $apiCaller): void
    {
        $items = $this->request($apiCaller, 10, 95);
        self::assertCount(self::WITH_ITEM_COUNT - 95, $items);
    }

    /**
     * @dataProvider provideApiCallerWithItems
     *
     * @param ApiCallerInterface $apiCaller
     * @return void
     */
    public function testRequestLessItemsThanLimitWithoutOffset(ApiCallerInterface $apiCaller): void
    {
        $items = $this->request($apiCaller, 120);
        self::assertCount(self::WITH_ITEM_COUNT, $items);
    }

    /**
     * @dataProvider provideApiCallerWithItems
     *
     * @param ApiCallerInterface $apiCaller
     * @return void
     */
    public function testRequestLessItemsThanLimitWithOffsetNotOverflowing(ApiCallerInterface $apiCaller): void
    {
        $items = $this->request($apiCaller, 120, 15);
        self::assertCount(self::WITH_ITEM_COUNT - 15, $items);
    }

    /**
     * @dataProvider provideApiCallerWithItems
     *
     * @param ApiCallerInterface $apiCaller
     * @return void
     */
    public function testRequestLessItemsThanLimitWithOffsetOverflowing(ApiCallerInterface $apiCaller): void
    {
        $items = $this->request($apiCaller, 120, 95);
        self::assertCount(self::WITH_ITEM_COUNT - 95, $items);
    }

    /**
     * @dataProvider provideApiCallerNoItems
     *
     * @param ApiCallerInterface $apiCaller
     * @return void
     */
    public function testRequestNoItemsWithoutLimitWithoutOffset(ApiCallerInterface $apiCaller): void
    {
        $items = $this->request($apiCaller);
        self::assertCount(self::NO_ITEM_COUNT, $items);
    }

    /**
     * @dataProvider provideApiCallerNoItems
     *
     * @param ApiCallerInterface $apiCaller
     * @return void
     */
    public function testRequestNoItemsWithLimitWithoutOffset(ApiCallerInterface $apiCaller): void
    {
        $items = $this->request($apiCaller, 10);
        self::assertCount(self::NO_ITEM_COUNT, $items);
    }

    /**
     * @dataProvider provideApiCallerNoItems
     *
     * @param ApiCallerInterface $apiCaller
     * @return void
     */
    public function testRequestNoItemsWithoutLimitWithOffset(ApiCallerInterface $apiCaller): void
    {
        $items = $this->request($apiCaller, null, 5);
        self::assertCount(self::NO_ITEM_COUNT, $items);
    }
}
