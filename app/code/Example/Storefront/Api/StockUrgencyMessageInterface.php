<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Api;

/**
 * Service contract for building storefront stock-urgency messages.
 *
 * Produces a short shopper-facing message that conveys scarcity:
 * a "low stock" nudge when only a few units remain, an "out of stock"
 * notice when nothing is available, and an empty string when supply is
 * healthy (so the storefront renders nothing at all).
 *
 * @api
 */
interface StockUrgencyMessageInterface
{
    /**
     * Build the urgency message for a product given its salable quantity.
     *
     * @param float $salableQuantity The quantity available for sale.
     * @param bool $isInStock Whether the product is flagged in stock.
     * @return string The shopper-facing message, or an empty string when none applies.
     */
    public function getMessage(float $salableQuantity, bool $isInStock): string;
}
