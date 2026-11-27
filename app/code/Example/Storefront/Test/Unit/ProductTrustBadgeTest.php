<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Test\Unit;

use Example\Storefront\Model\ProductTrustBadge;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Unit tests for the ProductTrustBadge model.
 */
class ProductTrustBadgeTest extends TestCase
{
    /**
     * The badge message should be the expected default text.
     *
     * @return void
     */
    public function testGetMessageReturnsDefaultText(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $badge  = new ProductTrustBadge($logger);

        $this->assertSame('Quality checked and ready to ship.', $badge->getMessage());
    }

    /**
     * The badge should be enabled by default.
     *
     * @return void
     */
    public function testIsEnabledReturnsTrueByDefault(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $badge  = new ProductTrustBadge($logger);

        $this->assertTrue($badge->isEnabled());
    }
}
