<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Api;

use Example\Storefront\Api\Data\SubscriptionInterface;

/**
 * Service contract for managing auto-replenishment subscriptions.
 *
 * @api
 */
interface SubscriptionManagementInterface
{
    /**
     * Create a new subscription for a customer and product.
     *
     * @param int $customerId
     * @param int $productId
     * @param string $cadence
     * @return SubscriptionInterface
     * @throws \InvalidArgumentException If cadence is invalid.
     */
    public function createSubscription(
        int $customerId,
        int $productId,
        string $cadence
    ): SubscriptionInterface;

    /**
     * Cancel a subscription.
     *
     * @param int $subscriptionId
     * @return bool
     */
    public function cancelSubscription(int $subscriptionId): bool;

    /**
     * Pause a subscription.
     *
     * @param int $subscriptionId
     * @return bool
     */
    public function pauseSubscription(int $subscriptionId): bool;

    /**
     * Resume a paused subscription.
     *
     * @param int $subscriptionId
     * @return bool
     */
    public function resumeSubscription(int $subscriptionId): bool;
}
