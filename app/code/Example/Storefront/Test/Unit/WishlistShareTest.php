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
 * Unit tests for the WishlistShare model.
 */
class WishlistShareTest extends TestCase
{
    /**
     * A valid recipient address should be accepted.
     *
     * @return void
     */
    public function testShareByEmailAcceptsValidAddress(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $share = new WishlistShare($logger);

        $this->assertTrue($share->shareByEmail('marie@example.com'));
    }

    /**
     * A blank or malformed address should be rejected.
     *
     * @return void
     */
    public function testShareByEmailRejectsInvalidAddress(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $share = new WishlistShare($logger);

        $this->assertFalse($share->shareByEmail('   '));
        $this->assertFalse($share->shareByEmail('not-an-email'));
    }

    /**
     * The shareable link is not implemented yet and returns an empty string.
     *
     * @return void
     */
    public function testGetShareUrlReturnsEmptyStringUntilImplemented(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $share = new WishlistShare($logger);

        $this->assertSame('', $share->getShareUrl());
    }
}
