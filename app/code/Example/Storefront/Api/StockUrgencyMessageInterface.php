<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Api;

/**
 * Service contract for building low-stock urgency messages on the storefront.
 *
 * @api
 */
interface StockUrgencyMessageInterface
{
    /**
     * Build the urgency message for a product given its remaining quantity.
     *
     * Returns "Out of stock" when nothing is left, a short "Only N left" prompt
     * when stock is running low, and an empty string when stock is healthy.
     *
     * @param int $qtyRemaining
     * @return string
     */
    public function getMessage(int $qtyRemaining): string;
}
