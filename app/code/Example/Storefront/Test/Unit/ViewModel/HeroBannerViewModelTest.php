<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Test\Unit\ViewModel;

use Example\Storefront\Api\BannerRepositoryInterface;
use Example\Storefront\Api\Data\BannerInterface;
use Example\Storefront\Api\Data\BannerSearchResultsInterface;
use Example\Storefront\ViewModel\HeroBannerViewModel;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for HeroBannerViewModel.
 */
class HeroBannerViewModelTest extends TestCase
{
    /**
     * @var BannerRepositoryInterface|MockObject
     */
    private $bannerRepository;

    /**
     * @var SearchCriteriaBuilder|MockObject
     */
    private $searchCriteriaBuilder;

    /**
     * @var SortOrderBuilder|MockObject
     */
    private $sortOrderBuilder;

    /**
     * @var FilterBuilder|MockObject
     */
    private $filterBuilder;

    /**
     * @var StoreManagerInterface|MockObject
     */
    private $storeManager;

    /**
     * @var HeroBannerViewModel
     */
    private HeroBannerViewModel $viewModel;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->bannerRepository = $this->createMock(
            BannerRepositoryInterface::class
        );
        $this->searchCriteriaBuilder = $this->createMock(
            SearchCriteriaBuilder::class
        );
        $this->sortOrderBuilder = $this->createMock(SortOrderBuilder::class);
        $this->filterBuilder = $this->createMock(FilterBuilder::class);
        $this->storeManager = $this->createMock(StoreManagerInterface::class);

        $store = $this->createMock(StoreInterface::class);
        $store->method('getId')->willReturn(1);
        $this->storeManager->method('getStore')->willReturn($store);

        $this->viewModel = new HeroBannerViewModel(
            $this->bannerRepository,
            $this->searchCriteriaBuilder,
            $this->sortOrderBuilder,
            $this->filterBuilder,
            $this->storeManager
        );
    }

    /**
     * Test getActiveBanners returns items when banners exist.
     *
     * @return void
     */
    public function testGetActiveBannersReturnsItems(): void
    {
        $banner = $this->createMock(BannerInterface::class);
        $sortOrder = $this->createMock(SortOrder::class);
        $searchCriteria = $this->createMock(SearchCriteriaInterface::class);
        $searchResults = $this->createMock(
            BannerSearchResultsInterface::class
        );

        $this->sortOrderBuilder->expects($this->once())
            ->method('setField')
            ->with(BannerInterface::SORT_ORDER)
            ->willReturnSelf();
        $this->sortOrderBuilder->expects($this->once())
            ->method('setAscendingDirection')
            ->willReturnSelf();
        $this->sortOrderBuilder->expects($this->once())
            ->method('create')
            ->willReturn($sortOrder);

        $this->searchCriteriaBuilder->method('addFilter')
            ->willReturnSelf();
        $this->searchCriteriaBuilder->expects($this->once())
            ->method('addSortOrder')
            ->with($sortOrder)
            ->willReturnSelf();
        $this->searchCriteriaBuilder->expects($this->once())
            ->method('create')
            ->willReturn($searchCriteria);

        $this->bannerRepository->expects($this->once())
            ->method('getList')
            ->with($searchCriteria)
            ->willReturn($searchResults);

        $searchResults->method('getItems')->willReturn([$banner]);

        $result = $this->viewModel->getActiveBanners();
        $this->assertCount(1, $result);
        $this->assertSame($banner, $result[0]);
    }

    /**
     * Test getActiveBanners returns empty array when no banners.
     *
     * @return void
     */
    public function testGetActiveBannersReturnsEmptyArray(): void
    {
        $sortOrder = $this->createMock(SortOrder::class);
        $searchCriteria = $this->createMock(SearchCriteriaInterface::class);
        $searchResults = $this->createMock(
            BannerSearchResultsInterface::class
        );

        $this->sortOrderBuilder->method('setField')->willReturnSelf();
        $this->sortOrderBuilder->method('setAscendingDirection')
            ->willReturnSelf();
        $this->sortOrderBuilder->method('create')->willReturn($sortOrder);

        $this->searchCriteriaBuilder->method('addFilter')->willReturnSelf();
        $this->searchCriteriaBuilder->method('addSortOrder')
            ->willReturnSelf();
        $this->searchCriteriaBuilder->method('create')
            ->willReturn($searchCriteria);

        $this->bannerRepository->method('getList')
            ->willReturn($searchResults);

        $searchResults->method('getItems')->willReturn([]);

        $this->assertSame([], $this->viewModel->getActiveBanners());
    }
}
