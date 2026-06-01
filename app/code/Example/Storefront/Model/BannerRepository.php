<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Model;

use Example\Storefront\Api\BannerRepositoryInterface;
use Example\Storefront\Api\Data\BannerInterface;
use Example\Storefront\Api\Data\BannerSearchResultsInterface;
use Example\Storefront\Api\Data\BannerSearchResultsInterfaceFactory;
use Example\Storefront\Model\Cache\BannerCacheType;
use Example\Storefront\Model\ResourceModel\Banner as BannerResource;
use Example\Storefront\Model\ResourceModel\Banner\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\SerializerInterface;

/**
 * Repository implementation for banner entities.
 *
 * Designed for high-concurrency B2B workloads:
 * - Identity map prevents duplicate DB loads within a single request
 * - Cache layer (Redis/Varnish-backed) reduces DB pressure across requests
 * - Composite index on (is_active, store_id, sort_order) optimises the
 *   most frequent query path for 100K+ concurrent customer sessions
 */
class BannerRepository implements BannerRepositoryInterface
{
    /**
     * @var BannerResource
     */
    private BannerResource $resource;

    /**
     * @var BannerFactory
     */
    private BannerFactory $bannerFactory;

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * @var BannerSearchResultsInterfaceFactory
     */
    private BannerSearchResultsInterfaceFactory $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private CollectionProcessorInterface $collectionProcessor;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @var BannerCacheType
     */
    private BannerCacheType $cache;

    /**
     * In-memory identity map keyed by banner_id.
     *
     * Prevents duplicate DB loads within the same HTTP request
     * when multiple blocks or view models reference the same banner.
     *
     * @var BannerInterface[]
     */
    private array $identityMap = [];

    /**
     * @param BannerResource $resource
     * @param BannerFactory $bannerFactory
     * @param CollectionFactory $collectionFactory
     * @param BannerSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param SerializerInterface $serializer
     * @param BannerCacheType $cache
     */
    public function __construct(
        BannerResource $resource,
        BannerFactory $bannerFactory,
        CollectionFactory $collectionFactory,
        BannerSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor,
        SerializerInterface $serializer,
        BannerCacheType $cache
    ) {
        $this->resource = $resource;
        $this->bannerFactory = $bannerFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->serializer = $serializer;
        $this->cache = $cache;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $bannerId): BannerInterface
    {
        if (isset($this->identityMap[$bannerId])) {
            return $this->identityMap[$bannerId];
        }

        $banner = $this->bannerFactory->create();
        $this->resource->load($banner, $bannerId);

        if (!$banner->getBannerId()) {
            throw new NoSuchEntityException(
                __('Banner with ID "%1" does not exist.', $bannerId)
            );
        }

        $this->identityMap[$bannerId] = $banner;

        return $banner;
    }

    /**
     * @inheritDoc
     */
    public function save(BannerInterface $banner): BannerInterface
    {
        try {
            /** @var Banner $banner */
            $this->resource->save($banner);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the banner: %1', $exception->getMessage()),
                $exception
            );
        }

        $bannerId = $banner->getBannerId();
        if ($bannerId !== null) {
            $this->identityMap[$bannerId] = $banner;
        }
        $this->invalidateCache();

        return $banner;
    }

    /**
     * @inheritDoc
     */
    public function delete(BannerInterface $banner): bool
    {
        try {
            /** @var Banner $banner */
            $this->resource->delete($banner);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __('Could not delete the banner: %1', $exception->getMessage()),
                $exception
            );
        }

        $bannerId = $banner->getBannerId();
        if ($bannerId !== null) {
            unset($this->identityMap[$bannerId]);
        }
        $this->invalidateCache();

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        SearchCriteriaInterface $searchCriteria
    ): BannerSearchResultsInterface {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * Retrieve cached banner list for a given store and customer group.
     *
     * Falls back to getList() on cache miss. Cache key is scoped by
     * store_id and customer_group_id so different B2B segments
     * get their own cached result set.
     *
     * @param int $storeId
     * @param int|null $customerGroupId
     * @param SearchCriteriaInterface $searchCriteria
     * @return BannerSearchResultsInterface
     */
    public function getCachedList(
        int $storeId,
        ?int $customerGroupId,
        SearchCriteriaInterface $searchCriteria
    ): BannerSearchResultsInterface {
        $cacheKey = $this->buildCacheKey($storeId, $customerGroupId);
        $cached = $this->cache->load($cacheKey);

        if ($cached !== false) {
            $data = $this->serializer->unserialize($cached);
            $searchResults = $this->searchResultsFactory->create();
            $searchResults->setSearchCriteria($searchCriteria);
            $items = $this->hydrateItems($data['items'] ?? []);
            $searchResults->setItems($items);
            $searchResults->setTotalCount($data['total_count'] ?? 0);

            return $searchResults;
        }

        $result = $this->getList($searchCriteria);

        $itemsData = [];
        foreach ($result->getItems() as $item) {
            $itemsData[] = $this->extractItemData($item);
        }

        $this->cache->save(
            $this->serializer->serialize([
                'items' => $itemsData,
                'total_count' => $result->getTotalCount(),
            ]),
            $cacheKey,
            [BannerCacheType::CACHE_TAG],
            BannerCacheType::DEFAULT_LIFETIME
        );

        return $result;
    }

    /**
     * Build a composite cache key scoped by store and customer group.
     *
     * @param int $storeId
     * @param int|null $customerGroupId
     * @return string
     */
    private function buildCacheKey(
        int $storeId,
        ?int $customerGroupId
    ): string {
        $groupKey = $customerGroupId !== null
            ? (string) $customerGroupId
            : 'all';
        return sprintf(
            '%s_store_%d_group_%s',
            BannerCacheType::TYPE_IDENTIFIER,
            $storeId,
            $groupKey
        );
    }

    /**
     * Extract serialisable data from a banner entity.
     *
     * @param BannerInterface $item
     * @return array<string, mixed>
     */
    private function extractItemData(BannerInterface $item): array
    {
        return [
            BannerInterface::BANNER_ID         => $item->getBannerId(),
            BannerInterface::TITLE             => $item->getTitle(),
            BannerInterface::SUBTITLE          => $item->getSubtitle(),
            BannerInterface::IMAGE_URL         => $item->getImageUrl(),
            BannerInterface::CTA_LABEL         => $item->getCtaLabel(),
            BannerInterface::CTA_LINK          => $item->getCtaLink(),
            BannerInterface::SORT_ORDER        => $item->getSortOrder(),
            BannerInterface::IS_ACTIVE         => $item->getIsActive(),
            BannerInterface::CREATED_AT        => $item->getCreatedAt(),
            BannerInterface::UPDATED_AT        => $item->getUpdatedAt(),
            BannerInterface::STORE_ID          => $item->getStoreId(),
            BannerInterface::CUSTOMER_GROUP_ID => $item->getCustomerGroupId(),
        ];
    }

    /**
     * Hydrate banner objects from cached array data.
     *
     * @param array $itemsData
     * @return BannerInterface[]
     */
    private function hydrateItems(array $itemsData): array
    {
        $items = [];
        foreach ($itemsData as $data) {
            $banner = $this->bannerFactory->create();
            /** @var Banner $banner */
            $banner->setData($data);
            $items[] = $banner;
        }
        return $items;
    }

    /**
     * Invalidate all banner cache entries.
     *
     * Called after save/delete to ensure consistency across
     * all store and customer group cache segments.
     *
     * @return void
     */
    private function invalidateCache(): void
    {
        $this->cache->clean(
            'matchingTag',
            [BannerCacheType::CACHE_TAG]
        );
    }
}
