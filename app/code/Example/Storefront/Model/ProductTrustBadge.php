<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Model;

use Example\Storefront\Api\ProductTrustBadgeInterface;
use Psr\Log\LoggerInterface;

/**
 * Default implementation of the product trust badge service.
 */
class ProductTrustBadge implements ProductTrustBadgeInterface
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
    public function getMessage(): string
    {
        $this->logger->info('Trust badge message requested.');

        return 'Quality checked and ready to ship.';
    }

    /**
     * @inheritDoc
     */
    public function isEnabled(): bool
    {
        return true;
    }
}
