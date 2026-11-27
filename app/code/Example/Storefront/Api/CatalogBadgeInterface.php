<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Api;

/**
 * Service contract for resolving storefront product badges (e.g. "New").
 *
 * @api
 */
interface CatalogBadgeInterface
{
    /**
     * Resolve the badge label for the given product SKU.
     *
     * @param string $sku
     * @return string
     */
    public function getBadgeLabel(string $sku): string;
}
