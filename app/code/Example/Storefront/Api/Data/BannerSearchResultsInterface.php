<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Search results interface for banner entities.
 *
 * @api
 */
interface BannerSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get list of banners.
     *
     * @return \Example\Storefront\Api\Data\BannerInterface[]
     */
    public function getItems(): array;

    /**
     * Set list of banners.
     *
     * @param \Example\Storefront\Api\Data\BannerInterface[] $items
     * @return self
     */
    public function setItems(array $items): self;
}
