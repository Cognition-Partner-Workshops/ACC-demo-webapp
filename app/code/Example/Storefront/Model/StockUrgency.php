<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Model;

use Example\Storefront\Api\StockUrgencyInterface;
use Psr\Log\LoggerInterface;

/**
 * Default low-stock urgency messaging for the storefront.
 */
class StockUrgency implements StockUrgencyInterface
{
    /**
     * Quantity at or below which a low-stock message is shown.
     */
    private const LOW_STOCK_THRESHOLD = 5;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    /**
     * @inheritDoc
     */
    public function getUrgencyMessage(int $qtyRemaining): string
    {
        if ($qtyRemaining <= 0) {
            return 'Out of stock';
        }

        if ($qtyRemaining <= self::LOW_STOCK_THRESHOLD) {
            $this->logger->info(sprintf('Low-stock urgency shown for qty %d.', $qtyRemaining));

            return sprintf('Only %d left — order soon!', $qtyRemaining);
        }

        return '';
    }
}
