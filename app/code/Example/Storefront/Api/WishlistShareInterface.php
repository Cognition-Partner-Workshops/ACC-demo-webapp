<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Api;

/**
 * Service contract for sharing a storefront wishlist.
 *
 * @api
 */
interface WishlistShareInterface
{
    /**
     * Share the wishlist with the given email address.
     *
     * @param string $email
     * @return bool
     */
    public function shareByEmail(string $email): bool;

    /**
     * Return the public share URL for the wishlist.
     *
     * @return string
     */
    public function getShareUrl(): string;
}
