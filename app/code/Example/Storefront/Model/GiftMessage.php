<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Model;

use Example\Storefront\Api\GiftMessageRepositoryInterface;
use Psr\Log\LoggerInterface;

/**
 * Persists a customer's short gift message for an order.
 */
class GiftMessage
{
    /**
     * @var GiftMessageRepositoryInterface
     */
    private GiftMessageRepositoryInterface $repository;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param GiftMessageRepositoryInterface $repository
     * @param LoggerInterface $logger
     */
    public function __construct(
        GiftMessageRepositoryInterface $repository,
        LoggerInterface $logger
    ) {
        $this->repository = $repository;
        $this->logger = $logger;
    }

    /**
     * Save a gift message via the platform repository contract and log the save.
     *
     * @param string $message
     * @return void
     */
    public function saveMessage(string $message): void
    {
        $trimmed = trim($message);

        $this->repository->save($trimmed);

        $this->logger->info(sprintf('Gift message saved: "%s".', $trimmed));
    }
}
