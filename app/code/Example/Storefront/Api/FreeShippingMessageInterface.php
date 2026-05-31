<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Api;

/**
 * Service contract that owns the free-shipping threshold copy for the storefront.
 *
 * @api
 */
interface FreeShippingMessageInterface
{
    /**
     * Get the order subtotal threshold (in whole currency units) that unlocks free shipping.
     *
     * @return int
     */
    public function getThreshold(): int;

    /**
     * Build the customer-facing free-shipping banner message.
     *
     * @return string
     */
    public function getMessage(): string;
}
