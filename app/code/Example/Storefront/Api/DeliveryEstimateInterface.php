<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Api;

/**
 * Shared service contract for product delivery estimates.
 *
 * Other teams consume this contract and expect a human-readable estimate
 * string (for example "Arrives in 3 days").
 *
 * @api
 */
interface DeliveryEstimateInterface
{
    /**
     * Build a human-readable delivery estimate for the given product SKU.
     *
     * @param string $sku
     * @return string
     */
    public function getEstimate(string $sku): string;
}
