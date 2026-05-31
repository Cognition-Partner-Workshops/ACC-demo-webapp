<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Model;

use Example\Storefront\Api\ProductNameFormatterInterface;

/**
 * Default product name formatter for the storefront.
 *
 * Trims leading/trailing whitespace and collapses any run of repeated
 * whitespace characters into a single space so names display cleanly.
 */
class ProductNameFormatter implements ProductNameFormatterInterface
{
    /**
     * @inheritDoc
     */
    public function format(string $productName): string
    {
        $collapsed = preg_replace('/\s+/u', ' ', $productName);
        if ($collapsed === null) {
            $collapsed = $productName;
        }

        return trim($collapsed);
    }
}
