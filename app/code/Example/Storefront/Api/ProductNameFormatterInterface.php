<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Api;

/**
 * Service contract for normalizing product names for storefront display.
 *
 * @api
 */
interface ProductNameFormatterInterface
{
    /**
     * Clean up a product name for display.
     *
     * Trims leading/trailing whitespace and collapses any run of repeated
     * whitespace inside the name down to a single space.
     *
     * @param string $productName
     * @return string
     */
    public function format(string $productName): string;
}
