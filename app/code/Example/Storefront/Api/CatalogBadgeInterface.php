<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Api;

/**
 * Service contract for assigning display badges to catalog products.
 *
 * Badges such as "New", "Sale", or "Bestseller" are resolved at runtime based
 * on product attributes and injected badge rules.
 *
 * @api
 */
interface CatalogBadgeInterface
{
    /**
     * Return the list of badge labels that apply to the given product ID.
     *
     * @param int $productId
     * @return string[]
     */
    public function getBadges(int $productId): array;
}
