<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Plugin\Catalog;

use Example\Storefront\Model\NameNormalizer;
use Magento\Catalog\Model\Product;

/**
 * Normalizes product display names on the storefront.
 *
 * Uses an interceptor (after-plugin) rather than a class rewrite so core behaviour
 * is extended without overriding it.
 */
class ProductNamePlugin
{
    /**
     * @param NameNormalizer $nameNormalizer
     */
    public function __construct(private readonly NameNormalizer $nameNormalizer)
    {
    }

    /**
     * Normalize the product name returned by the core model.
     *
     * @param Product $subject
     * @param string|null $result
     * @return string|null
     */
    public function afterGetName(Product $subject, ?string $result): ?string
    {
        if ($result === null) {
            return null;
        }

        return $this->nameNormalizer->normalize($result);
    }
}
