<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Model;

use Example\Storefront\Api\WishlistShareInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Psr\Log\LoggerInterface;

/**
 * Default wishlist sharing implementation for the storefront.
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
            $this->logger->warning(
                sprintf('Wishlist share aborted: invalid email "%s".', $email)
            );

            return false;
        }

        $this->logger->info(
            sprintf('Wishlist shared by email with "%s".', $recipient)
        );

        return true;
    }

    /**
     * @inheritDoc
     *
     * @throws LocalizedException Always, until the shareable-link channel is implemented.
     */
    public function getShareUrl(): string
    {
        throw new LocalizedException(
            new Phrase('Public shareable wishlist links are not yet available.')
        );
    }
}
