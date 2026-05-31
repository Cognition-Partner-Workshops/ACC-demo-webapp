<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Api;

/**
 * Service contract for calculating loyalty points earned on a product.
 *
 * @api
 */
interface LoyaltyPointsCalculatorInterface
{
    /**
     * Calculate the loyalty points earned for the given product price.
     *
     * @param float $price
     * @return int
     */
    public function calculate(float $price): int;
}
