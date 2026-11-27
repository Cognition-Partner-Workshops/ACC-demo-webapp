<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Banner resource model.
 */
class Banner extends AbstractDb
{
    /**
     * Initialize table and primary key.
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init('example_storefront_banner', 'banner_id');
    }
}
