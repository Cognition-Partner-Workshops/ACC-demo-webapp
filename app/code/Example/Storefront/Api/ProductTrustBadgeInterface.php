<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Api;

/**
 * Service contract for product trust badge content.
 *
 * @api
 */
interface ProductTrustBadgeInterface
{
    /**
     * Return the trust badge message displayed on the product detail page.
     *
     * @return string
     */
    public function getMessage(): string;

    /**
     * Check whether the trust badge should be visible.
     *
     * @return bool
     */
    public function isEnabled(): bool;
}
