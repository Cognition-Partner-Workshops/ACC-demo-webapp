<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Cron;

use Example\Storefront\Api\Data\SubscriptionInterface;
use Example\Storefront\Api\SubscriptionRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Psr\Log\LoggerInterface;

/**
 * Cron job to process due auto-replenishment subscriptions.
 */
class ProcessReplenishments
{
    /**
     * Mapping of cadence values to interval days.
     */
    private const CADENCE_DAYS = [
        SubscriptionInterface::CADENCE_WEEKLY => 7,
        SubscriptionInterface::CADENCE_MONTHLY => 30,
        SubscriptionInterface::CADENCE_QUARTERLY => 90,
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
     * Process subscriptions that are due for replenishment.
     *
     * @return void
     */
    public function execute(): void
    {
        $today = date('Y-m-d');
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('status', SubscriptionInterface::STATUS_ACTIVE)
            ->addFilter('next_delivery_date', $today, 'lteq')
            ->create();

        $results = $this->subscriptionRepository->getList($searchCriteria);

        foreach ($results->getItems() as $subscription) {
            try {
                $this->logger->info(
                    sprintf(
                        'Replenishment due: subscription=%d, customer=%d, product=%d',
                        $subscription->getSubscriptionId(),
                        $subscription->getCustomerId(),
                        $subscription->getProductId()
                    )
                );

                $cadence = $subscription->getCadence();
                $days = self::CADENCE_DAYS[$cadence] ?? 30;
                $baseDate = strtotime($subscription->getNextDeliveryDate());
                $subscription->setNextDeliveryDate(
                    date(
                        'Y-m-d',
                        strtotime(sprintf('+%d days', $days), $baseDate)
                    )
                );
                $this->subscriptionRepository->save($subscription);
            } catch (\Exception $e) {
                $this->logger->error(
                    sprintf(
                        'Failed to process subscription %d: %s',
                        $subscription->getSubscriptionId(),
                        $e->getMessage()
                    )
                );
            }
        }
    }
}
