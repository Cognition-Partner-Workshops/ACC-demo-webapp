<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Api;

/**
 * Service contract for cleaning up product names for storefront display.
 *
 * @api
 */
interface ProductNameFormatterInterface
{
    /**
     * Clean a product name for display.
     *
     * Trims the ends and collapses any run of repeated whitespace into one space.
     *
     * @param string $productName
     * @return string
     */
    public function format(string $productName): string;
}
