<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

// Stubs for Magento Framework API classes (not in vendor for this module-only repo).
namespace Magento\Framework\Api {

    if (!interface_exists(SearchCriteriaInterface::class, false)) {
        /**
         * Stub for Magento search criteria interface.
         */
        interface SearchCriteriaInterface
        {
        }
    }

    if (!interface_exists(SearchResultsInterface::class, false)) {
        /**
         * Stub for Magento search results interface.
         */
        interface SearchResultsInterface
        {
            /**
             * Get items.
             *
             * @return mixed[]
             */
            public function getItems(): array;

            /**
             * Set items.
             *
             * @param mixed[] $items
             * @return self
             */
            public function setItems(array $items): self;

            /**
             * Set search criteria.
             *
             * @param SearchCriteriaInterface $searchCriteria
             * @return self
             */
            public function setSearchCriteria(
                SearchCriteriaInterface $searchCriteria
            ): self;

            /**
             * Set total count.
             *
             * @param int $totalCount
             * @return self
             */
            public function setTotalCount(int $totalCount): self;
        }
    }

    if (!class_exists(SearchCriteriaBuilder::class, false)) {
        /**
         * Stub for Magento search criteria builder.
         */
        class SearchCriteriaBuilder
        {
            /**
             * Add a filter.
             *
             * @param string $field
             * @param mixed $value
             * @param string|null $conditionType
             * @return self
             */
            public function addFilter(
                string $field,
                $value,
                ?string $conditionType = null
            ): self {
                return $this;
            }

            /**
             * Build the search criteria.
             *
             * @return SearchCriteriaInterface|null
             */
            public function create(): ?SearchCriteriaInterface
            {
                return null;
            }
        }
    }
}

namespace Example\Storefront\Test\Unit\Cron {

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
     * Unit tests for ProcessReplenishments cron job.
     */
    class ProcessReplenishmentsTest extends TestCase
    {
        /**
         * @var SubscriptionRepositoryInterface|MockObject
         */
        private MockObject $repositoryMock;

        /**
         * @var SearchCriteriaBuilder|MockObject
         */
        private MockObject $searchCriteriaBuilderMock;

        /**
         * @var LoggerInterface|MockObject
         */
        private MockObject $loggerMock;

        /**
         * @var ProcessReplenishments
         */
        private ProcessReplenishments $cron;

        /**
         * Set up test fixtures.
         *
         * @return void
         */
        protected function setUp(): void
        {
            $this->repositoryMock = $this->createMock(
                SubscriptionRepositoryInterface::class
            );
            $this->searchCriteriaBuilderMock = $this->createMock(
                SearchCriteriaBuilder::class
            );
            $this->loggerMock = $this->createMock(LoggerInterface::class);

            $this->cron = new ProcessReplenishments(
                $this->repositoryMock,
                $this->searchCriteriaBuilderMock,
                $this->loggerMock
            );
        }

        /**
         * Test that due subscriptions are processed and dates advanced.
         *
         * @return void
         */
        public function testExecuteProcessesDueSubscriptions(): void
        {
            $searchCriteriaMock = $this->createMock(
                SearchCriteriaInterface::class
            );

            $this->searchCriteriaBuilderMock
                ->method('addFilter')
                ->willReturnSelf();
            $this->searchCriteriaBuilderMock->expects($this->once())
                ->method('create')
                ->willReturn($searchCriteriaMock);

            $subscriptionMock = $this->createMock(
                SubscriptionInterface::class
            );
            $currentDeliveryDate = '2026-05-01';
            $subscriptionMock->method('getSubscriptionId')->willReturn(1);
            $subscriptionMock->method('getCustomerId')->willReturn(100);
            $subscriptionMock->method('getProductId')->willReturn(42);
            $subscriptionMock->method('getCadence')->willReturn('monthly');
            $subscriptionMock->method('getNextDeliveryDate')
                ->willReturn($currentDeliveryDate);

            $expectedDate = date(
                'Y-m-d',
                strtotime('+30 days', strtotime($currentDeliveryDate))
            );
            $subscriptionMock->expects($this->once())
                ->method('setNextDeliveryDate')
                ->with($expectedDate)
                ->willReturnSelf();

            $searchResultsMock = $this->createMock(
                SearchResultsInterface::class
            );
            $searchResultsMock->method('getItems')
                ->willReturn([$subscriptionMock]);

            $this->repositoryMock->expects($this->once())
                ->method('getList')
                ->with($searchCriteriaMock)
                ->willReturn($searchResultsMock);

            $this->repositoryMock->expects($this->once())
                ->method('save')
                ->with($subscriptionMock);

            $this->cron->execute();
        }

        /**
         * Test that no save is called when no subscriptions are due.
         *
         * @return void
         */
        public function testExecuteSkipsWhenNoDueSubscriptions(): void
        {
            $searchCriteriaMock = $this->createMock(
                SearchCriteriaInterface::class
            );

            $this->searchCriteriaBuilderMock
                ->method('addFilter')
                ->willReturnSelf();
            $this->searchCriteriaBuilderMock->method('create')
                ->willReturn($searchCriteriaMock);

            $searchResultsMock = $this->createMock(
                SearchResultsInterface::class
            );
            $searchResultsMock->method('getItems')->willReturn([]);

            $this->repositoryMock->expects($this->once())
                ->method('getList')
                ->willReturn($searchResultsMock);

            $this->repositoryMock->expects($this->never())
                ->method('save');

            $this->cron->execute();
        }

        /**
         * Test that a save failure does not stop processing remaining items.
         *
         * @return void
         */
        public function testExecuteContinuesOnSaveFailure(): void
        {
            $searchCriteriaMock = $this->createMock(
                SearchCriteriaInterface::class
            );

            $this->searchCriteriaBuilderMock
                ->method('addFilter')
                ->willReturnSelf();
            $this->searchCriteriaBuilderMock->method('create')
                ->willReturn($searchCriteriaMock);

            $sub1 = $this->createMock(SubscriptionInterface::class);
            $sub1->method('getSubscriptionId')->willReturn(1);
            $sub1->method('getCustomerId')->willReturn(100);
            $sub1->method('getProductId')->willReturn(42);
            $sub1->method('getCadence')->willReturn('weekly');
            $sub1->method('getNextDeliveryDate')->willReturn('2026-05-01');
            $sub1->method('setNextDeliveryDate')->willReturnSelf();

            $sub2 = $this->createMock(SubscriptionInterface::class);
            $sub2->method('getSubscriptionId')->willReturn(2);
            $sub2->method('getCustomerId')->willReturn(200);
            $sub2->method('getProductId')->willReturn(43);
            $sub2->method('getCadence')->willReturn('monthly');
            $sub2->method('getNextDeliveryDate')->willReturn('2026-05-01');
            $sub2->method('setNextDeliveryDate')->willReturnSelf();

            $searchResultsMock = $this->createMock(
                SearchResultsInterface::class
            );
            $searchResultsMock->method('getItems')
                ->willReturn([$sub1, $sub2]);

            $this->repositoryMock->method('getList')
                ->willReturn($searchResultsMock);

            $this->repositoryMock->method('save')
                ->willReturnCallback(
                    function ($sub) {
                        if ($sub->getSubscriptionId() === 1) {
                            throw new \RuntimeException('DB error');
                        }
                        return $sub;
                    }
                );

            $this->loggerMock->expects($this->once())
                ->method('error');

            $this->cron->execute();
        }
    }
}
