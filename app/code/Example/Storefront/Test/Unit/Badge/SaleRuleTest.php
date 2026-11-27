<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Test\Unit\Badge;

use Example\Storefront\Model\Badge\SaleRule;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the SaleRule badge rule.
 */
class SaleRuleTest extends TestCase
{
    /**
     * Product IDs divisible by 3 should receive the "Sale" badge.
     *
     * @return void
     */
    public function testDivisibleByThreeReturnsBadge(): void
    {
        $rule = new SaleRule();
        $this->assertSame('Sale', $rule->resolve(9));
    }

    /**
     * Product IDs not divisible by 3 should not qualify.
     *
     * @return void
     */
    public function testNotDivisibleByThreeReturnsNull(): void
    {
        $rule = new SaleRule();
        $this->assertNull($rule->resolve(7));
    }
}
