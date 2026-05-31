<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Test\Unit;

use Example\Storefront\Model\FreeShippingMessage;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the FreeShippingMessage model.
 */
class FreeShippingMessageTest extends TestCase
{
    /**
     * The threshold should be the expected $75 free-shipping minimum.
     *
     * @return void
     */
    public function testThresholdIsSeventyFive(): void
    {
        $message = new FreeShippingMessage();

        $this->assertSame(75, $message->getThreshold());
    }

    /**
     * The banner copy should advertise the $75 free-shipping threshold.
     *
     * @return void
     */
    public function testMessageAdvertisesSeventyFiveDollarThreshold(): void
    {
        $message = new FreeShippingMessage();

        $this->assertSame('Free shipping on orders over $75', $message->getMessage());
    }
}
