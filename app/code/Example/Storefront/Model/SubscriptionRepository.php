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
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Repository for auto-replenishment subscriptions.
 */
class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    /**
     * @var SubscriptionFactory
     */
    private SubscriptionFactory $subscriptionFactory;

    /**
     * @var SubscriptionResource
     */
    private SubscriptionResource $resource;

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * @var SearchResultsInterfaceFactory
     */
    private SearchResultsInterfaceFactory $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private CollectionProcessorInterface $collectionProcessor;

    /**
     * @param SubscriptionFactory $subscriptionFactory
     * @param SubscriptionResource $resource
     * @param CollectionFactory $collectionFactory
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        SubscriptionFactory $subscriptionFactory,
        SubscriptionResource $resource,
        CollectionFactory $collectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->subscriptionFactory = $subscriptionFactory;
        $this->resource = $resource;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
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
    public function getList(
        SearchCriteriaInterface $searchCriteria
    ): SearchResultsInterface {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}
