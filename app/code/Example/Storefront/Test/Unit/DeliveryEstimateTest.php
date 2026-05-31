<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Test\Unit;

use Example\Storefront\Model\DeliveryEstimate;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Unit tests for the DeliveryEstimate model.
 */
class DeliveryEstimateTest extends TestCase
{
    /**
     * The estimate should be a human-readable string reporting business days.
     *
     * @return void
     */
    public function testReturnsHumanReadableEstimate(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $estimate = new DeliveryEstimate($logger);

        $this->assertSame('Arrives in 5 business days', $estimate->getEstimate('SKU-123'));
    }
}
