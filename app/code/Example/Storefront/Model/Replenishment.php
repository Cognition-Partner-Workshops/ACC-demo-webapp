<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Model;

use Example\Storefront\Api\SubscriptionRepositoryInterface;
use Psr\Log\LoggerInterface;

/**
 * Service for scheduling product replenishments.
 */
class Replenishment
{
    /**
     * @var SubscriptionRepositoryInterface
     */
    private SubscriptionRepositoryInterface $subscriptionRepository;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param SubscriptionRepositoryInterface $subscriptionRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        SubscriptionRepositoryInterface $subscriptionRepository,
        LoggerInterface $logger
    ) {
        $this->subscriptionRepository = $subscriptionRepository;
        $this->logger = $logger;
    }

    /**
     * Schedule a replenishment for the given product.
     *
     * @param string $sku
     * @param int $intervalDays
     * @return void
     */
    public function scheduleReplenishment(string $sku, int $intervalDays): void
    {
        $this->logger->info(
            sprintf(
                'Replenishment scheduled: sku=%s, interval=%d days',
                $sku,
                $intervalDays
            )
        );
    }
}
