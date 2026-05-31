<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Model;

use Example\Storefront\Api\Data\SubscriptionInterface;
use Example\Storefront\Api\Data\SubscriptionInterfaceFactory;
use Example\Storefront\Api\SubscriptionManagementInterface;
use Example\Storefront\Api\SubscriptionRepositoryInterface;
use Psr\Log\LoggerInterface;

/**
 * Management service for auto-replenishment subscriptions.
 */
class SubscriptionManagement implements SubscriptionManagementInterface
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
     * @var SubscriptionInterfaceFactory
     */
    private SubscriptionInterfaceFactory $subscriptionFactory;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param SubscriptionRepositoryInterface $subscriptionRepository
     * @param SubscriptionInterfaceFactory $subscriptionFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        SubscriptionRepositoryInterface $subscriptionRepository,
        SubscriptionInterfaceFactory $subscriptionFactory,
        LoggerInterface $logger
    ) {
        $this->subscriptionRepository = $subscriptionRepository;
        $this->subscriptionFactory = $subscriptionFactory;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function createSubscription(
        int $customerId,
        int $productId,
        string $cadence
    ): SubscriptionInterface {
        if (!isset(self::CADENCE_DAYS[$cadence])) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Invalid cadence "%s". Allowed values: %s',
                    $cadence,
                    implode(', ', array_keys(self::CADENCE_DAYS))
                )
            );
        }

        $subscription = $this->subscriptionFactory->create();
        $subscription->setCustomerId($customerId);
        $subscription->setProductId($productId);
        $subscription->setCadence($cadence);
        $subscription->setStatus(SubscriptionInterface::STATUS_ACTIVE);
        $subscription->setNextDeliveryDate(
            $this->computeNextDeliveryDate($cadence)
        );

        $subscription = $this->subscriptionRepository->save($subscription);

        $this->logger->info(
            sprintf(
                'Subscription created: customer=%d, product=%d, cadence=%s',
                $customerId,
                $productId,
                $cadence
            )
        );

        return $subscription;
    }

    /**
     * @inheritDoc
     */
    public function cancelSubscription(int $subscriptionId): bool
    {
        $subscription = $this->subscriptionRepository->getById($subscriptionId);
        $subscription->setStatus(SubscriptionInterface::STATUS_CANCELLED);
        $this->subscriptionRepository->save($subscription);
        return true;
    }

    /**
     * @inheritDoc
     */
    public function pauseSubscription(int $subscriptionId): bool
    {
        $subscription = $this->subscriptionRepository->getById($subscriptionId);
        if ($subscription->getStatus() !== SubscriptionInterface::STATUS_ACTIVE) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Subscription %d cannot be paused because its status is "%s", not "active".',
                    $subscriptionId,
                    $subscription->getStatus()
                )
            );
        }
        $subscription->setStatus(SubscriptionInterface::STATUS_PAUSED);
        $this->subscriptionRepository->save($subscription);
        return true;
    }

    /**
     * @inheritDoc
     */
    public function resumeSubscription(int $subscriptionId): bool
    {
        $subscription = $this->subscriptionRepository->getById($subscriptionId);
        if ($subscription->getStatus() !== SubscriptionInterface::STATUS_PAUSED) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Subscription %d cannot be resumed because its status is "%s", not "paused".',
                    $subscriptionId,
                    $subscription->getStatus()
                )
            );
        }
        $subscription->setStatus(SubscriptionInterface::STATUS_ACTIVE);
        $subscription->setNextDeliveryDate(
            $this->computeNextDeliveryDate($subscription->getCadence())
        );
        $this->subscriptionRepository->save($subscription);
        return true;
    }

    /**
     * Compute the next delivery date from today based on cadence.
     *
     * @param string $cadence
     * @return string
     */
    private function computeNextDeliveryDate(string $cadence): string
    {
        $days = $this->getCadenceDays($cadence);
        return date('Y-m-d', strtotime(sprintf('+%d days', $days)));
    }

    /**
     * Get the number of interval days for a given cadence.
     *
     * @param string $cadence
     * @return int
     */
    private function getCadenceDays(string $cadence): int
    {
        return self::CADENCE_DAYS[$cadence];
    }
}
