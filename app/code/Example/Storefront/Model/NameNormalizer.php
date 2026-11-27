<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Model;

/**
 * Normalizes display strings (trim + collapse internal whitespace).
 */
class NameNormalizer
{
    /**
     * Collapse runs of whitespace and trim the given value.
     *
     * @param string $value
     * @return string
     */
    public function normalize(string $value): string
    {
        $collapsed = preg_replace('/\s+/', ' ', $value);

        return trim($collapsed ?? $value);
    }
}
