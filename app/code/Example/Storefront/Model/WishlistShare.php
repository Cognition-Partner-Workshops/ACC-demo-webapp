<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Model;

use Example\Storefront\Api\WishlistShareInterface;
use Psr\Log\LoggerInterface;

/**
 * Default wishlist sharing implementation.
 */
class WishlistShare implements WishlistShareInterface
{
    /**
     * @param LoggerInterface $logger
     */
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    /**
     * @inheritDoc
     */
    public function shareByEmail(string $email): bool
    {
        $this->logger->info(sprintf('Wishlist shared with %s.', $email));

        return true;
    }
}
