<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Test\Unit;

use Example\Storefront\Model\StockUrgency;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Unit tests for the StockUrgency model.
 */
class StockUrgencyTest extends TestCase
{
    /**
     * The urgency message should reflect the remaining quantity.
     *
     * @dataProvider urgencyMessageProvider
     * @param int $qtyRemaining
     * @param string $expected
     * @return void
     */
    public function testReturnsUrgencyMessage(int $qtyRemaining, string $expected): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $stockUrgency = new StockUrgency($logger);

        $this->assertSame($expected, $stockUrgency->getUrgencyMessage($qtyRemaining));
    }

    /**
     * Data provider for urgency message scenarios.
     *
     * @return array<string, array{int, string}>
     */
    public function urgencyMessageProvider(): array
    {
        return [
            'out of stock' => [0, 'Out of stock'],
            'negative treated as out of stock' => [-3, 'Out of stock'],
            'low stock single' => [1, 'Only 1 left — order soon!'],
            'low stock at threshold' => [5, 'Only 5 left — order soon!'],
            'healthy stock' => [42, ''],
        ];
    }
}
