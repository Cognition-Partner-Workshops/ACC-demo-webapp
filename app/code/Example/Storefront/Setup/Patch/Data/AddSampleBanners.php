<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

/**
 * Data patch to seed sample hero banners.
 */
class AddSampleBanners implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private ModuleDataSetupInterface $moduleDataSetup;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(ModuleDataSetupInterface $moduleDataSetup)
    {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * Seed the banner table with sample rows.
     *
     * @return void
     */
    public function apply(): void
    {
        $connection = $this->moduleDataSetup->getConnection();
        $tableName  = $this->moduleDataSetup->getTable('example_storefront_banner');

        $banners = [
            [
                'title'      => 'Discover Our Brands',
                'subtitle'   => 'Quality products loved around the world',
                'image_url'  => '/media/banner/hero-1.jpg',
                'cta_label'  => 'Explore Now',
                'cta_link'   => '/brands',
                'sort_order' => 1,
                'is_active'  => 1,
                'store_id'   => 0,
            ],
            [
                'title'      => 'Nutrition & Wellness',
                'subtitle'   => 'Committed to a healthier future for all',
                'image_url'  => '/media/banner/hero-2.jpg',
                'cta_label'  => 'Learn More',
                'cta_link'   => '/nutrition',
                'sort_order' => 2,
                'is_active'  => 1,
                'store_id'   => 0,
            ],
            [
                'title'      => 'Sustainability Promise',
                'subtitle'   => 'Good for you, good for the planet',
                'image_url'  => '/media/banner/hero-3.jpg',
                'cta_label'  => 'Our Commitment',
                'cta_link'   => '/sustainability',
                'sort_order' => 3,
                'is_active'  => 1,
                'store_id'   => 0,
            ],
        ];

        foreach ($banners as $banner) {
            $connection->insert($tableName, $banner);
        }
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAliases(): array
    {
        return [];
    }
}
