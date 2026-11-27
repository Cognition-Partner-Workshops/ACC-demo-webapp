<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Model;

use Example\Storefront\Api\StockUrgencyMessageInterface;
use Psr\Log\LoggerInterface;

/**
 * Default low-stock urgency messaging for the storefront.
 *
 * Encourages shoppers to act when a product is almost sold out and clearly
 * flags products that are no longer available.
 */
class StockUrgencyMessage implements StockUrgencyMessageInterface
{
    /**
     * Quantity at or below which the low-stock urgency message is shown.
     */
    public const DEFAULT_LOW_STOCK_THRESHOLD = 5;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var int
     */
    private int $lowStockThreshold;

    /**
     * @param LoggerInterface $logger
     * @param int $lowStockThreshold
     */
    public function __construct(
        LoggerInterface $logger,
        int $lowStockThreshold = self::DEFAULT_LOW_STOCK_THRESHOLD
    ) {
        $this->logger = $logger;
        $this->lowStockThreshold = $lowStockThreshold;
    }

    /**
     * @inheritDoc
     */
    public function getMessage(int $qtyRemaining): string
    {
        if ($qtyRemaining <= 0) {
            return 'Out of stock';
        }

        if ($qtyRemaining <= $this->lowStockThreshold) {
            $this->logger->info(sprintf('Low-stock urgency message shown for qty %d.', $qtyRemaining));

            return sprintf('Only %d left — order soon!', $qtyRemaining);
        }

        return '';
    }
}
