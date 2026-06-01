<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Model;

use Example\Storefront\Api\Data\SubscriptionInterface;
use Example\Storefront\Api\SubscriptionManagementInterface;
use Example\Storefront\Api\SubscriptionRepositoryInterface;

/**
 * Management service for subscription business operations.
 */
class SubscriptionManagement implements SubscriptionManagementInterface
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
     * @var SubscriptionFactory
     */
    private SubscriptionFactory $subscriptionFactory;

    /**
     * @var SubscriptionRepositoryInterface
     */
    private SubscriptionRepositoryInterface $subscriptionRepository;

    /**
     * @param SubscriptionFactory $subscriptionFactory
     * @param SubscriptionRepositoryInterface $subscriptionRepository
     */
    public function __construct(
        SubscriptionFactory $subscriptionFactory,
        SubscriptionRepositoryInterface $subscriptionRepository
    ) {
        $this->subscriptionFactory = $subscriptionFactory;
        $this->subscriptionRepository = $subscriptionRepository;
    }

    /**
     * @inheritDoc
     */
    public function createSubscription(
        int $customerId,
        int $productId,
        string $cadence
    ): SubscriptionInterface {
        $subscription = $this->subscriptionFactory->create();
        $subscription->setCustomerId($customerId);
        $subscription->setProductId($productId);
        $subscription->setCadence($cadence);
        $subscription->setNextDeliveryDate($this->calculateNextDeliveryDate($cadence));
        $subscription->setStatus('active');

        return $this->subscriptionRepository->save($subscription);
    }

    /**
     * @inheritDoc
     */
    public function cancelSubscription(int $subscriptionId): bool
    {
        $subscription = $this->subscriptionRepository->getById($subscriptionId);
        $subscription->setStatus('cancelled');
        $this->subscriptionRepository->save($subscription);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function pauseSubscription(int $subscriptionId): bool
    {
        $subscription = $this->subscriptionRepository->getById($subscriptionId);
        $subscription->setStatus('paused');
        $this->subscriptionRepository->save($subscription);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function resumeSubscription(int $subscriptionId): bool
    {
        $subscription = $this->subscriptionRepository->getById($subscriptionId);
        $subscription->setStatus('active');
        $subscription->setNextDeliveryDate(
            $this->calculateNextDeliveryDate($subscription->getCadence())
        );
        $this->subscriptionRepository->save($subscription);

        return true;
    }

    /**
     * Calculate the next delivery date from today based on cadence.
     *
     * @param string|null $cadence
     * @return string
     */
    private function calculateNextDeliveryDate(?string $cadence): string
    {
        $days = self::CADENCE_INTERVALS[$cadence] ?? self::CADENCE_INTERVALS['monthly'];

        return date('Y-m-d', strtotime('+' . $days . ' days'));
    }
}
