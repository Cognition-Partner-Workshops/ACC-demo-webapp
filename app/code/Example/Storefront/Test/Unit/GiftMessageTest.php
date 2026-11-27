<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Test\Unit;

use Example\Storefront\Api\GiftMessageRepositoryInterface;
use Example\Storefront\Model\GiftMessage;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Unit tests for the GiftMessage model.
 */
class GiftMessageTest extends TestCase
{
    /**
     * The trimmed message should be persisted via the repository and logged.
     *
     * @return void
     */
    public function testSavesTrimmedMessageAndLogs(): void
    {
        $repository = $this->createMock(GiftMessageRepositoryInterface::class);
        $logger = $this->createMock(LoggerInterface::class);

        $repository->expects($this->once())
            ->method('save')
            ->with('Happy birthday!');

        $logger->expects($this->once())
            ->method('info')
            ->with('Gift message saved: "Happy birthday!".');

        $giftMessage = new GiftMessage($repository, $logger);
        $giftMessage->saveMessage('  Happy birthday!  ');
    }
}
