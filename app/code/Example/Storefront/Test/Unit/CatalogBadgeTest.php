<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Test\Unit;

use Example\Storefront\Api\StockResolverInterface;
use Example\Storefront\Model\CatalogBadge;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the CatalogBadge model.
 */
class CatalogBadgeTest extends TestCase
{
    /**
     * A recently-added product should return the "New" badge.
     *
     * @return void
     */
    public function testReturnsNewBadgeForRecentlyAddedProduct(): void
    {
        $stockResolver = $this->createMock(StockResolverInterface::class);
        $stockResolver->method('isRecentlyAdded')
            ->with('SKU-001')
            ->willReturn(true);

        $badge = new CatalogBadge($stockResolver);

        $this->assertSame('New', $badge->getBadgeLabel('SKU-001'));
    }

    /**
     * A product that is not recently added should return an empty badge.
     *
     * @return void
     */
    public function testReturnsEmptyBadgeForOlderProduct(): void
    {
        $stockResolver = $this->createMock(StockResolverInterface::class);
        $stockResolver->method('isRecentlyAdded')
            ->with('SKU-OLD')
            ->willReturn(false);

        $badge = new CatalogBadge($stockResolver);

        $this->assertSame('', $badge->getBadgeLabel('SKU-OLD'));
    }
}
