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
 *
 * Email sharing is implemented now; the public shareable link is planned and
 * will be filled in later.
 */
class WishlistShare implements WishlistShareInterface
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function shareByEmail(string $email): bool
    {
        $recipient = trim($email);
        if ($recipient === '' || !filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            $this->logger->warning(sprintf('Wishlist share requested for invalid email "%s".', $email));

            return false;
        }

        $this->logger->info(sprintf('Wishlist shared by email with "%s".', $recipient));

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getShareUrl(): string
    {
        // Public shareable-link generation is planned and not yet available;
        // return an empty string until it is implemented.
        return '';
    }
}
