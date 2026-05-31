<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Block;

use Example\Storefront\Api\ProductTrustBadgeInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Block that renders the product trust badge on the product detail page.
 */
class ProductTrustBadge extends Template
{
    /**
     * @var ProductTrustBadgeInterface
     */
    private ProductTrustBadgeInterface $trustBadge;

    /**
     * @param Context                    $context
     * @param ProductTrustBadgeInterface $trustBadge
     * @param mixed[]                    $data
     */
    public function __construct(
        Context $context,
        ProductTrustBadgeInterface $trustBadge,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->trustBadge = $trustBadge;
    }

    /**
     * Return the trust badge message for the template.
     *
     * @return string
     */
    public function getBadgeMessage(): string
    {
        return $this->trustBadge->getMessage();
    }

    /**
     * Check whether the trust badge should be rendered.
     *
     * @return bool
     */
    public function isBadgeEnabled(): bool
    {
        return $this->trustBadge->isEnabled();
    }
}
