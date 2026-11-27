<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Test\Unit;

use Example\Storefront\Model\ResourceModel\Subscription as SubscriptionResource;
use Example\Storefront\Model\ResourceModel\Subscription\CollectionFactory;
use Example\Storefront\Model\Subscription;
use Example\Storefront\Model\SubscriptionFactory;
use Example\Storefront\Model\SubscriptionRepository;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the SubscriptionRepository.
 */
class SubscriptionRepositoryTest extends TestCase
{
    /**
     * @var SubscriptionResource|MockObject
     */
    private MockObject $resource;

    /**
     * @var SubscriptionFactory|MockObject
     */
    private MockObject $subscriptionFactory;

    /**
     * @var CollectionFactory|MockObject
     */
    private MockObject $collectionFactory;

    /**
     * @var SearchResultsInterfaceFactory|MockObject
     */
    private MockObject $searchResultsFactory;

    /**
     * @var SubscriptionRepository
     */
    private SubscriptionRepository $repository;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->resource = $this->createMock(SubscriptionResource::class);
        $this->subscriptionFactory = $this->createMock(SubscriptionFactory::class);
        $this->collectionFactory = $this->createMock(CollectionFactory::class);
        $this->searchResultsFactory = $this->createMock(SearchResultsInterfaceFactory::class);

        $this->repository = new SubscriptionRepository(
            $this->resource,
            $this->subscriptionFactory,
            $this->collectionFactory,
            $this->searchResultsFactory
        );
    }

    /**
     * Test getById returns subscription when found.
     *
     * @return void
     */
    public function testGetByIdReturnsSubscription(): void
    {
        $subscription = $this->createMock(Subscription::class);
        $subscription->method('getSubscriptionId')->willReturn(1);

        $this->subscriptionFactory->method('create')->willReturn($subscription);

        $this->resource->expects($this->once())
            ->method('load')
            ->with($subscription, 1);

        $result = $this->repository->getById(1);
        $this->assertSame($subscription, $result);
    }

    /**
     * Test getById throws NoSuchEntityException when not found.
     *
     * @return void
     */
    public function testGetByIdThrowsExceptionWhenNotFound(): void
    {
        $subscription = $this->createMock(Subscription::class);
        $subscription->method('getSubscriptionId')->willReturn(null);

        $this->subscriptionFactory->method('create')->willReturn($subscription);

        $this->expectException(NoSuchEntityException::class);

        $this->repository->getById(999);
    }

    /**
     * Test save delegates to resource model.
     *
     * @return void
     */
    public function testSaveDelegatesToResource(): void
    {
        $subscription = $this->createMock(Subscription::class);

        $this->resource->expects($this->once())
            ->method('save')
            ->with($subscription);

        $result = $this->repository->save($subscription);
        $this->assertSame($subscription, $result);
    }

    /**
     * Test save throws CouldNotSaveException on failure.
     *
     * @return void
     */
    public function testSaveThrowsExceptionOnFailure(): void
    {
        $subscription = $this->createMock(Subscription::class);

        $this->resource->method('save')
            ->willThrowException(new \Exception('DB error'));

        $this->expectException(CouldNotSaveException::class);

        $this->repository->save($subscription);
    }

    /**
     * Test delete delegates to resource model.
     *
     * @return void
     */
    public function testDeleteDelegatesToResource(): void
    {
        $subscription = $this->createMock(Subscription::class);

        $this->resource->expects($this->once())
            ->method('delete')
            ->with($subscription);

        $this->assertTrue($this->repository->delete($subscription));
    }

    /**
     * Test delete throws CouldNotDeleteException on failure.
     *
     * @return void
     */
    public function testDeleteThrowsExceptionOnFailure(): void
    {
        $subscription = $this->createMock(Subscription::class);

        $this->resource->method('delete')
            ->willThrowException(new \Exception('DB error'));

        $this->expectException(CouldNotDeleteException::class);

        $this->repository->delete($subscription);
    }
}
