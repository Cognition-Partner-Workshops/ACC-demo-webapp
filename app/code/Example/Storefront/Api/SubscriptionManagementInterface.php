<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Api;

use Example\Storefront\Api\Data\SubscriptionInterface;

/**
 * Management service contract for subscription business operations.
 *
 * @api
 */
interface SubscriptionManagementInterface
{
    /**
     * Create a new auto-replenishment subscription.
     *
     * @param int $customerId
     * @param int $productId
     * @param string $cadence
     * @return SubscriptionInterface
     */
    public function createSubscription(
        int $customerId,
        int $productId,
        string $cadence
    ): SubscriptionInterface;

    /**
     * Cancel an existing subscription.
     *
     * @param int $subscriptionId
     * @return bool
     */
    public function cancelSubscription(int $subscriptionId): bool;

    /**
     * Pause an active subscription.
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
