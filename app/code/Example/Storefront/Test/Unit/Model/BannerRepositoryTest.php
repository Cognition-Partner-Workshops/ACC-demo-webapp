<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Test\Unit\Model;

use Example\Storefront\Api\Data\BannerInterface;
use Example\Storefront\Api\Data\BannerSearchResultsInterface;
use Example\Storefront\Api\Data\BannerSearchResultsInterfaceFactory;
use Example\Storefront\Model\Banner;
use Example\Storefront\Model\BannerFactory;
use Example\Storefront\Model\BannerRepository;
use Example\Storefront\Model\Cache\BannerCacheType;
use Example\Storefront\Model\ResourceModel\Banner as BannerResource;
use Example\Storefront\Model\ResourceModel\Banner\Collection;
use Example\Storefront\Model\ResourceModel\Banner\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\SerializerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for BannerRepository.
 */
class BannerRepositoryTest extends TestCase
{
    /**
     * @var BannerResource|MockObject
     */
    private $resource;

    /**
     * @var BannerFactory|MockObject
     */
    private $bannerFactory;

    /**
     * @var CollectionFactory|MockObject
     */
    private $collectionFactory;

    /**
     * @var BannerSearchResultsInterfaceFactory|MockObject
     */
    private $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface|MockObject
     */
    private $collectionProcessor;

    /**
     * @var SerializerInterface|MockObject
     */
    private $serializer;

    /**
     * @var BannerCacheType|MockObject
     */
    private $cache;

    /**
     * @var BannerRepository
     */
    private BannerRepository $repository;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->resource = $this->createMock(BannerResource::class);
        $this->bannerFactory = $this->createMock(BannerFactory::class);
        $this->collectionFactory = $this->createMock(CollectionFactory::class);
        $this->searchResultsFactory = $this->createMock(
            BannerSearchResultsInterfaceFactory::class
        );
        $this->collectionProcessor = $this->createMock(
            CollectionProcessorInterface::class
        );
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->cache = $this->createMock(BannerCacheType::class);

        $this->repository = new BannerRepository(
            $this->resource,
            $this->bannerFactory,
            $this->collectionFactory,
            $this->searchResultsFactory,
            $this->collectionProcessor,
            $this->serializer,
            $this->cache
        );
    }

    /**
     * Test getById returns the banner when it exists.
     *
     * @return void
     */
    public function testGetByIdReturnsBanner(): void
    {
        $banner = $this->createMock(Banner::class);
        $banner->method('getBannerId')->willReturn(1);

        $this->bannerFactory->method('create')->willReturn($banner);
        $this->resource->expects($this->once())
            ->method('load')
            ->with($banner, 1);

        $result = $this->repository->getById(1);
        $this->assertSame($banner, $result);
    }

    /**
     * Test getById returns cached banner from identity map.
     *
     * @return void
     */
    public function testGetByIdReturnsFromIdentityMap(): void
    {
        $banner = $this->createMock(Banner::class);
        $banner->method('getBannerId')->willReturn(1);

        $this->bannerFactory->method('create')->willReturn($banner);
        $this->resource->expects($this->once())
            ->method('load')
            ->with($banner, 1);

        $this->repository->getById(1);
        $result = $this->repository->getById(1);
        $this->assertSame($banner, $result);
    }

    /**
     * Test getById throws NoSuchEntityException for missing banner.
     *
     * @return void
     */
    public function testGetByIdThrowsWhenNotFound(): void
    {
        $banner = $this->createMock(Banner::class);
        $banner->method('getBannerId')->willReturn(null);

        $this->bannerFactory->method('create')->willReturn($banner);

        $this->expectException(NoSuchEntityException::class);
        $this->repository->getById(999);
    }

    /**
     * Test save delegates to the resource model and returns the banner.
     *
     * @return void
     */
    public function testSaveReturnsBanner(): void
    {
        $banner = $this->createMock(Banner::class);
        $banner->method('getBannerId')->willReturn(1);

        $this->resource->expects($this->once())
            ->method('save')
            ->with($banner);

        $this->cache->expects($this->once())
            ->method('clean');

        $result = $this->repository->save($banner);
        $this->assertSame($banner, $result);
    }

    /**
     * Test save wraps exceptions in CouldNotSaveException.
     *
     * @return void
     */
    public function testSaveThrowsCouldNotSaveException(): void
    {
        $banner = $this->createMock(Banner::class);

        $this->resource->method('save')
            ->willThrowException(new \Exception('DB error'));

        $this->expectException(CouldNotSaveException::class);
        $this->repository->save($banner);
    }

    /**
     * Test delete delegates to the resource model and returns true.
     *
     * @return void
     */
    public function testDeleteReturnsTrue(): void
    {
        $banner = $this->createMock(Banner::class);
        $banner->method('getBannerId')->willReturn(1);

        $this->resource->expects($this->once())
            ->method('delete')
            ->with($banner);

        $this->cache->expects($this->once())
            ->method('clean');

        $this->assertTrue($this->repository->delete($banner));
    }

    /**
     * Test delete wraps exceptions in CouldNotDeleteException.
     *
     * @return void
     */
    public function testDeleteThrowsCouldNotDeleteException(): void
    {
        $banner = $this->createMock(Banner::class);

        $this->resource->method('delete')
            ->willThrowException(new \Exception('DB error'));

        $this->expectException(CouldNotDeleteException::class);
        $this->repository->delete($banner);
    }

    /**
     * Test getList uses the collection processor and returns search results.
     *
     * @return void
     */
    public function testGetListReturnsSearchResults(): void
    {
        $searchCriteria = $this->createMock(SearchCriteriaInterface::class);
        $collection = $this->createMock(Collection::class);
        $searchResults = $this->createMock(
            BannerSearchResultsInterface::class
        );

        $this->collectionFactory->method('create')->willReturn($collection);
        $this->collectionProcessor->expects($this->once())
            ->method('process')
            ->with($searchCriteria, $collection);

        $collection->method('getItems')->willReturn([]);
        $collection->method('getSize')->willReturn(0);

        $this->searchResultsFactory->method('create')
            ->willReturn($searchResults);

        $searchResults->expects($this->once())
            ->method('setSearchCriteria')
            ->with($searchCriteria);
        $searchResults->expects($this->once())
            ->method('setItems')
            ->with([]);
        $searchResults->expects($this->once())
            ->method('setTotalCount')
            ->with(0);

        $result = $this->repository->getList($searchCriteria);
        $this->assertSame($searchResults, $result);
    }

    /**
     * Test getCachedList returns from cache on hit.
     *
     * @return void
     */
    public function testGetCachedListReturnsCacheHit(): void
    {
        $searchCriteria = $this->createMock(SearchCriteriaInterface::class);
        $searchResults = $this->createMock(
            BannerSearchResultsInterface::class
        );
        $banner = $this->createMock(Banner::class);

        $cachedData = [
            'items' => [
                [
                    'banner_id' => 1,
                    'title' => 'Test',
                    'store_id' => 0,
                ],
            ],
            'total_count' => 1,
        ];

        $this->cache->method('load')
            ->willReturn('serialized');
        $this->serializer->method('unserialize')
            ->with('serialized')
            ->willReturn($cachedData);

        $this->bannerFactory->method('create')->willReturn($banner);
        $this->searchResultsFactory->method('create')
            ->willReturn($searchResults);

        $searchResults->expects($this->once())
            ->method('setSearchCriteria')
            ->with($searchCriteria);
        $searchResults->expects($this->once())
            ->method('setTotalCount')
            ->with(1);

        $result = $this->repository->getCachedList(0, null, $searchCriteria);
        $this->assertSame($searchResults, $result);
    }

    /**
     * Test getCachedList populates cache on miss.
     *
     * @return void
     */
    public function testGetCachedListPopulatesCacheOnMiss(): void
    {
        $searchCriteria = $this->createMock(SearchCriteriaInterface::class);
        $collection = $this->createMock(Collection::class);
        $searchResults = $this->createMock(
            BannerSearchResultsInterface::class
        );

        $this->cache->method('load')->willReturn(false);

        $this->collectionFactory->method('create')->willReturn($collection);
        $this->collectionProcessor->method('process');
        $collection->method('getItems')->willReturn([]);
        $collection->method('getSize')->willReturn(0);

        $this->searchResultsFactory->method('create')
            ->willReturn($searchResults);
        $searchResults->method('getItems')->willReturn([]);
        $searchResults->method('getTotalCount')->willReturn(0);

        $this->serializer->expects($this->once())
            ->method('serialize');
        $this->cache->expects($this->once())
            ->method('save');

        $result = $this->repository->getCachedList(1, null, $searchCriteria);
        $this->assertSame($searchResults, $result);
    }
}
