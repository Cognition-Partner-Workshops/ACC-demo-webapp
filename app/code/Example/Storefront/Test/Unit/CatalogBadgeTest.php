<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Test\Unit;

use Example\Storefront\Api\Data\BadgeRuleInterface;
use Example\Storefront\Model\CatalogBadge;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Unit tests for the CatalogBadge service.
 */
class CatalogBadgeTest extends TestCase
{
    /**
     * When no rules are injected the service should return an empty array.
     *
     * @return void
     */
    public function testReturnsEmptyBadgesWhenNoRules(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $service = new CatalogBadge($logger);

        $this->assertSame([], $service->getBadges(1));
    }

    /**
     * Rules that resolve to a label should appear in the result.
     *
     * @return void
     */
    public function testCollectsBadgesFromMatchingRules(): void
    {
        $ruleA = $this->createMock(BadgeRuleInterface::class);
        $ruleA->method('resolve')->willReturn('New Arrival');

        $ruleB = $this->createMock(BadgeRuleInterface::class);
        $ruleB->method('resolve')->willReturn(null);

        $ruleC = $this->createMock(BadgeRuleInterface::class);
        $ruleC->method('resolve')->willReturn('Sale');

        $logger = $this->createMock(LoggerInterface::class);
        $service = new CatalogBadge($logger, [$ruleA, $ruleB, $ruleC]);

        $this->assertSame(['New Arrival', 'Sale'], $service->getBadges(42));
    }

    /**
     * Rules that return null should be excluded from the result.
     *
     * @return void
     */
    public function testExcludesNullRules(): void
    {
        $rule = $this->createMock(BadgeRuleInterface::class);
        $rule->method('resolve')->willReturn(null);

        $logger = $this->createMock(LoggerInterface::class);
        $service = new CatalogBadge($logger, [$rule]);

        $this->assertSame([], $service->getBadges(7));
    }

    /**
     * The service should log how many badges were resolved.
     *
     * @return void
     */
    public function testLogsBadgeCount(): void
    {
        $rule = $this->createMock(BadgeRuleInterface::class);
        $rule->method('resolve')->willReturn('Bestseller');

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method('debug')
            ->with($this->stringContains('1 badge(s)'));

        $service = new CatalogBadge($logger, [$rule]);
        $service->getBadges(10);
    }
}
