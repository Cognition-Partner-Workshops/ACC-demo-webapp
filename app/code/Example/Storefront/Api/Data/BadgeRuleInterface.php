<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Api\Data;

/**
 * A single badge rule that decides whether a product qualifies for a badge.
 *
 * Implementations are collected via DI and evaluated by the CatalogBadge service.
 *
 * @api
 */
interface BadgeRuleInterface
{
    /**
     * Return the badge label if the product qualifies, or null otherwise.
     *
     * @param int $productId
     * @return string|null
     */
    public function resolve(int $productId): ?string;
}
