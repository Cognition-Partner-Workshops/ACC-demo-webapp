<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Cron;

use Example\Storefront\Api\SubscriptionRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Psr\Log\LoggerInterface;

/**
 * Cron job that processes due auto-replenishment subscriptions.
 */
class ProcessReplenishments
{
    /**
     * Cadence interval mapping in days.
     */
    private const CADENCE_INTERVALS = [
        'weekly' => 7,
        'monthly' => 30,
        'quarterly' => 90,
    ];

    /**
     * @var SubscriptionRepositoryInterface
     */
    private SubscriptionRepositoryInterface $subscriptionRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param SubscriptionRepositoryInterface $subscriptionRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param LoggerInterface $logger
     */
    public function __construct(
        SubscriptionRepositoryInterface $subscriptionRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        LoggerInterface $logger
    ) {
        $this->subscriptionRepository = $subscriptionRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->logger = $logger;
    }

    /**
     * Process all active subscriptions whose next delivery date is today or earlier.
     *
     * @return void
     */
    public function execute(): void
    {
        $today = date('Y-m-d');

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('status', 'active')
            ->addFilter('next_delivery_date', $today, 'lteq')
            ->create();

        $results = $this->subscriptionRepository->getList($searchCriteria);

        foreach ($results->getItems() as $subscription) {
            $this->logger->info(sprintf(
                'Processing replenishment for subscription %d (product %d, customer %d).',
                $subscription->getSubscriptionId(),
                $subscription->getProductId(),
                $subscription->getCustomerId()
            ));

            // TODO: Place actual order via QuoteManagement (demo module placeholder).

            $cadence = $subscription->getCadence();
            $days = self::CADENCE_INTERVALS[$cadence] ?? self::CADENCE_INTERVALS['monthly'];
            $subscription->setNextDeliveryDate(
                date('Y-m-d', strtotime('+' . $days . ' days'))
            );

            $this->subscriptionRepository->save($subscription);
        }
    }
}
