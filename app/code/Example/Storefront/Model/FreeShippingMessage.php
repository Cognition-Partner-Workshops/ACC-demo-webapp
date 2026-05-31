<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Model;

use Example\Storefront\Api\FreeShippingMessageInterface;

/**
 * Owns the free-shipping threshold copy shown in the storefront banner.
 */
class FreeShippingMessage implements FreeShippingMessageInterface
{
    /**
     * Order subtotal (in whole currency units) required to qualify for free shipping.
     */
    private const THRESHOLD = 75;

    /**
     * @inheritDoc
     */
    public function getThreshold(): int
    {
        return self::THRESHOLD;
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return sprintf('Free shipping on orders over $%d', $this->getThreshold());
    }
}
