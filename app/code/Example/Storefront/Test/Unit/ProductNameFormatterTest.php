<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Test\Unit;

use Example\Storefront\Model\ProductNameFormatter;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the ProductNameFormatter model.
 */
class ProductNameFormatterTest extends TestCase
{
    /**
     * @var ProductNameFormatter
     */
    private ProductNameFormatter $formatter;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->formatter = new ProductNameFormatter();
    }

    /**
     * Leading and trailing whitespace should be removed.
     *
     * @return void
     */
    public function testTrimsLeadingAndTrailingWhitespace(): void
    {
        $this->assertSame('Espresso Roast', $this->formatter->format('   Espresso Roast   '));
    }

    /**
     * Repeated interior spaces should collapse to a single space.
     *
     * @return void
     */
    public function testCollapsesRepeatedInteriorSpaces(): void
    {
        $this->assertSame('Espresso Roast Coffee', $this->formatter->format('Espresso    Roast  Coffee'));
    }

    /**
     * Trimming and collapsing should both apply together.
     *
     * @return void
     */
    public function testTrimsAndCollapsesTogether(): void
    {
        $this->assertSame('Dark Chocolate Bar', $this->formatter->format('  Dark   Chocolate    Bar  '));
    }

    /**
     * Mixed whitespace (tabs, newlines) should collapse to single spaces.
     *
     * @return void
     */
    public function testCollapsesMixedWhitespace(): void
    {
        $this->assertSame('Green Tea', $this->formatter->format("Green\t\tTea"));
        $this->assertSame('Green Tea', $this->formatter->format("Green\n Tea"));
    }

    /**
     * An already-clean name should be returned unchanged.
     *
     * @return void
     */
    public function testLeavesCleanNameUnchanged(): void
    {
        $this->assertSame('Caramel Latte', $this->formatter->format('Caramel Latte'));
    }

    /**
     * A blank or whitespace-only name should become an empty string.
     *
     * @return void
     */
    public function testWhitespaceOnlyNameBecomesEmpty(): void
    {
        $this->assertSame('', $this->formatter->format('     '));
        $this->assertSame('', $this->formatter->format(''));
    }
}
