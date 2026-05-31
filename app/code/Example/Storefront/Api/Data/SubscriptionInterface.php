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
     * Cadence value: weekly.
     */
    public const CADENCE_WEEKLY = 'weekly';

    /**
     * Cadence value: monthly.
     */
    public const CADENCE_MONTHLY = 'monthly';

    /**
     * Cadence value: quarterly.
     */
    public const CADENCE_QUARTERLY = 'quarterly';

    /**
     * Status value: active.
     */
    public const STATUS_ACTIVE = 'active';

    /**
     * Status value: paused.
     */
    public const STATUS_PAUSED = 'paused';

    /**
     * Status value: cancelled.
     */
    public const STATUS_CANCELLED = 'cancelled';

    /**
     * Get subscription ID.
     *
     * @return int|null
     */
    public function getSubscriptionId(): ?int;

    /**
     * Set subscription ID.
     *
     * @param int $subscriptionId
     * @return self
     */
    public function setSubscriptionId(int $subscriptionId): self;

    /**
     * Get customer ID.
     *
     * @return int
     */
    public function getCustomerId(): int;

    /**
     * Set customer ID.
     *
     * @param int $customerId
     * @return self
     */
    public function setCustomerId(int $customerId): self;

    /**
     * Get product ID.
     *
     * @return int
     */
    public function getProductId(): int;

    /**
     * Set product ID.
     *
     * @param int $productId
     * @return self
     */
    public function setProductId(int $productId): self;

    /**
     * Get replenishment cadence.
     *
     * @return string
     */
    public function getCadence(): string;

    /**
     * Set replenishment cadence.
     *
     * @param string $cadence
     * @return self
     */
    public function setCadence(string $cadence): self;

    /**
     * Get next delivery date.
     *
     * @return string
     */
    public function getNextDeliveryDate(): string;

    /**
     * Set next delivery date.
     *
     * @param string $date
     * @return self
     */
    public function setNextDeliveryDate(string $date): self;

    /**
     * Get subscription status.
     *
     * @return string
     */
    public function getStatus(): string;

    /**
     * Set subscription status.
     *
     * @param string $status
     * @return self
     */
    public function setStatus(string $status): self;
}
