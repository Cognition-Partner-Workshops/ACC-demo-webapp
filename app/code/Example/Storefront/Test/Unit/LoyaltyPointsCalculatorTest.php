<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Test\Unit;

use Example\Storefront\Model\LoyaltyPointsCalculator;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Unit tests for the LoyaltyPointsCalculator model.
 */
class LoyaltyPointsCalculatorTest extends TestCase
{
    /**
     * A whole-dollar price should yield the floored point value.
     *
     * @return void
     */
    public function testCalculatesPointsForWholePrice(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $calculator = new LoyaltyPointsCalculator($logger);

        $this->assertSame(49, $calculator->calculate(49.99));
    }

    /**
     * A zero price should return zero points.
     *
     * @return void
     */
    public function testZeroPriceReturnsZeroPoints(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $calculator = new LoyaltyPointsCalculator($logger);

        $this->assertSame(0, $calculator->calculate(0.00));
    }
}
