<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Model;

use Example\Storefront\Api\ProductNameFormatterInterface;

/**
 * Default product name formatter for the storefront.
 */
class ProductNameFormatter implements ProductNameFormatterInterface
{
    /**
     * @inheritDoc
     */
    public function format(string $productName): string
    {
        $collapsed = preg_replace('/\s+/', ' ', trim($productName));

        return $collapsed ?? '';
    }
}
