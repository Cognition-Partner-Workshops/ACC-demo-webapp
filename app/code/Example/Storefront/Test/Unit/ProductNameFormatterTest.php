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
        $this->assertSame('Nescafé Gold', $this->formatter->format('   Nescafé Gold   '));
    }

    /**
     * Repeated spaces inside the name should collapse to a single space.
     *
     * @return void
     */
    public function testCollapsesRepeatedInnerSpaces(): void
    {
        $this->assertSame('KitKat Chunky Bar', $this->formatter->format('KitKat   Chunky  Bar'));
    }

    /**
     * Trimming and collapsing should both apply together.
     *
     * @return void
     */
    public function testTrimsAndCollapsesTogether(): void
    {
        $this->assertSame('Smarties Sharing Box', $this->formatter->format('  Smarties   Sharing Box  '));
    }

    /**
     * Mixed whitespace (tabs, newlines) should also collapse to single spaces.
     *
     * @return void
     */
    public function testCollapsesMixedWhitespace(): void
    {
        $this->assertSame('Milo Energy Drink', $this->formatter->format("Milo\t\tEnergy\nDrink"));
    }

    /**
     * An already clean name should be returned unchanged.
     *
     * @return void
     */
    public function testLeavesCleanNameUnchanged(): void
    {
        $this->assertSame('Aero Peppermint', $this->formatter->format('Aero Peppermint'));
    }

    /**
     * A name made up entirely of whitespace collapses to an empty string.
     *
     * @return void
     */
    public function testReturnsEmptyStringForWhitespaceOnlyName(): void
    {
        $this->assertSame('', $this->formatter->format("   \t  "));
    }
}
