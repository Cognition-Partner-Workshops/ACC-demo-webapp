<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Api\Data;

/**
 * Data interface for auto-replenishment subscriptions.
 *
 * @api
 */
interface SubscriptionInterface
{
    /**
     * Get subscription ID.
     *
     * @return int|null
     */
    public function getSubscriptionId(): ?int;

    /**
     * Set subscription ID.
     *
     * @param int $id
     * @return SubscriptionInterface
     */
    public function setSubscriptionId(int $id): SubscriptionInterface;

    /**
     * Get customer ID.
     *
     * @return int|null
     */
    public function getCustomerId(): ?int;

    /**
     * Set customer ID.
     *
     * @param int $customerId
     * @return SubscriptionInterface
     */
    public function setCustomerId(int $customerId): SubscriptionInterface;

    /**
     * Get product ID.
     *
     * @return int|null
     */
    public function getProductId(): ?int;

    /**
     * Set product ID.
     *
     * @param int $productId
     * @return SubscriptionInterface
     */
    public function setProductId(int $productId): SubscriptionInterface;

    /**
     * Get replenishment cadence (weekly, monthly, quarterly).
     *
     * @return string|null
     */
    public function getCadence(): ?string;

    /**
     * Set replenishment cadence.
     *
     * @param string $cadence
     * @return SubscriptionInterface
     */
    public function setCadence(string $cadence): SubscriptionInterface;

    /**
     * Get next scheduled delivery date.
     *
     * @return string|null
     */
    public function getNextDeliveryDate(): ?string;

    /**
     * Set next scheduled delivery date.
     *
     * @param string $date
     * @return SubscriptionInterface
     */
    public function setNextDeliveryDate(string $date): SubscriptionInterface;

    /**
     * Get subscription status.
     *
     * @return string|null
     */
    public function getStatus(): ?string;

    /**
     * Set subscription status.
     *
     * @param string $status
     * @return SubscriptionInterface
     */
    public function setStatus(string $status): SubscriptionInterface;
}
