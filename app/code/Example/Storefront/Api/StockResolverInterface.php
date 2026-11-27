<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Api;

/**
 * Service contract for resolving product stock and availability metadata.
 *
 * @api
 */
interface StockResolverInterface
{
    /**
     * Determine whether the product identified by SKU was recently added to the catalog.
     *
     * @param string $sku
     * @return bool
     */
    public function isRecentlyAdded(string $sku): bool;
}
