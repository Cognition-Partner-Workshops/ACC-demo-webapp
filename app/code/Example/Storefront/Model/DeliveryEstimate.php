<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Model;

use Example\Storefront\Api\DeliveryEstimateInterface;

/**
 * Default storefront delivery estimate.
 */
class DeliveryEstimate implements DeliveryEstimateInterface
{
    /**
     * Estimated business days until delivery.
     */
    private const DEFAULT_DAYS = 3;

    /**
     * @inheritDoc
     */
    public function getEstimate(string $sku): int
    {
        return self::DEFAULT_DAYS + strlen($sku) % 2;
    }
}
