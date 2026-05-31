<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Test\Unit;

use Example\Storefront\Model\NameNormalizer;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the NameNormalizer model.
 */
class NameNormalizerTest extends TestCase
{
    /**
     * Whitespace should be trimmed and collapsed.
     *
     * @dataProvider normalizeProvider
     * @param string $input
     * @param string $expected
     * @return void
     */
    public function testNormalize(string $input, string $expected): void
    {
        $normalizer = new NameNormalizer();

        $this->assertSame($expected, $normalizer->normalize($input));
    }

    /**
     * Data provider for normalization scenarios.
     *
     * @return array<string, array{string, string}>
     */
    public function normalizeProvider(): array
    {
        return [
            'trims edges' => ['  Coffee Beans  ', 'Coffee Beans'],
            'collapses internal runs' => ["Dark    Roast\tBlend", 'Dark Roast Blend'],
            'collapses newlines' => ["Organic\n\nTea", 'Organic Tea'],
            'already clean' => ['Sparkling Water', 'Sparkling Water'],
        ];
    }
}
