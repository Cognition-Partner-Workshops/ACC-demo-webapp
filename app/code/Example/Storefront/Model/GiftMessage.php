<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Model;

use Example\Storefront\Api\GiftMessageInterface;
use Example\Storefront\Api\GiftMessageRepositoryInterface;
use Psr\Log\LoggerInterface;

/**
 * Persists a customer's gift message via the platform gift-message repository.
 */
class GiftMessage implements GiftMessageInterface
{
    /**
     * @var GiftMessageRepositoryInterface
     */
    private GiftMessageRepositoryInterface $giftMessageRepository;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param GiftMessageRepositoryInterface $giftMessageRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        GiftMessageRepositoryInterface $giftMessageRepository,
        LoggerInterface $logger
    ) {
        $this->giftMessageRepository = $giftMessageRepository;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function saveMessage(string $message): void
    {
        $note = trim($message);

        $this->giftMessageRepository->save($note);

        $this->logger->info(sprintf('Gift message saved: "%s".', $note));
    }
}
