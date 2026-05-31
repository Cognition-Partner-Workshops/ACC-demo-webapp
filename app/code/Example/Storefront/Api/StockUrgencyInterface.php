<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Api;

/**
 * Service contract for storefront low-stock urgency messaging.
 *
 * @api
 */
interface StockUrgencyInterface
{
    /**
     * Build the urgency message for the given remaining quantity.
     *
     * @param int $qtyRemaining
     * @return string
     */
    public function getUrgencyMessage(int $qtyRemaining): string;
}
