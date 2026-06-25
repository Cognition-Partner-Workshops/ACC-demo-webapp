<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Model\Badge;

use Example\Storefront\Api\Data\BadgeRuleInterface;

/**
 * Marks products with an ID divisible by 3 as "Sale" (placeholder logic).
 *
 * In a real implementation this would compare the product's `special_price`
 * against its regular `price`.
 */
class SaleRule implements BadgeRuleInterface
{
    /**
     * @inheritDoc
     */
    public function resolve(int $productId): ?string
    {
        return ($productId % 3 === 0) ? 'Sale' : null;
    }
}
