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
     * Healthy stock above the threshold should produce no message at all.
     *
     * @return void
     */
    public function testReturnsEmptyStringWhenStockIsHealthy(): void
    {
        $service = new StockUrgencyMessage($this->createMock(LoggerInterface::class), 5);

        $this->assertSame('', $service->getMessage(20.0, true));
    }

    /**
     * Low (but available) stock should produce the urgency nudge with the unit count.
     *
     * @return void
     */
    public function testReturnsLowStockMessageWhenAtOrBelowThreshold(): void
    {
        $service = new StockUrgencyMessage($this->createMock(LoggerInterface::class), 5);

        $this->assertSame('Only 3 left — order soon!', $service->getMessage(3.0, true));
    }

    /**
     * The threshold boundary is inclusive: exactly threshold units still nudges.
     *
     * @return void
     */
    public function testThresholdBoundaryIsInclusive(): void
    {
        $service = new StockUrgencyMessage($this->createMock(LoggerInterface::class), 5);

        $this->assertSame('Only 5 left — order soon!', $service->getMessage(5.0, true));
        $this->assertSame('', $service->getMessage(6.0, true));
    }

    /**
     * Fractional quantities are floored to whole salable units for display.
     *
     * @return void
     */
    public function testFractionalQuantityIsFlooredToWholeUnits(): void
    {
        $service = new StockUrgencyMessage($this->createMock(LoggerInterface::class), 5);

        $this->assertSame('Only 2 left — order soon!', $service->getMessage(2.9, true));
    }

    /**
     * Zero salable quantity should read as out of stock even if flagged in stock.
     *
     * @return void
     */
    public function testReturnsOutOfStockWhenQuantityIsZero(): void
    {
        $service = new StockUrgencyMessage($this->createMock(LoggerInterface::class), 5);

        $this->assertSame('Out of stock', $service->getMessage(0.0, true));
    }

    /**
     * A product flagged out of stock is out of stock regardless of quantity.
     *
     * @return void
     */
    public function testReturnsOutOfStockWhenFlaggedOutOfStock(): void
    {
        $service = new StockUrgencyMessage($this->createMock(LoggerInterface::class), 5);

        $this->assertSame('Out of stock', $service->getMessage(50.0, false));
    }

    /**
     * The low-stock threshold is configurable via constructor injection.
     *
     * @return void
     */
    public function testRespectsCustomThreshold(): void
    {
        $service = new StockUrgencyMessage($this->createMock(LoggerInterface::class), 2);

        $this->assertSame('', $service->getMessage(3.0, true));
        $this->assertSame('Only 2 left — order soon!', $service->getMessage(2.0, true));
    }

    /**
     * The low-stock path should be logged for observability.
     *
     * @return void
     */
    public function testLogsWhenLowStockMessageIsShown(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method('info')
            ->with($this->stringContains('Low-stock urgency shown for 3'));

        $service = new StockUrgencyMessage($logger, 5);
        $service->getMessage(3.0, true);
    }
}
