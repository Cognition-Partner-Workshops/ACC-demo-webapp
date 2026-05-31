<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Api;

/**
 * Service contract for sharing a customer's wishlist.
 *
 * Sharing supports two channels: sending the wishlist to a recipient by email
 * (available now) and generating a public, shareable link (planned).
 *
 * @api
 */
interface WishlistShareInterface
{
    /**
     * Share the current wishlist with the given recipient by email.
     *
     * @param string $email
     * @return bool
     */
    public function shareByEmail(string $email): bool;

    /**
     * Build a public, shareable URL for the current wishlist.
     *
     * @return string
     */
    public function getShareUrl(): string;
}
