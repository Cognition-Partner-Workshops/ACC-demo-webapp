<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Model;

use Example\Storefront\Api\StockResolverInterface;

/**
 * Default implementation of stock availability metadata resolution.
 */
class StockResolver implements StockResolverInterface
{
    /**
     * Number of days a product is considered "recently added".
     */
    private const RECENT_DAYS_THRESHOLD = 30;

    /**
     * @inheritDoc
     */
    public function isRecentlyAdded(string $sku): bool
    {
        // Stub: in a full Magento environment this would query the catalog
        // for the product's created_at date relative to the threshold.
        return false;
    }
}
