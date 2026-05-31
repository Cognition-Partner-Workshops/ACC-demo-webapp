<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Test\Unit;

use Example\Storefront\Model\WishlistShare;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Unit tests for the WishlistShare model (email sharing channel).
 */
class WishlistShareTest extends TestCase
{
    /**
     * A valid email address should be accepted and reported as shared.
     *
     * @return void
     */
    public function testShareByEmailWithValidAddressSucceeds(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

        $share = new WishlistShare($logger);

        $this->assertTrue($share->shareByEmail('shopper@example.com'));
    }

    /**
     * A blank address should be rejected without sharing.
     *
     * @return void
     */
    public function testShareByEmailWithBlankAddressFails(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('warning');

        $share = new WishlistShare($logger);

        $this->assertFalse($share->shareByEmail('   '));
    }

    /**
     * A malformed address should be rejected without sharing.
     *
     * @return void
     */
    public function testShareByEmailWithMalformedAddressFails(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('warning');

        $share = new WishlistShare($logger);

        $this->assertFalse($share->shareByEmail('not-an-email'));
    }
}
