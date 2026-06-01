<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Model;

use Example\Storefront\Api\Data\SubscriptionInterface;
use Example\Storefront\Api\SubscriptionRepositoryInterface;
use Example\Storefront\Model\ResourceModel\Subscription as SubscriptionResource;
use Example\Storefront\Model\ResourceModel\Subscription\CollectionFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Repository implementation for subscription CRUD operations.
 */
class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    /**
     * @var SubscriptionResource
     */
    private SubscriptionResource $resource;

    /**
     * @var SubscriptionFactory
     */
    private SubscriptionFactory $subscriptionFactory;

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * @var SearchResultsInterfaceFactory
     */
    private SearchResultsInterfaceFactory $searchResultsFactory;

    /**
     * @param SubscriptionResource $resource
     * @param SubscriptionFactory $subscriptionFactory
     * @param CollectionFactory $collectionFactory
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     */
    public function __construct(
        SubscriptionResource $resource,
        SubscriptionFactory $subscriptionFactory,
        CollectionFactory $collectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory
    ) {
        $this->resource = $resource;
        $this->subscriptionFactory = $subscriptionFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $subscriptionId): SubscriptionInterface
    {
        $subscription = $this->subscriptionFactory->create();
        $this->resource->load($subscription, $subscriptionId);

        if (!$subscription->getSubscriptionId()) {
            throw new NoSuchEntityException(
                __('Subscription with ID "%1" does not exist.', $subscriptionId)
            );
        }

        return $subscription;
    }

    /**
     * @inheritDoc
     */
    public function save(SubscriptionInterface $subscription): SubscriptionInterface
    {
        try {
            /** @var Subscription $subscription */
            $this->resource->save($subscription);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save subscription: %1', $exception->getMessage()),
                $exception
            );
        }

        return $subscription;
    }

    /**
     * @inheritDoc
     */
    public function delete(SubscriptionInterface $subscription): bool
    {
        try {
            /** @var Subscription $subscription */
            $this->resource->delete($subscription);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __('Could not delete subscription: %1', $exception->getMessage()),
                $exception
            );
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface
    {
        $collection = $this->collectionFactory->create();

        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                $collection->addFieldToFilter(
                    $filter->getField(),
                    [$filter->getConditionType() => $filter->getValue()]
                );
            }
        }

        /** @var SearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}
