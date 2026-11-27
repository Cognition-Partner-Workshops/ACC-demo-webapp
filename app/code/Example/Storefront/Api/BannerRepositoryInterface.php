<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Api;

use Example\Storefront\Api\Data\BannerInterface;
use Example\Storefront\Api\Data\BannerSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Repository interface for banner entities.
 *
 * @api
 */
interface BannerRepositoryInterface
{
    /**
     * Load a banner by its ID.
     *
     * @param int $bannerId
     * @return \Example\Storefront\Api\Data\BannerInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $bannerId): BannerInterface;

    /**
     * Persist a banner entity.
     *
     * @param \Example\Storefront\Api\Data\BannerInterface $banner
     * @return \Example\Storefront\Api\Data\BannerInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(BannerInterface $banner): BannerInterface;

    /**
     * Delete a banner entity.
     *
     * @param \Example\Storefront\Api\Data\BannerInterface $banner
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(BannerInterface $banner): bool;

    /**
     * Retrieve banners matching the given search criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Example\Storefront\Api\Data\BannerSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): BannerSearchResultsInterface;
}
