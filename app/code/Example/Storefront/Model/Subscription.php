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
     * Field name constants.
     */
    private const SUBSCRIPTION_ID = 'subscription_id';
    private const CUSTOMER_ID = 'customer_id';
    private const PRODUCT_ID = 'product_id';
    private const CADENCE = 'cadence';
    private const NEXT_DELIVERY_DATE = 'next_delivery_date';
    private const STATUS = 'status';

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
        $value = $this->getData(self::SUBSCRIPTION_ID);
        return $value !== null ? (int) $value : null;
    }

    /**
     * @inheritDoc
     */
    public function setSubscriptionId(int $id): SubscriptionInterface
    {
        return $this->setData(self::SUBSCRIPTION_ID, $id);
    }

    /**
     * @inheritDoc
     */
    public function getCustomerId(): ?int
    {
        $value = $this->getData(self::CUSTOMER_ID);
        return $value !== null ? (int) $value : null;
    }

    /**
     * @inheritDoc
     */
    public function setCustomerId(int $customerId): SubscriptionInterface
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * @inheritDoc
     */
    public function getProductId(): ?int
    {
        $value = $this->getData(self::PRODUCT_ID);
        return $value !== null ? (int) $value : null;
    }

    /**
     * @inheritDoc
     */
    public function setProductId(int $productId): SubscriptionInterface
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }

    /**
     * @inheritDoc
     */
    public function getCadence(): ?string
    {
        return $this->getData(self::CADENCE);
    }

    /**
     * @inheritDoc
     */
    public function setCadence(string $cadence): SubscriptionInterface
    {
        return $this->setData(self::CADENCE, $cadence);
    }

    /**
     * @inheritDoc
     */
    public function getNextDeliveryDate(): ?string
    {
        return $this->getData(self::NEXT_DELIVERY_DATE);
    }

    /**
     * @inheritDoc
     */
    public function setNextDeliveryDate(string $date): SubscriptionInterface
    {
        return $this->setData(self::NEXT_DELIVERY_DATE, $date);
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): ?string
    {
        return $this->getData(self::STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setStatus(string $status): SubscriptionInterface
    {
        return $this->setData(self::STATUS, $status);
    }
}
