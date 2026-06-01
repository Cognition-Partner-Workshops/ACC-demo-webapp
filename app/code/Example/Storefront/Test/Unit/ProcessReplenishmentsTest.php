<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Test\Unit;

use Example\Storefront\Api\Data\SubscriptionInterface;
use Example\Storefront\Api\SubscriptionRepositoryInterface;
use Example\Storefront\Cron\ProcessReplenishments;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Unit tests for the ProcessReplenishments cron job.
 */
class ProcessReplenishmentsTest extends TestCase
{
    /**
     * @var SubscriptionRepositoryInterface|MockObject
     */
    private MockObject $subscriptionRepository;

    /**
     * @var SearchCriteriaBuilder|MockObject
     */
    private MockObject $searchCriteriaBuilder;

    /**
     * @var LoggerInterface|MockObject
     */
    private MockObject $logger;

    /**
     * @var ProcessReplenishments
     */
    private ProcessReplenishments $cron;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->subscriptionRepository = $this->createMock(SubscriptionRepositoryInterface::class);
        $this->searchCriteriaBuilder = $this->createMock(SearchCriteriaBuilder::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->cron = new ProcessReplenishments(
            $this->subscriptionRepository,
            $this->searchCriteriaBuilder,
            $this->logger
        );
    }

    /**
     * Test that execute processes due subscriptions and advances the delivery date.
     *
     * @return void
     */
    public function testExecuteProcessesDueSubscriptions(): void
    {
        $searchCriteria = $this->createMock(SearchCriteriaInterface::class);

        $this->searchCriteriaBuilder->method('addFilter')->willReturnSelf();
        $this->searchCriteriaBuilder->method('create')->willReturn($searchCriteria);

        $subscription = $this->createMock(SubscriptionInterface::class);
        $subscription->method('getSubscriptionId')->willReturn(1);
        $subscription->method('getProductId')->willReturn(101);
        $subscription->method('getCustomerId')->willReturn(42);
        $subscription->method('getCadence')->willReturn('weekly');

        $searchResults = $this->createMock(SearchResultsInterface::class);
        $searchResults->method('getItems')->willReturn([$subscription]);

        $this->subscriptionRepository->method('getList')
            ->with($searchCriteria)
            ->willReturn($searchResults);

        $this->logger->expects($this->once())
            ->method('info')
            ->with($this->stringContains('Processing replenishment'));

        $subscription->expects($this->once())
            ->method('setNextDeliveryDate')
            ->with($this->isType('string'))
            ->willReturnSelf();

        $this->subscriptionRepository->expects($this->once())
            ->method('save')
            ->with($subscription);

        $this->cron->execute();
    }

    /**
     * Test that execute does nothing when there are no due subscriptions.
     *
     * @return void
     */
    public function testExecuteDoesNothingWhenNoDueSubscriptions(): void
    {
        $searchCriteria = $this->createMock(SearchCriteriaInterface::class);

        $this->searchCriteriaBuilder->method('addFilter')->willReturnSelf();
        $this->searchCriteriaBuilder->method('create')->willReturn($searchCriteria);

        $searchResults = $this->createMock(SearchResultsInterface::class);
        $searchResults->method('getItems')->willReturn([]);

        $this->subscriptionRepository->method('getList')
            ->willReturn($searchResults);

        $this->logger->expects($this->never())->method('info');
        $this->subscriptionRepository->expects($this->never())->method('save');

        $this->cron->execute();
    }
}
