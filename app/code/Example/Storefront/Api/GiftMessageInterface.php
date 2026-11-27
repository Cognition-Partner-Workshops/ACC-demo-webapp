<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Api;

/**
 * Service contract for attaching a gift message to an order.
 *
 * @api
 */
interface GiftMessageInterface
{
    /**
     * Persist a customer's gift message for the current order.
     *
     * @param string $message
     * @return void
     */
    public function saveMessage(string $message): void;
}
