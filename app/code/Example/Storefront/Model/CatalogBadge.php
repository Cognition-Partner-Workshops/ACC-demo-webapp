<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Model;

use Example\Storefront\Api\CatalogBadgeInterface;
use Example\Storefront\Api\StockResolverInterface;

/**
 * Resolves storefront product badges based on stock state.
 */
class CatalogBadge implements CatalogBadgeInterface
{
    /**
     * @var StockResolverInterface
     */
    private StockResolverInterface $stockResolver;

    /**
     * @param StockResolverInterface $stockResolver
     */
    public function __construct(StockResolverInterface $stockResolver)
    {
        $this->stockResolver = $stockResolver;
    }

    /**
     * @inheritDoc
     */
    public function getBadgeLabel(string $sku): string
    {
        if ($this->stockResolver->isRecentlyAdded($sku)) {
            return 'New';
        }

        return '';
    }
}
