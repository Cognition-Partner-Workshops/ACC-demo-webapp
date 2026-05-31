<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Block;

use Example\Storefront\Api\FreeShippingMessageInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Storefront block that renders the free-shipping threshold banner.
 */
class FreeShippingBanner extends Template
{
    /**
     * @var FreeShippingMessageInterface
     */
    private FreeShippingMessageInterface $freeShippingMessage;

    /**
     * @param Context $context
     * @param FreeShippingMessageInterface $freeShippingMessage
     * @param array $data
     */
    public function __construct(
        Context $context,
        FreeShippingMessageInterface $freeShippingMessage,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->freeShippingMessage = $freeShippingMessage;
    }

    /**
     * Get the customer-facing free-shipping banner message.
     *
     * @return string
     */
    public function getBannerMessage(): string
    {
        return $this->freeShippingMessage->getMessage();
    }
}
