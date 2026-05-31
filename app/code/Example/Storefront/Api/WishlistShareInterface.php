<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Api;

/**
 * Service contract for sharing a customer's wishlist.
 *
 * Sharing supports two channels: sending the wishlist to an email address, and
 * exposing a public shareable link. Only the email channel is implemented for
 * now; the shareable-link channel is part of the contract for upcoming work.
 *
 * @api
 */
interface WishlistShareInterface
{
    /**
     * Share the current wishlist with the given email address.
     *
     * @param string $email
     * @return bool True when the wishlist was successfully shared.
     */
    public function shareByEmail(string $email): bool;

    /**
     * Build a public, shareable URL for the current wishlist.
     *
     * @return string The absolute URL that renders the shared wishlist.
     */
    public function getShareUrl(): string;
}
