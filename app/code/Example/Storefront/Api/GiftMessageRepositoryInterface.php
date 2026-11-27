<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Api;

/**
 * Service contract for persisting a customer's gift message for an order.
 *
 * @api
 */
interface GiftMessageRepositoryInterface
{
    /**
     * Persist the given gift message.
     *
     * @param string $message
     * @return void
     */
    public function save(string $message): void;
}
