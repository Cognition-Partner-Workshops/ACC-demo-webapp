<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Model\Badge;

use Example\Storefront\Api\Data\BadgeRuleInterface;

/**
 * Marks products with an ID divisible by 2 as "New Arrival" (placeholder logic).
 *
 * In a real implementation this would check the product's `news_from_date` /
 * `news_to_date` attributes against the current date.
 */
class NewArrivalRule implements BadgeRuleInterface
{
    /**
     * @inheritDoc
     */
    public function resolve(int $productId): ?string
    {
        return ($productId % 2 === 0) ? 'New Arrival' : null;
    }
}
