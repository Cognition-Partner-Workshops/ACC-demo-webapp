<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Model;

use DateTimeImmutable;
use Example\Storefront\Api\DeliveryEstimateInterface;
use Psr\Log\LoggerInterface;

/**
 * Default delivery-estimate builder for the storefront.
 *
 * For this first version the estimate is based on the number of business days
 * (Monday–Friday) between today and the standard delivery date.
 */
class DeliveryEstimate implements DeliveryEstimateInterface
{
    /**
     * Standard delivery lead time, expressed in calendar days.
     */
    private const LEAD_TIME_DAYS = 5;

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
        $businessDays = $this->countBusinessDays(self::LEAD_TIME_DAYS);

        $this->logger->info(
            sprintf('Delivery estimate for SKU "%s": %d business day(s).', trim($sku), $businessDays)
        );

        return sprintf('Arrives in %d business day%s', $businessDays, $businessDays === 1 ? '' : 's');
    }

    /**
     * Count the business days (Monday–Friday) within the given calendar window.
     *
     * @param int $leadTimeDays
     * @return int
     */
    private function countBusinessDays(int $leadTimeDays): int
    {
        $today = new DateTimeImmutable('today');
        $businessDays = 0;

        for ($offset = 1; $offset <= $leadTimeDays; $offset++) {
            $day = $today->modify(sprintf('+%d day', $offset));
            $weekday = (int) $day->format('N');
            if ($weekday < 6) {
                $businessDays++;
            }
        }

        return $businessDays;
    }
}
