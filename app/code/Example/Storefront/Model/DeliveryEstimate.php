<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Model;

use Example\Storefront\Api\DeliveryEstimateInterface;
use Psr\Log\LoggerInterface;

/**
 * Default delivery estimate provider for the storefront.
 *
 * For this first version the estimate is based on a fixed business-day
 * (Monday–Friday) lead time. The projected delivery date skips weekends, and
 * the human-readable estimate reports the number of business days until it.
 */
class DeliveryEstimate implements DeliveryEstimateInterface
{
    /**
     * Number of business days the warehouse needs to dispatch and deliver.
     */
    private const DEFAULT_LEAD_BUSINESS_DAYS = 5;

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
    public function getEstimate(string $sku): string
    {
        $businessDays = self::DEFAULT_LEAD_BUSINESS_DAYS;
        $deliveryDate = $this->projectDeliveryDate($businessDays);

        $this->logger->info(sprintf(
            'Delivery estimate for SKU "%s": %d business day(s), arriving by %s.',
            $sku,
            $businessDays,
            $deliveryDate->format('Y-m-d')
        ));

        return sprintf('Arrives in %d business %s', $businessDays, $businessDays === 1 ? 'day' : 'days');
    }

    /**
     * Project the delivery date by advancing the given number of business days.
     *
     * Weekends (Saturday/Sunday) are skipped, so the result is always exactly
     * $businessDays business days ahead of today regardless of the start day.
     *
     * @param int $businessDays
     * @return \DateTimeImmutable
     */
    private function projectDeliveryDate(int $businessDays): \DateTimeImmutable
    {
        $counted = 0;
        $cursor = new \DateTimeImmutable('today');

        while ($counted < $businessDays) {
            $cursor = $cursor->modify('+1 day');
            if ((int) $cursor->format('N') < 6) {
                $counted++;
            }
        }

        return $cursor;
    }
}
