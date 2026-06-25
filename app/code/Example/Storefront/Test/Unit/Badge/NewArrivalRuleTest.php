<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Test\Unit\Badge;

use Example\Storefront\Model\Badge\NewArrivalRule;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the NewArrivalRule badge rule.
 */
class NewArrivalRuleTest extends TestCase
{
    /**
     * Even product IDs should receive the "New Arrival" badge.
     *
     * @return void
     */
    public function testEvenProductIdReturnsBadge(): void
    {
        $rule = new NewArrivalRule();
        $this->assertSame('New Arrival', $rule->resolve(4));
    }

    /**
     * Odd product IDs should not qualify for this badge.
     *
     * @return void
     */
    public function testOddProductIdReturnsNull(): void
    {
        $rule = new NewArrivalRule();
        $this->assertNull($rule->resolve(5));
    }
}
