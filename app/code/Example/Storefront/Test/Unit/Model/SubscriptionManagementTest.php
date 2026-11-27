<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

// Stub for the Magento-generated factory (created at di:compile time).
namespace Example\Storefront\Api\Data {

    if (!class_exists(SubscriptionInterfaceFactory::class, false)) {
        /**
         * Factory stub for SubscriptionInterface.
         */
        class SubscriptionInterfaceFactory
        {
            /**
             * Create a new SubscriptionInterface instance.
             *
             * @param mixed[] $data
             * @return SubscriptionInterface|null
             */
            public function create(array $data = []): ?SubscriptionInterface
            {
                return null;
            }
        }
    }
}

namespace Example\Storefront\Test\Unit\Model {

    use Example\Storefront\Api\Data\SubscriptionInterface;
    use Example\Storefront\Api\Data\SubscriptionInterfaceFactory;
    use Example\Storefront\Api\SubscriptionRepositoryInterface;
    use Example\Storefront\Model\SubscriptionManagement;
    use PHPUnit\Framework\MockObject\MockObject;
    use PHPUnit\Framework\TestCase;
    use Psr\Log\LoggerInterface;

    /**
     * Unit tests for SubscriptionManagement.
     */
    class SubscriptionManagementTest extends TestCase
    {
        /**
         * @var SubscriptionRepositoryInterface|MockObject
         */
        private MockObject $repositoryMock;

        /**
         * @var SubscriptionInterfaceFactory|MockObject
         */
        private MockObject $factoryMock;

        /**
         * @var LoggerInterface|MockObject
         */
        private MockObject $loggerMock;

        /**
         * @var SubscriptionManagement
         */
        private SubscriptionManagement $management;

        /**
         * Set up test fixtures.
         *
         * @return void
         */
        protected function setUp(): void
        {
            $this->repositoryMock = $this->createMock(SubscriptionRepositoryInterface::class);
            $this->factoryMock = $this->createMock(SubscriptionInterfaceFactory::class);
            $this->loggerMock = $this->createMock(LoggerInterface::class);

            $this->management = new SubscriptionManagement(
                $this->repositoryMock,
                $this->factoryMock,
                $this->loggerMock
            );
        }

        /**
         * Test creating a subscription with a valid cadence.
         *
         * @return void
         */
        public function testCreateSubscriptionWithValidCadence(): void
        {
            $subscriptionMock = $this->createMock(SubscriptionInterface::class);

            $this->factoryMock->expects($this->once())
                ->method('create')
                ->willReturn($subscriptionMock);

            $subscriptionMock->expects($this->once())
                ->method('setCustomerId')
                ->with(1)
                ->willReturnSelf();
            $subscriptionMock->expects($this->once())
                ->method('setProductId')
                ->with(42)
                ->willReturnSelf();
            $subscriptionMock->expects($this->once())
                ->method('setCadence')
                ->with('monthly')
                ->willReturnSelf();
            $subscriptionMock->expects($this->once())
                ->method('setStatus')
                ->with(SubscriptionInterface::STATUS_ACTIVE)
                ->willReturnSelf();

            $expectedDate = date('Y-m-d', strtotime('+30 days'));
            $subscriptionMock->expects($this->once())
                ->method('setNextDeliveryDate')
                ->with($expectedDate)
                ->willReturnSelf();

            $this->repositoryMock->expects($this->once())
                ->method('save')
                ->with($subscriptionMock)
                ->willReturn($subscriptionMock);

            $result = $this->management->createSubscription(1, 42, 'monthly');

            $this->assertSame($subscriptionMock, $result);
        }

        /**
         * Test that an invalid cadence throws InvalidArgumentException.
         *
         * @return void
         */
        public function testCreateSubscriptionWithInvalidCadenceThrows(): void
        {
            $this->expectException(\InvalidArgumentException::class);
            $this->management->createSubscription(1, 42, 'biweekly');
        }

        /**
         * Test cancelling a subscription.
         *
         * @return void
         */
        public function testCancelSubscription(): void
        {
            $subscriptionMock = $this->createMock(SubscriptionInterface::class);

            $this->repositoryMock->expects($this->once())
                ->method('getById')
                ->with(10)
                ->willReturn($subscriptionMock);

            $subscriptionMock->expects($this->once())
                ->method('setStatus')
                ->with(SubscriptionInterface::STATUS_CANCELLED)
                ->willReturnSelf();

            $this->repositoryMock->expects($this->once())
                ->method('save')
                ->with($subscriptionMock)
                ->willReturn($subscriptionMock);

            $this->assertTrue($this->management->cancelSubscription(10));
        }

        /**
         * Test pausing a subscription.
         *
         * @return void
         */
        public function testPauseSubscription(): void
        {
            $subscriptionMock = $this->createMock(SubscriptionInterface::class);

            $this->repositoryMock->expects($this->once())
                ->method('getById')
                ->with(10)
                ->willReturn($subscriptionMock);

            $subscriptionMock->method('getStatus')
                ->willReturn(SubscriptionInterface::STATUS_ACTIVE);

            $subscriptionMock->expects($this->once())
                ->method('setStatus')
                ->with(SubscriptionInterface::STATUS_PAUSED)
                ->willReturnSelf();

            $this->repositoryMock->expects($this->once())
                ->method('save')
                ->with($subscriptionMock)
                ->willReturn($subscriptionMock);

            $this->assertTrue($this->management->pauseSubscription(10));
        }

        /**
         * Test that pausing a cancelled subscription throws.
         *
         * @return void
         */
        public function testPauseNonActiveSubscriptionThrows(): void
        {
            $subscriptionMock = $this->createMock(SubscriptionInterface::class);

            $this->repositoryMock->expects($this->once())
                ->method('getById')
                ->with(10)
                ->willReturn($subscriptionMock);

            $subscriptionMock->method('getStatus')
                ->willReturn(SubscriptionInterface::STATUS_CANCELLED);

            $this->expectException(\InvalidArgumentException::class);
            $this->management->pauseSubscription(10);
        }

        /**
         * Test resuming a subscription.
         *
         * @return void
         */
        public function testResumeSubscription(): void
        {
            $subscriptionMock = $this->createMock(SubscriptionInterface::class);

            $this->repositoryMock->expects($this->once())
                ->method('getById')
                ->with(10)
                ->willReturn($subscriptionMock);

            $subscriptionMock->method('getStatus')
                ->willReturn(SubscriptionInterface::STATUS_PAUSED);

            $subscriptionMock->expects($this->once())
                ->method('setStatus')
                ->with(SubscriptionInterface::STATUS_ACTIVE)
                ->willReturnSelf();

            $subscriptionMock->expects($this->once())
                ->method('getCadence')
                ->willReturn('weekly');

            $expectedDate = date('Y-m-d', strtotime('+7 days'));
            $subscriptionMock->expects($this->once())
                ->method('setNextDeliveryDate')
                ->with($expectedDate)
                ->willReturnSelf();

            $this->repositoryMock->expects($this->once())
                ->method('save')
                ->with($subscriptionMock)
                ->willReturn($subscriptionMock);

            $this->assertTrue($this->management->resumeSubscription(10));
        }

        /**
         * Test that resuming a cancelled subscription throws.
         *
         * @return void
         */
        public function testResumeNonPausedSubscriptionThrows(): void
        {
            $subscriptionMock = $this->createMock(SubscriptionInterface::class);

            $this->repositoryMock->expects($this->once())
                ->method('getById')
                ->with(10)
                ->willReturn($subscriptionMock);

            $subscriptionMock->method('getStatus')
                ->willReturn(SubscriptionInterface::STATUS_CANCELLED);

            $this->expectException(\InvalidArgumentException::class);
            $this->management->resumeSubscription(10);
        }
    }
}
