<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Model\ResourceModel\Banner;

use Example\Storefront\Model\Banner as BannerModel;
use Example\Storefront\Model\ResourceModel\Banner as BannerResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Banner collection.
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
        $this->_init(BannerModel::class, BannerResource::class);
    }
}
