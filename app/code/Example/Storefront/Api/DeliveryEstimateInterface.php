<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Api;

/**
 * Shared delivery-estimate service contract consumed across teams.
 *
 * Implementations return a human-readable delivery estimate for a product,
 * e.g. "Arrives in 3 days".
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
