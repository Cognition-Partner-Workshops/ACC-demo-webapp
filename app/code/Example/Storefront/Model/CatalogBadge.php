<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Model;

use Example\Storefront\Api\CatalogBadgeInterface;
use Example\Storefront\Api\Data\BadgeRuleInterface;
use Psr\Log\LoggerInterface;

/**
 * Evaluates all registered badge rules and returns applicable badges for a product.
 */
class CatalogBadge implements CatalogBadgeInterface
{
    /**
     * @var BadgeRuleInterface[]
     */
    private array $rules;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param LoggerInterface $logger
     * @param BadgeRuleInterface[] $rules
     */
    public function __construct(
        LoggerInterface $logger,
        array $rules = []
    ) {
        $this->logger = $logger;
        $this->rules = $rules;
    }

    /**
     * @inheritDoc
     */
    public function getBadges(int $productId): array
    {
        $badges = [];

        foreach ($this->rules as $rule) {
            $label = $rule->resolve($productId);
            if ($label !== null) {
                $badges[] = $label;
            }
        }

        $this->logger->debug(
            sprintf(
                'CatalogBadge: product %d resolved %d badge(s).',
                $productId,
                count($badges)
            )
        );

        return $badges;
    }
}
