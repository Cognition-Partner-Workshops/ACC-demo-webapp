<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Api;

use Example\Storefront\Api\Data\SubscriptionInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Repository service contract for auto-replenishment subscriptions.
 *
 * @api
 */
interface SubscriptionRepositoryInterface
{
    /**
     * Load a subscription by its ID.
     *
     * @param int $subscriptionId
     * @return SubscriptionInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $subscriptionId): SubscriptionInterface;

    /**
     * Persist a subscription entity.
     *
     * @param SubscriptionInterface $subscription
     * @return SubscriptionInterface
     * @throws CouldNotSaveException
     */
    public function save(SubscriptionInterface $subscription): SubscriptionInterface;

    /**
     * Delete a subscription entity.
     *
     * @param SubscriptionInterface $subscription
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(SubscriptionInterface $subscription): bool;

    /**
     * Retrieve subscriptions matching the given search criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(
        SearchCriteriaInterface $searchCriteria
    ): SearchResultsInterface;
}
