<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Model;

use Example\Storefront\Api\Data\SubscriptionInterface;
use Example\Storefront\Model\ResourceModel\Subscription as SubscriptionResource;
use Magento\Framework\Model\AbstractModel;

/**
 * Subscription model for auto-replenishment.
 */
class Subscription extends AbstractModel implements SubscriptionInterface
{
    /**
     * Key for subscription_id column.
     */
    private const KEY_SUBSCRIPTION_ID = 'subscription_id';

    /**
     * Key for customer_id column.
     */
    private const KEY_CUSTOMER_ID = 'customer_id';

    /**
     * Key for product_id column.
     */
    private const KEY_PRODUCT_ID = 'product_id';

    /**
     * Key for cadence column.
     */
    private const KEY_CADENCE = 'cadence';

    /**
     * Key for next_delivery_date column.
     */
    private const KEY_NEXT_DELIVERY_DATE = 'next_delivery_date';

    /**
     * Key for status column.
     */
    private const KEY_STATUS = 'status';

    /**
     * Initialize resource model.
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(SubscriptionResource::class);
    }

    /**
     * @inheritDoc
     */
    public function getSubscriptionId(): ?int
    {
        $value = $this->getData(self::KEY_SUBSCRIPTION_ID);
        return $value !== null ? (int) $value : null;
    }

    /**
     * @inheritDoc
     */
    public function setSubscriptionId(int $subscriptionId): SubscriptionInterface
    {
        return $this->setData(self::KEY_SUBSCRIPTION_ID, $subscriptionId);
    }

    /**
     * @inheritDoc
     */
    public function getCustomerId(): int
    {
        return (int) $this->getData(self::KEY_CUSTOMER_ID);
    }

    /**
     * @inheritDoc
     */
    public function setCustomerId(int $customerId): SubscriptionInterface
    {
        return $this->setData(self::KEY_CUSTOMER_ID, $customerId);
    }

    /**
     * @inheritDoc
     */
    public function getProductId(): int
    {
        return (int) $this->getData(self::KEY_PRODUCT_ID);
    }

    /**
     * @inheritDoc
     */
    public function setProductId(int $productId): SubscriptionInterface
    {
        return $this->setData(self::KEY_PRODUCT_ID, $productId);
    }

    /**
     * @inheritDoc
     */
    public function getCadence(): string
    {
        return (string) $this->getData(self::KEY_CADENCE);
    }

    /**
     * @inheritDoc
     */
    public function setCadence(string $cadence): SubscriptionInterface
    {
        return $this->setData(self::KEY_CADENCE, $cadence);
    }

    /**
     * @inheritDoc
     */
    public function getNextDeliveryDate(): string
    {
        return (string) $this->getData(self::KEY_NEXT_DELIVERY_DATE);
    }

    /**
     * @inheritDoc
     */
    public function setNextDeliveryDate(string $date): SubscriptionInterface
    {
        return $this->setData(self::KEY_NEXT_DELIVERY_DATE, $date);
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): string
    {
        return (string) $this->getData(self::KEY_STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setStatus(string $status): SubscriptionInterface
    {
        return $this->setData(self::KEY_STATUS, $status);
    }
}
