<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Model;

use Example\Storefront\Api\LoyaltyPointsCalculatorInterface;
use Psr\Log\LoggerInterface;

/**
 * Default loyalty-points calculator for the storefront.
 */
class LoyaltyPointsCalculator implements LoyaltyPointsCalculatorInterface
{
    /**
     * Points awarded per whole dollar spent.
     */
    private const POINTS_PER_DOLLAR = 1;

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
    public function calculate(float $price): int
    {
        $points = (int) floor($price * self::POINTS_PER_DOLLAR);

        $this->logger->info(sprintf('Loyalty points calculated: %d for price %.2f.', $points, $price));

        return $points;
    }
}
