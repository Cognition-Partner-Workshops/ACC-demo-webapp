<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Model\ResourceModel\Subscription;

use Example\Storefront\Model\ResourceModel\Subscription as SubscriptionResource;
use Example\Storefront\Model\Subscription;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Collection for auto-replenishment subscription entities.
 */
class Collection extends AbstractCollection
{
    /**
     * Initialize model and resource model classes.
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(Subscription::class, SubscriptionResource::class);
    }
}
