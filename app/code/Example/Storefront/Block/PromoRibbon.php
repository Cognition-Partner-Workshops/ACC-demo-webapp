<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Block;

use Magento\Framework\View\Element\Template;

/**
 * Block that renders the site-wide promotional ribbon shown on every storefront page.
 */
class PromoRibbon extends Template
{
    /**
     * Default promotional message rendered in the ribbon.
     *
     * @var string
     */
    private const DEFAULT_MESSAGE = 'Demo Storefront: Fast fulfillment on featured products.';

    /**
     * Return the promotional message to display in the ribbon.
     *
     * The message can be overridden from layout XML via the `message` argument; otherwise the
     * built-in demo-safe default is used.
     *
     * @return string
     */
    public function getMessage(): string
    {
        $message = $this->getData('message');
        if (is_string($message) && trim($message) !== '') {
            return $message;
        }

        return self::DEFAULT_MESSAGE;
    }
}
