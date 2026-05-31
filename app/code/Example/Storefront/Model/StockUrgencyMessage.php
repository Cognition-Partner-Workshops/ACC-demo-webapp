<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Model;

use Example\Storefront\Api\StockUrgencyMessageInterface;
use Psr\Log\LoggerInterface;

/**
 * Default builder for storefront stock-urgency messages.
 *
 * The "low stock" threshold is injected via di.xml so merchandisers can tune
 * how early the urgency nudge appears without touching code.
 */
class StockUrgencyMessage implements StockUrgencyMessageInterface
{
    /**
     * Message shown when a product cannot be purchased.
     */
    private const OUT_OF_STOCK_MESSAGE = 'Out of stock';

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
     * @param int $lowStockThreshold Inclusive unit count at or below which the low-stock nudge shows.
     */
    public function __construct(LoggerInterface $logger, int $lowStockThreshold = 5)
    {
        $this->logger = $logger;
        $this->lowStockThreshold = max(0, $lowStockThreshold);
    }

    /**
     * @inheritDoc
     */
    public function getMessage(float $salableQuantity, bool $isInStock): string
    {
        $units = (int) floor($salableQuantity);

        if (!$isInStock || $units <= 0) {
            return self::OUT_OF_STOCK_MESSAGE;
        }

        if ($units <= $this->lowStockThreshold) {
            $this->logger->info(sprintf('Low-stock urgency shown for %d unit(s).', $units));

            return sprintf('Only %d left — order soon!', $units);
        }

        return '';
    }
}
