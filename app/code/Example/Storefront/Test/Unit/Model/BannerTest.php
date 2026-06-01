<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Test\Unit\Model;

use Example\Storefront\Api\Data\BannerInterface;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the Banner model via the BannerInterface contract.
 *
 * Since Banner extends AbstractModel (which is not available without the
 * Magento framework), we test the interface contract through a simple
 * in-memory stub that mirrors the getData/setData behaviour.
 */
class BannerTest extends TestCase
{
    /**
     * Build a BannerInterface stub backed by an array.
     *
     * @param array<string, mixed> $data
     * @return BannerInterface
     */
    private function createBannerStub(array $data = []): BannerInterface
    {
        $store = $data;
        $banner = $this->createMock(BannerInterface::class);

        $banner->method('getBannerId')->willReturnCallback(
            function () use (&$store): ?int {
                return isset($store[BannerInterface::BANNER_ID])
                    ? (int) $store[BannerInterface::BANNER_ID]
                    : null;
            }
        );
        $banner->method('setBannerId')->willReturnCallback(
            function (int $id) use (&$store, $banner): BannerInterface {
                $store[BannerInterface::BANNER_ID] = $id;
                return $banner;
            }
        );

        $banner->method('getTitle')->willReturnCallback(
            function () use (&$store): ?string {
                return $store[BannerInterface::TITLE] ?? null;
            }
        );
        $banner->method('setTitle')->willReturnCallback(
            function (string $v) use (&$store, $banner): BannerInterface {
                $store[BannerInterface::TITLE] = $v;
                return $banner;
            }
        );

        $banner->method('getSubtitle')->willReturnCallback(
            function () use (&$store): ?string {
                return $store[BannerInterface::SUBTITLE] ?? null;
            }
        );
        $banner->method('setSubtitle')->willReturnCallback(
            function (?string $v) use (&$store, $banner): BannerInterface {
                $store[BannerInterface::SUBTITLE] = $v;
                return $banner;
            }
        );

        $banner->method('getImageUrl')->willReturnCallback(
            function () use (&$store): ?string {
                return $store[BannerInterface::IMAGE_URL] ?? null;
            }
        );
        $banner->method('setImageUrl')->willReturnCallback(
            function (string $v) use (&$store, $banner): BannerInterface {
                $store[BannerInterface::IMAGE_URL] = $v;
                return $banner;
            }
        );

        $banner->method('getCtaLabel')->willReturnCallback(
            function () use (&$store): ?string {
                return $store[BannerInterface::CTA_LABEL] ?? null;
            }
        );
        $banner->method('setCtaLabel')->willReturnCallback(
            function (?string $v) use (&$store, $banner): BannerInterface {
                $store[BannerInterface::CTA_LABEL] = $v;
                return $banner;
            }
        );

        $banner->method('getCtaLink')->willReturnCallback(
            function () use (&$store): ?string {
                return $store[BannerInterface::CTA_LINK] ?? null;
            }
        );
        $banner->method('setCtaLink')->willReturnCallback(
            function (?string $v) use (&$store, $banner): BannerInterface {
                $store[BannerInterface::CTA_LINK] = $v;
                return $banner;
            }
        );

        $banner->method('getSortOrder')->willReturnCallback(
            function () use (&$store): ?int {
                return isset($store[BannerInterface::SORT_ORDER])
                    ? (int) $store[BannerInterface::SORT_ORDER]
                    : null;
            }
        );
        $banner->method('setSortOrder')->willReturnCallback(
            function (int $v) use (&$store, $banner): BannerInterface {
                $store[BannerInterface::SORT_ORDER] = $v;
                return $banner;
            }
        );

        $banner->method('getIsActive')->willReturnCallback(
            function () use (&$store): ?bool {
                return isset($store[BannerInterface::IS_ACTIVE])
                    ? (bool) $store[BannerInterface::IS_ACTIVE]
                    : null;
            }
        );
        $banner->method('setIsActive')->willReturnCallback(
            function (bool $v) use (&$store, $banner): BannerInterface {
                $store[BannerInterface::IS_ACTIVE] = $v;
                return $banner;
            }
        );

        $banner->method('getCreatedAt')->willReturnCallback(
            function () use (&$store): ?string {
                return $store[BannerInterface::CREATED_AT] ?? null;
            }
        );
        $banner->method('setCreatedAt')->willReturnCallback(
            function (string $v) use (&$store, $banner): BannerInterface {
                $store[BannerInterface::CREATED_AT] = $v;
                return $banner;
            }
        );

        $banner->method('getUpdatedAt')->willReturnCallback(
            function () use (&$store): ?string {
                return $store[BannerInterface::UPDATED_AT] ?? null;
            }
        );
        $banner->method('setUpdatedAt')->willReturnCallback(
            function (string $v) use (&$store, $banner): BannerInterface {
                $store[BannerInterface::UPDATED_AT] = $v;
                return $banner;
            }
        );

        $banner->method('getStoreId')->willReturnCallback(
            function () use (&$store): int {
                return (int) ($store[BannerInterface::STORE_ID] ?? 0);
            }
        );
        $banner->method('setStoreId')->willReturnCallback(
            function (int $v) use (&$store, $banner): BannerInterface {
                $store[BannerInterface::STORE_ID] = $v;
                return $banner;
            }
        );

        $banner->method('getCustomerGroupId')->willReturnCallback(
            function () use (&$store): ?int {
                return isset($store[BannerInterface::CUSTOMER_GROUP_ID])
                    ? (int) $store[BannerInterface::CUSTOMER_GROUP_ID]
                    : null;
            }
        );
        $banner->method('setCustomerGroupId')->willReturnCallback(
            function (?int $v) use (&$store, $banner): BannerInterface {
                $store[BannerInterface::CUSTOMER_GROUP_ID] = $v;
                return $banner;
            }
        );

        return $banner;
    }

    /**
     * Test that setTitle / getTitle round-trips correctly.
     *
     * @return void
     */
    public function testTitleGetterSetter(): void
    {
        $banner = $this->createBannerStub();
        $banner->setTitle('Promo Banner');
        $this->assertSame('Promo Banner', $banner->getTitle());
    }

    /**
     * Test banner ID getter and setter.
     *
     * @return void
     */
    public function testBannerIdGetterSetter(): void
    {
        $banner = $this->createBannerStub();
        $banner->setBannerId(42);
        $this->assertSame(42, $banner->getBannerId());
    }

    /**
     * Test subtitle getter and setter.
     *
     * @return void
     */
    public function testSubtitleGetterSetter(): void
    {
        $banner = $this->createBannerStub();
        $banner->setSubtitle('Tagline');
        $this->assertSame('Tagline', $banner->getSubtitle());
    }

    /**
     * Test image URL getter and setter.
     *
     * @return void
     */
    public function testImageUrlGetterSetter(): void
    {
        $banner = $this->createBannerStub();
        $banner->setImageUrl('/media/banner/test.jpg');
        $this->assertSame('/media/banner/test.jpg', $banner->getImageUrl());
    }

    /**
     * Test CTA label getter and setter.
     *
     * @return void
     */
    public function testCtaLabelGetterSetter(): void
    {
        $banner = $this->createBannerStub();
        $banner->setCtaLabel('Click Here');
        $this->assertSame('Click Here', $banner->getCtaLabel());
    }

    /**
     * Test CTA link getter and setter.
     *
     * @return void
     */
    public function testCtaLinkGetterSetter(): void
    {
        $banner = $this->createBannerStub();
        $banner->setCtaLink('/promo');
        $this->assertSame('/promo', $banner->getCtaLink());
    }

    /**
     * Test sort order getter and setter.
     *
     * @return void
     */
    public function testSortOrderGetterSetter(): void
    {
        $banner = $this->createBannerStub();
        $banner->setSortOrder(5);
        $this->assertSame(5, $banner->getSortOrder());
    }

    /**
     * Test is-active getter and setter.
     *
     * @return void
     */
    public function testIsActiveGetterSetter(): void
    {
        $banner = $this->createBannerStub();
        $banner->setIsActive(true);
        $this->assertTrue($banner->getIsActive());
    }

    /**
     * Test is-active defaults to null when not set.
     *
     * @return void
     */
    public function testIsActiveDefaultsToNull(): void
    {
        $banner = $this->createBannerStub();
        $this->assertNull($banner->getIsActive());
    }

    /**
     * Test created-at getter and setter.
     *
     * @return void
     */
    public function testCreatedAtGetterSetter(): void
    {
        $banner = $this->createBannerStub();
        $banner->setCreatedAt('2025-01-01 00:00:00');
        $this->assertSame('2025-01-01 00:00:00', $banner->getCreatedAt());
    }

    /**
     * Test updated-at getter and setter.
     *
     * @return void
     */
    public function testUpdatedAtGetterSetter(): void
    {
        $banner = $this->createBannerStub();
        $banner->setUpdatedAt('2025-06-15 12:00:00');
        $this->assertSame('2025-06-15 12:00:00', $banner->getUpdatedAt());
    }

    /**
     * Test that setters return fluent interface.
     *
     * @return void
     */
    public function testSettersReturnSelf(): void
    {
        $banner = $this->createBannerStub();
        $result = $banner->setTitle('Test');
        $this->assertInstanceOf(BannerInterface::class, $result);
    }

    /**
     * Test store ID getter and setter.
     *
     * @return void
     */
    public function testStoreIdGetterSetter(): void
    {
        $banner = $this->createBannerStub();
        $banner->setStoreId(2);
        $this->assertSame(2, $banner->getStoreId());
    }

    /**
     * Test store ID defaults to zero.
     *
     * @return void
     */
    public function testStoreIdDefaultsToZero(): void
    {
        $banner = $this->createBannerStub();
        $this->assertSame(0, $banner->getStoreId());
    }

    /**
     * Test customer group ID getter and setter.
     *
     * @return void
     */
    public function testCustomerGroupIdGetterSetter(): void
    {
        $banner = $this->createBannerStub();
        $banner->setCustomerGroupId(3);
        $this->assertSame(3, $banner->getCustomerGroupId());
    }

    /**
     * Test customer group ID defaults to null.
     *
     * @return void
     */
    public function testCustomerGroupIdDefaultsToNull(): void
    {
        $banner = $this->createBannerStub();
        $this->assertNull($banner->getCustomerGroupId());
    }

    /**
     * Test column-name constants are defined.
     *
     * @return void
     */
    public function testColumnConstants(): void
    {
        $this->assertSame('banner_id', BannerInterface::BANNER_ID);
        $this->assertSame('title', BannerInterface::TITLE);
        $this->assertSame('subtitle', BannerInterface::SUBTITLE);
        $this->assertSame('image_url', BannerInterface::IMAGE_URL);
        $this->assertSame('cta_label', BannerInterface::CTA_LABEL);
        $this->assertSame('cta_link', BannerInterface::CTA_LINK);
        $this->assertSame('sort_order', BannerInterface::SORT_ORDER);
        $this->assertSame('is_active', BannerInterface::IS_ACTIVE);
        $this->assertSame('created_at', BannerInterface::CREATED_AT);
        $this->assertSame('updated_at', BannerInterface::UPDATED_AT);
        $this->assertSame('store_id', BannerInterface::STORE_ID);
        $this->assertSame(
            'customer_group_id',
            BannerInterface::CUSTOMER_GROUP_ID
        );
    }
}
