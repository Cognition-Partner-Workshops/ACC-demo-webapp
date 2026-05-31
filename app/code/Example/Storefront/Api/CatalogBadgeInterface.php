<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Api;

/**
 * Service contract for resolving storefront product badges by SKU.
 *
 * @api
 */
interface CatalogBadgeInterface
{
    /**
     * Return the badge label for the given product SKU (e.g. "New"), or empty string if none.
     *
     * @param string $sku
     * @return string
     */
    public function getBadgeLabel(string $sku): string;
}
