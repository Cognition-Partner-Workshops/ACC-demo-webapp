<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\ViewModel;

use Example\Storefront\Api\BannerRepositoryInterface;
use Example\Storefront\Api\Data\BannerInterface;
use Example\Storefront\Model\BannerRepository;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * ViewModel that provides active hero banners to the template.
 *
 * Supports store-scoped and customer-group-scoped banners for
 * multi-tenant B2B deployments serving 100K+ customers across
 * MENA region stores. Uses the repository's cache layer to
 * minimise database pressure under high concurrency.
 */
class HeroBannerViewModel implements ArgumentInterface
{
    /**
     * @var BannerRepositoryInterface
     */
    private BannerRepositoryInterface $bannerRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @var SortOrderBuilder
     */
    private SortOrderBuilder $sortOrderBuilder;

    /**
     * @var FilterBuilder
     */
    private FilterBuilder $filterBuilder;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @param BannerRepositoryInterface $bannerRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SortOrderBuilder $sortOrderBuilder
     * @param FilterBuilder $filterBuilder
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        BannerRepositoryInterface $bannerRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder,
        FilterBuilder $filterBuilder,
        StoreManagerInterface $storeManager
    ) {
        $this->bannerRepository = $bannerRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->storeManager = $storeManager;
    }

    /**
     * Retrieve active banners sorted by sort order ascending.
     *
     * Filters by is_active=1 and current store scope (store_id=0 for
     * global banners, or the specific store ID). Uses cached repository
     * method when available to support 1000+ parallel sessions.
     *
     * @param int|null $customerGroupId
     * @return BannerInterface[]
     */
    public function getActiveBanners(?int $customerGroupId = null): array
    {
        $sortOrder = $this->sortOrderBuilder
            ->setField(BannerInterface::SORT_ORDER)
            ->setAscendingDirection()
            ->create();

        $storeId = (int) $this->storeManager->getStore()->getId();

        $this->searchCriteriaBuilder
            ->addFilter(BannerInterface::IS_ACTIVE, 1)
            ->addFilter(
                BannerInterface::STORE_ID,
                [0, $storeId],
                'in'
            )
            ->addSortOrder($sortOrder);

        if ($customerGroupId !== null) {
            $groupFilter = $this->filterBuilder
                ->setField(BannerInterface::CUSTOMER_GROUP_ID)
                ->setValue($customerGroupId)
                ->setConditionType('eq')
                ->create();
            $nullFilter = $this->filterBuilder
                ->setField(BannerInterface::CUSTOMER_GROUP_ID)
                ->setConditionType('null')
                ->create();
            $this->searchCriteriaBuilder->addFilters(
                [$groupFilter, $nullFilter]
            );
        }

        $searchCriteria = $this->searchCriteriaBuilder->create();

        if ($this->bannerRepository instanceof BannerRepository) {
            return $this->bannerRepository
                ->getCachedList($storeId, $customerGroupId, $searchCriteria)
                ->getItems();
        }

        return $this->bannerRepository
            ->getList($searchCriteria)
            ->getItems();
    }
}
