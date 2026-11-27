<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Model\ResourceModel\Subscription;

use Example\Storefront\Model\ResourceModel\Subscription as SubscriptionResource;
use Example\Storefront\Model\Subscription as SubscriptionModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Collection for auto-replenishment subscriptions.
 */
class Collection extends AbstractCollection
{
    /**
     * Map model and resource model.
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(SubscriptionModel::class, SubscriptionResource::class);
    }
}
