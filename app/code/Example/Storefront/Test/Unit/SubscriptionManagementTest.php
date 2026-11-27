<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Test\Unit;

use Example\Storefront\Api\Data\SubscriptionInterface;
use Example\Storefront\Api\SubscriptionRepositoryInterface;
use Example\Storefront\Model\Subscription;
use Example\Storefront\Model\SubscriptionFactory;
use Example\Storefront\Model\SubscriptionManagement;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the SubscriptionManagement service.
 */
class SubscriptionManagementTest extends TestCase
{
    /**
     * @var SubscriptionFactory|MockObject
     */
    private MockObject $subscriptionFactory;

    /**
     * @var SubscriptionRepositoryInterface|MockObject
     */
    private MockObject $subscriptionRepository;

    /**
     * @var SubscriptionManagement
     */
    private SubscriptionManagement $management;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->subscriptionFactory = $this->createMock(SubscriptionFactory::class);
        $this->subscriptionRepository = $this->createMock(SubscriptionRepositoryInterface::class);

        $this->management = new SubscriptionManagement(
            $this->subscriptionFactory,
            $this->subscriptionRepository
        );
    }

    /**
     * Test that createSubscription sets the correct fields and saves.
     *
     * @return void
     */
    public function testCreateSubscriptionSetsFieldsAndSaves(): void
    {
        $subscription = $this->createMock(Subscription::class);

        $this->subscriptionFactory->method('create')->willReturn($subscription);

        $subscription->expects($this->once())
            ->method('setCustomerId')
            ->with(42)
            ->willReturnSelf();

        $subscription->expects($this->once())
            ->method('setProductId')
            ->with(101)
            ->willReturnSelf();

        $subscription->expects($this->once())
            ->method('setCadence')
            ->with('monthly')
            ->willReturnSelf();

        $subscription->expects($this->once())
            ->method('setNextDeliveryDate')
            ->with($this->isType('string'))
            ->willReturnSelf();

        $subscription->expects($this->once())
            ->method('setStatus')
            ->with('active')
            ->willReturnSelf();

        $this->subscriptionRepository->expects($this->once())
            ->method('save')
            ->with($subscription)
            ->willReturn($subscription);

        $result = $this->management->createSubscription(42, 101, 'monthly');
        $this->assertSame($subscription, $result);
    }

    /**
     * Test that cancelSubscription changes status to cancelled.
     *
     * @return void
     */
    public function testCancelSubscriptionChangesStatus(): void
    {
        $subscription = $this->createMock(Subscription::class);

        $this->subscriptionRepository->method('getById')
            ->with(1)
            ->willReturn($subscription);

        $subscription->expects($this->once())
            ->method('setStatus')
            ->with('cancelled')
            ->willReturnSelf();

        $this->subscriptionRepository->expects($this->once())
            ->method('save')
            ->with($subscription)
            ->willReturn($subscription);

        $this->assertTrue($this->management->cancelSubscription(1));
    }

    /**
     * Test that pauseSubscription changes status to paused.
     *
     * @return void
     */
    public function testPauseSubscriptionChangesStatus(): void
    {
        $subscription = $this->createMock(Subscription::class);

        $this->subscriptionRepository->method('getById')
            ->with(2)
            ->willReturn($subscription);

        $subscription->expects($this->once())
            ->method('setStatus')
            ->with('paused')
            ->willReturnSelf();

        $this->subscriptionRepository->expects($this->once())
            ->method('save')
            ->with($subscription)
            ->willReturn($subscription);

        $this->assertTrue($this->management->pauseSubscription(2));
    }

    /**
     * Test that resumeSubscription sets status active and recalculates date.
     *
     * @return void
     */
    public function testResumeSubscriptionSetsActiveAndRecalculatesDate(): void
    {
        $subscription = $this->createMock(Subscription::class);

        $this->subscriptionRepository->method('getById')
            ->with(3)
            ->willReturn($subscription);

        $subscription->method('getCadence')->willReturn('weekly');

        $subscription->expects($this->once())
            ->method('setStatus')
            ->with('active')
            ->willReturnSelf();

        $subscription->expects($this->once())
            ->method('setNextDeliveryDate')
            ->with($this->isType('string'))
            ->willReturnSelf();

        $this->subscriptionRepository->expects($this->once())
            ->method('save')
            ->with($subscription)
            ->willReturn($subscription);

        $this->assertTrue($this->management->resumeSubscription(3));
    }
}
