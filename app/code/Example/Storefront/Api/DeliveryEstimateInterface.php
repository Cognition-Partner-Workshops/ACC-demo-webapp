<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Api;

/**
 * Service contract for storefront delivery estimates.
 *
 * @api
 */
interface DeliveryEstimateInterface
{
    /**
     * Return a human-readable delivery estimate for the given SKU.
     *
     * @param string $sku
     * @return string
     */
    public function getEstimate(string $sku): string;
}
