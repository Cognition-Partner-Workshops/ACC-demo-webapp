<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Test\Unit\Setup\Patch\Data;

use Example\Storefront\Setup\Patch\Data\AddSampleBanners;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the AddSampleBanners data patch.
 */
class AddSampleBannersTest extends TestCase
{
    /**
     * @var ModuleDataSetupInterface|MockObject
     */
    private $moduleDataSetup;

    /**
     * @var AdapterInterface|MockObject
     */
    private $connection;

    /**
     * @var AddSampleBanners
     */
    private AddSampleBanners $patch;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->moduleDataSetup = $this->createMock(
            ModuleDataSetupInterface::class
        );
        $this->connection = $this->createMock(AdapterInterface::class);

        $this->moduleDataSetup->method('getConnection')
            ->willReturn($this->connection);
        $this->moduleDataSetup->method('getTable')
            ->with('example_storefront_banner')
            ->willReturn('example_storefront_banner');

        $this->patch = new AddSampleBanners($this->moduleDataSetup);
    }

    /**
     * Test getDependencies returns empty array.
     *
     * @return void
     */
    public function testGetDependenciesReturnsEmptyArray(): void
    {
        $this->assertSame([], AddSampleBanners::getDependencies());
    }

    /**
     * Test getAliases returns empty array.
     *
     * @return void
     */
    public function testGetAliasesReturnsEmptyArray(): void
    {
        $this->assertSame([], $this->patch->getAliases());
    }

    /**
     * Test apply inserts three sample banners.
     *
     * @return void
     */
    public function testApplyInsertsThreeBanners(): void
    {
        $this->connection->expects($this->exactly(3))
            ->method('insert')
            ->with(
                'example_storefront_banner',
                $this->callback(function (array $data): bool {
                    return isset(
                        $data['title'],
                        $data['image_url'],
                        $data['sort_order']
                    );
                })
            );

        $this->patch->apply();
    }
}
