<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Test\Unit;

use Example\Storefront\Model\StockUrgencyMessage;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Unit tests for the StockUrgencyMessage model.
 */
class StockUrgencyMessageTest extends TestCase
{
    /**
     * The urgency message should reflect the remaining quantity.
     *
     * @dataProvider messageProvider
     * @param int $qtyRemaining
     * @param string $expected
     * @return void
     */
    public function testReturnsMessageForRemainingQuantity(int $qtyRemaining, string $expected): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $model = new StockUrgencyMessage($logger);

        $this->assertSame($expected, $model->getMessage($qtyRemaining));
    }

    /**
     * Data provider covering the out-of-stock, low-stock and healthy-stock cases.
     *
     * @return array<string, array{int, string}>
     */
    public function messageProvider(): array
    {
        return [
            'out of stock' => [0, 'Out of stock'],
            'negative treated as out of stock' => [-5, 'Out of stock'],
            'low stock single unit' => [1, 'Only 1 left — order soon!'],
            'low stock matches example' => [3, 'Only 3 left — order soon!'],
            'low stock at threshold' => [5, 'Only 5 left — order soon!'],
            'healthy stock just above threshold' => [6, ''],
            'healthy stock plentiful' => [250, ''],
        ];
    }

    /**
     * A custom threshold injected via DI should change when the low-stock prompt appears.
     *
     * @return void
     */
    public function testRespectsCustomThreshold(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $model = new StockUrgencyMessage($logger, 2);

        $this->assertSame('Only 2 left — order soon!', $model->getMessage(2));
        $this->assertSame('', $model->getMessage(3));
    }

    /**
     * Showing a low-stock prompt should be logged for observability.
     *
     * @return void
     */
    public function testLogsWhenLowStockMessageShown(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method('info')
            ->with($this->stringContains('qty 4'));

        $model = new StockUrgencyMessage($logger);

        $this->assertSame('Only 4 left — order soon!', $model->getMessage(4));
    }

    /**
     * Healthy and out-of-stock states should not emit a low-stock log entry.
     *
     * @return void
     */
    public function testDoesNotLogWhenNoLowStockMessage(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->never())->method('info');

        $model = new StockUrgencyMessage($logger);

        $this->assertSame('', $model->getMessage(100));
        $this->assertSame('Out of stock', $model->getMessage(0));
    }
}
